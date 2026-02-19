<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit;
}

$semaine = get_days();
$heures = get_hours();
$delete = array_pop($heures);
$debutSemaine = date('Y-m-d 00:00:00', strtotime(min($semaine)));
$finSemaine = date('Y-m-d 23:59:59', strtotime(max($semaine)));
$events = get_all($pdo, $debutSemaine, $finSemaine);
foreach ($events as $event) {
    $event['start_ts'] = strtotime($event['start_date']);
    $event['end_ts']   = strtotime($event['end_date']);
}

?>

<div class="table-container">
  <table>
    <tr>
      <td>&nbsp;</td>
      <?php
      foreach ($semaine as $jour) {
        $timestamp = strtotime($jour);
        $formatter = new IntlDateFormatter('fr_FR',IntlDateFormatter::FULL,IntlDateFormatter::NONE);
        echo '<td>' . $formatter->format($timestamp) . '</td>';
      }
      ?>
    </tr>
    <?php
    foreach ($heures as $heure) {
      echo '<tr>
            <td>' . $heure . '</td>';
      foreach ($semaine as $jour) {
          $start_ts = strtotime($jour . ' ' . $heure);
          $end_ts   = strtotime('+1 hour', $start_ts);
          $event = event_taken_hour($events, $start_ts, $end_ts);
          if ($event !== false) {
            if($event['creator_id'] === $_SESSION['id']){
              echo '<td class="slot my_slot">
                      <a href="reservation_detail.php?id=' . $event['id'] . '">
                        <p>' . htmlspecialchars($event['event_title']) . '</p>
                        <h3>Vous</h3>
                      </a>
                    </td>';
            } else{
            echo '<td class="slot taken">
                    <p>' . htmlspecialchars($event['event_title']) . '</p>
                    <h3>' . htmlspecialchars($event['username']) . '</h3>
                  </td>';
            }
          } elseif (date('N', $start_ts) >= 6 || strtotime("today") > strtotime($jour)){
              echo '<td class="slot impossible"></td>';
          } else {
              echo '<td class="slot available">
                  <a href="reservation-form.php?date=' . date('Y-m-d H:i:s', $start_ts) . '">
                      Réserver
                  </a>
              </td>';
          }
      }
      echo '</tr>';
    }
    ?>
  </table>
</div>

<?php include '../includes/footer.php'; ?>