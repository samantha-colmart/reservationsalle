
<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

$error = "";

if (!empty($_POST)) {
    $result = profile_modification_process($pdo, $_POST);
    if ($result === true) {
        header("Location: profil.php");
        exit;
    } else {
        $error = $result;
    }
}


$error_suppression = "";
// Suppression message
if (!empty($_POST['delete'])) {
    $delete = (int)$_POST['delete'];
    if (!event_deletion($pdo, $delete, $_SESSION['id'])) {
        $error_suppression = "Impossible de supprimer ce message.";
    } else {
        header("Location: profil.php");
        exit;
    }
}

$information = get_information_user($pdo, $_SESSION['id']);

$semaine = get_days();
$heures = get_hours();
$debutSemaine = date('Y-m-d 00:00:00', strtotime(min($semaine)));
$finSemaine = date('Y-m-d 23:59:59', strtotime(max($semaine)));
$events = event_by_user_in_week($pdo, $_SESSION['id'], $debutSemaine, $finSemaine);

?>

<section class="container-form">
    <article class="auth-header">
        <i class="auth-icon fa-solid fa-user-pen"></i>
        <h1 class="title-profile">Mon Profil</h1>
        <p class="subtitle">Gérez vos informations personnelles</p>
    </article>
    <?php 
    if (!empty($error)){
        echo '<p class="form-error">' . $error .  '</p>';
    }
    ?>
    <form action="" method="POST">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($information['username']); ?>">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" placeholder="Nouveau mot de passe">
        <label for="confirm_password">Confirmation du mot de passe</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmation du mot de passe">
        <input type="submit" value="Modifier">
    </form>
</section>
<?php
if(!empty($events)){
    echo'  <section>
    <h2>Mes Rendez-vous :</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <td>Début</td>
                    <td>Fin</td>
                    <td>Voir le détail</td>
                    <td>Modifier le RDV</td>
                    <td>Annuler</td>
                </tr>
            </thead>
            <tbody>';
                foreach ($events as $event) {
                    echo '<tr>
                        <td>' . htmlspecialchars(date("d/m/Y", strtotime($event['start_date']))) . ' à ' . htmlspecialchars(date("H:i", strtotime($event['start_date']))) . '</td>
                        <td>' . htmlspecialchars(date("d/m/Y", strtotime($event['end_date']))) . ' à ' . htmlspecialchars(date("H:i", strtotime($event['end_date']))) . '</td>
                        <td>
                            <a href="reservation_detail.php?id=' . $event['id'] . '">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </a>
                        </td>
                        <td>
                            <a href="reservation-form.php?id=' . $event['id'] . '">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete" value="' . htmlspecialchars($event['id']) . '">
                                <button type="submit" class="trash">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>';
                }
            echo '</tbody>
        </table>
    </div>
</section>';
}
?>

<?php include '../includes/footer.php'; ?>