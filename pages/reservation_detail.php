<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

if(isset($_GET['id'])){
    $creneau = $_GET['id'];
}


$details = event_by_id($pdo, $creneau);

$error_suppression = "";

if (!empty($_POST['delete'])) {
    $delete = (int)$_POST['delete'];
    if (!event_deletion($pdo, $delete, $_SESSION['id'])) {
        $error_suppression = "Impossible de supprimer ce message.";
    } else {
        header("Location: profil.php");
        exit;
    }
}

?>


<section class="details">
    <h2>Détails de la réservation</h2>
    <?php
    if (!empty($error)) {
        echo '<p class="form-error">' . $error . '</p>';
    }
    ?>
    <?php
    $timestamp = strtotime($details[0]['start_date']);
    $formatter = new IntlDateFormatter('fr_FR',IntlDateFormatter::FULL,IntlDateFormatter::NONE);
    echo '<p><strong>Massage :</strong> ' . htmlspecialchars($details[0]['event_title']) . '</p>
        <p><strong>Jour de la réservation :</strong> ' . $formatter->format($timestamp) . '</p>
        <p><strong>Heure de début :</strong> ' . date('H:i', strtotime($details[0]['start_date'])) . '</p>
        <p><strong>Heure de fin :</strong> ' . date('H:i', strtotime($details[0]['end_date'])) . '</p>
        <p><strong>Description du massage : </strong>' . htmlspecialchars($details[0]['description']) . '</p>';
    ?>
    <h3>Modifier ou annuler la réservation</h3>
    <article>
        <a href="reservation-form.php?id=<?php echo (int)$details[0]['id']; ?>">
            Modifier la réservation
        </a>
        <form method="POST">
            <input type="hidden" name="delete" value="<?php echo (int)$details[0]['id']; ?>">
            <button type="submit" class="trash">
                Annuler la réservation
            </button>
        </form>
    </article>
</section>


<?php include '../includes/footer.php'; ?>