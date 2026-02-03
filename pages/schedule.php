<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit;
}

/* Configuration du planning */
$startHour = 9;  // Début des créneaux
$endHour = 18;   // Dernier créneau possible pour 1h

/* Semaine courante */
$startOfWeek = new DateTime('monday this week');
$endOfWeek = clone $startOfWeek;
$endOfWeek->modify('+4 days');

/* Récupération des événements de la semaine */
$stmt = $pdo->prepare("
    SELECT * 
    FROM event 
    WHERE start_date BETWEEN ? AND ?
");
$stmt->execute([
    $startOfWeek->format('Y-m-d 00:00:00'),
    $endOfWeek->format('Y-m-d 23:59:59')
]);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Indexer toutes les heures occupées */
$eventMap = [];
foreach ($events as $event) {
    $date = date('Y-m-d', strtotime($event['start_date']));
    $startH = (int)date('H', strtotime($event['start_date']));
    $endH = (int)date('H', strtotime($event['end_date']));

    for ($h = $startH; $h < $endH; $h++) {
        $eventMap[$date][$h] = true;
    }
}
?>

<h2>Planning des massages</h2>

<table border="1" cellpadding="8" style="border-collapse: collapse; text-align:center; width:100%; margin-top:15px;">
    <tr>
        <th>Heure</th>
        <?php for ($d = 0; $d < 5; $d++): 
            $day = clone $startOfWeek;
            $day->modify("+$d days");
        ?>
            <th><?= $day->format('l d/m') ?></th>
        <?php endfor; ?>
    </tr>

    <?php for ($h = $startHour; $h <= $endHour; $h++): ?>
        <tr>
            <th><?= $h ?>h</th>
            <?php for ($d = 0; $d < 5; $d++):
                $day = clone $startOfWeek;
                $day->modify("+$d days");
                $dateKey = $day->format('Y-m-d');
            ?>

                <?php 
                // Vérifier si le créneau est occupé
                if (!empty($eventMap[$dateKey][$h])): ?>
                    <td style="background:#e74c3c; color:#fff;">Indisponible</td>
                <?php else: ?>
                    <td style="background:#2ecc71; color:#fff;">
                        <a href="reservation-form.php?date=<?= $dateKey ?>&hour=<?= $h ?>" style="color:#fff; text-decoration:none;">Libre</a>
                    </td>
                <?php endif; ?>

            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>

<p style="margin-top:15px;">
    <span style="background:#2ecc71; color:#fff; padding:2px 5px;">Libre</span> 
    <span style="background:#e74c3c; color:#fff; padding:2px 5px;">Indisponible</span>
</p>

<?php include '../includes/footer.php'; ?>
