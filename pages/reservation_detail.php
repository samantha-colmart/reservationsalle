<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<p>Réservation introuvable.</p>";
    include '../includes/footer.php';
    exit;
}

// Définir massages, tarifs et descriptions
$massages = [
    "Massage relaxant" => [
        'duration'=>1,
        'price'=>60,
        'description'=>"Massage doux et enveloppant sur tout le corps avec des mouvements lents et fluides pour relâcher les tensions, apaiser le stress et favoriser un état de profonde détente."
    ],
    "Massage tonifiant" => [
        'duration'=>1,
        'price'=>65,
        'description'=>"Techniques plus énergiques ciblant les muscles tendus et fatigués. Stimule la circulation, améliore la souplesse et prépare le corps à l’effort ou récupère après l’activité sportive."
    ],
    "Massage Shiatsu" => [
        'duration'=>1,
        'price'=>75,
        'description'=>"Massage japonais profond appliquant des pressions sur les points énergétiques et muscles pour libérer les tensions chroniques, améliorer la posture et rééquilibrer l’énergie du corps."
    ],
    "Massage aux pierres chaudes" => [
        'duration'=>1,
        'price'=>80,
        'description'=>"Des pierres volcaniques chauffées sont placées sur les zones clés du corps et utilisées pour masser. La chaleur détend profondément les muscles, stimule la circulation sanguine et procure un confort et une relaxation intense."
    ],
    "Massage aromathérapie" => [
        'duration'=>1,
        'price'=>70,
        'description'=>"Massage relaxant combiné à des huiles essentielles adaptées à vos besoins (relaxation, énergie ou équilibre). Un véritable moment sensoriel pour le corps et l’esprit."
    ],
    "Massage détente luxe" => [
        'duration'=>2,
        'price'=>120,
        'description'=>"Une expérience complète alliant massage relaxant, aromathérapie et soins spécifiques (pieds, tête ou épaules). Parfait pour un moment de bien-être total et un dépaysement sensoriel."
    ]
];

// Récupérer la réservation uniquement si c'est l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM event WHERE id=? AND creator_id=?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    echo "<p>Réservation introuvable.</p>";
    include '../includes/footer.php';
    exit;
}

$error = "";
$success = "";

/* =======================
   Traitement formulaire : modification ou annulation
======================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Annuler la réservation
    if (isset($_POST['cancel'])) {
        $stmt = $pdo->prepare("DELETE FROM event WHERE id=?");
        $stmt->execute([$id]);
        header("Location: profil.php");
        exit;
    }

    // Modifier le massage
    if (isset($_POST['modify'])) {
        $newMassage = $_POST['title'] ?? '';
        if (!$newMassage || !isset($massages[$newMassage])) {
            $error = "Veuillez choisir un massage valide.";
        } else {
            $newDuration = $massages[$newMassage]['duration'];
            $start = new DateTime($reservation['start_date']);
            $end = clone $start;
            $end->modify("+$newDuration hour");

            // Vérifier si le créneau est libre (hors cette réservation)
            $stmt = $pdo->prepare("SELECT start_date,end_date FROM event WHERE DATE(start_date)=? AND id!=?");
            $stmt->execute([$start->format('Y-m-d'), $reservation['id']]);
            $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $canBook = true;
            for ($h=(int)$start->format('H'); $h < (int)$start->format('H')+$newDuration; $h++) {
                if ($h>=19) { // ne pas dépasser 19h
                    $canBook = false;
                    break;
                }
                foreach ($events as $e) {
                    $s = (int)date('H', strtotime($e['start_date']));
                    $eH = (int)date('H', strtotime($e['end_date']));
                    if ($h >= $s && $h < $eH) {
                        $canBook = false;
                        break 2;
                    }
                }
            }

            if (!$canBook) {
                $error = "Ce créneau n'est pas disponible pour le massage choisi.";
            } else {
                // Mettre à jour
                $stmt = $pdo->prepare("UPDATE event SET event_title=?, end_date=? WHERE id=?");
                $stmt->execute([$newMassage, $end->format('Y-m-d H:i:s'), $id]);
                $success = "Votre réservation a été mise à jour.";
                $reservation['event_title'] = $newMassage;
                $reservation['end_date'] = $end->format('Y-m-d H:i:s');
            }
        }
    }
}

// Tarif et description du massage
$massageName = $reservation['event_title'];
$tarif = $massages[$massageName]['price'];
$descriptionInfo = $massages[$massageName]['description'];
?>

<h2>Détails de la réservation</h2>

<?php if($error) echo '<p class="form-error">'.$error.'</p>'; ?>
<?php if($success) echo '<p class="form-success">'.$success.'</p>'; ?>

<p><strong>Massage :</strong> <?= htmlspecialchars($reservation['event_title']) ?></p>
<p><strong>Heure de début :</strong> <?= date('d/m/Y H:i', strtotime($reservation['start_date'])) ?></p>
<p><strong>Heure de fin :</strong> <?= date('H:i', strtotime($reservation['end_date'])) ?></p>
<p><strong>Tarif :</strong> <?= $tarif ?>€</p>

<!-- Description informative du massage (non modifiable) -->
<p><strong>Description du massage :</strong><br><?= nl2br(htmlspecialchars($descriptionInfo)) ?></p>

<h3>Modifier ou annuler la réservation</h3>
<form method="post">
    <label>Modifier le massage</label>
    <select name="title">
        <option value="">-- Choisir un massage --</option>
        <?php foreach($massages as $name=>$info): ?>
            <option value="<?= $name ?>" <?= ($name==$reservation['event_title'])?'selected':'' ?>>
                <?= $name ?> (<?= $info['duration'] ?>h)
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="modify" value="Modifier">
    <input type="submit" name="cancel" value="Annuler la réservation" style="background:#e74c3c; color:#fff;">
</form>

<?php include '../includes/footer.php'; ?>
