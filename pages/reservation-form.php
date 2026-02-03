<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit;
}

$error = "";

// Définir les massages et leur durée
$massages = [
    "Massage relaxant" => 1,
    "Massage tonifiant" => 1,
    "Massage Shiatsu" => 1,
    "Massage aux pierres chaudes" => 1,
    "Massage aromathérapie" => 1,
    "Massage détente luxe" => 2
];

// Pré-remplissage si date et heure depuis planning
$prefillDate = $_GET['date'] ?? '';
$prefillHour = $_GET['hour'] ?? '';

$selectedMassage = $_POST['title'] ?? '';

// Récupérer les événements pour la date choisie
$reservedHours = [];
if (!empty($_POST['date']) || $prefillDate) {
    $dateKey = $_POST['date'] ?? $prefillDate;

    $stmt = $pdo->prepare("SELECT start_date, end_date FROM event WHERE DATE(start_date)=?");
    $stmt->execute([$dateKey]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($events as $e) {
        $s = (int)date('H', strtotime($e['start_date']));
        $eH = (int)date('H', strtotime($e['end_date']));
        for ($h=$s; $h<$eH; $h++) {
            $reservedHours[] = $h;
        }
    }
}

// Déterminer les heures disponibles selon le massage sélectionné
$availableHours = [];
$duration = $massages[$selectedMassage] ?? 1;
for ($h=9; $h<=18; $h++) {
    $canBook = true;
    for ($i=0; $i<$duration; $i++) {
        if (in_array($h+$i, $reservedHours) || ($h+$i)>=19) {
            $canBook = false;
            break;
        }
    }
    if ($canBook) $availableHours[] = $h;
}

// Traitement du formulaire (UNIQUEMENT au clic sur "Réserver")
if (!empty($_POST) && isset($_POST['hour'])) {
    $title = $_POST['title'] ?? '';
    $hour = (int)($_POST['hour'] ?? 0);
    $date = $_POST['date'] ?? '';

    if (!$title || !isset($massages[$title])) {
        $error = "Veuillez choisir un massage valide.";
    } elseif (!$date || !$hour) {
        $error = "Veuillez choisir une date et une heure valide.";
    } else {
        $duration = $massages[$title];

        // Vérifier que le créneau est libre et ne dépasse pas 19h
        $canBook = true;
        for ($i=0; $i<$duration; $i++) {
            if (in_array($hour+$i, $reservedHours) || ($hour+$i)>=19) {
                $canBook = false;
                break;
            }
        }

        if (!$canBook) {
            $error = "Ce créneau n'est pas disponible.";
        } else {
            $start = new DateTime($date.' '.$hour.':00:00');
            $end = clone $start;
            $end->modify("+$duration hour");

            $stmt = $pdo->prepare("
                INSERT INTO event (event_title, start_date, end_date, creator_id)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $title,
                $start->format('Y-m-d H:i:s'),
                $end->format('Y-m-d H:i:s'),
                $_SESSION['user']['id']
            ]);

            header("Location: schedule.php");
            exit;
        }
    }
}
?>

<h2>Nouvelle réservation</h2>
<?php if($error) echo '<p class="form-error">'.$error.'</p>'; ?>

<form method="post">
    <label>Type de massage</label>
    <select name="title" required>
        <option value="">-- Choisir un massage --</option>
        <?php foreach($massages as $name => $dur): ?>
            <option value="<?= htmlspecialchars($name) ?>" <?= ($selectedMassage==$name) ? 'selected' : '' ?>>
                <?= $name ?> (<?= $dur ?>h)
            </option>
        <?php endforeach; ?>
    </select>

    <label>Date</label>
    <input type="date" name="date" value="<?= $_POST['date'] ?? $prefillDate ?>" required>

    <label>Heure</label>
    <select name="hour" required>
        <option value="">-- Choisir un créneau --</option>
        <?php foreach($availableHours as $h): ?>
            <option value="<?= $h ?>" <?= ((isset($_POST['hour']) && $_POST['hour']==$h) || $prefillHour==$h) ? 'selected' : '' ?>>
                <?= $h ?>h
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Réserver">
</form>

<?php include '../includes/footer.php'; ?>
