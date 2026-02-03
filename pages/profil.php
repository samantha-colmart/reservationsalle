<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
    exit;
}

$error = "";
$success = "";

/* =======================
   Modification login / mot de passe
======================= */
if (!empty($_POST) && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (username_exists($pdo, $username, $_SESSION['user']['id'])) {
        $error = "Ce nom d'utilisateur est déjà utilisé.";
    } elseif ($password && $password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $hash = $password ? password_hash($password, PASSWORD_DEFAULT) : $_SESSION['user']['password'];

        $stmt = $pdo->prepare("UPDATE user SET username=?, password=? WHERE id=?");
        $stmt->execute([$username, $hash, $_SESSION['user']['id']]);
        $_SESSION['user']['username'] = $username;
        $success = "Profil mis à jour avec succès !";
    }
}

/* =======================
   Récupérer les réservations de l’utilisateur
======================= */
$stmt = $pdo->prepare("SELECT * FROM event WHERE creator_id=? ORDER BY start_date ASC");
$stmt->execute([$_SESSION['user']['id']]);
$reservations = $stmt->fetchAll();
?>

<h2>Profil</h2>

<?php
if ($error) echo '<p class="form-error">'.$error.'</p>';
if ($success) echo '<p class="form-success">'.$success.'</p>';
?>

<!-- Formulaire modification login/mot de passe -->
<h3>Modifier mon profil</h3>
<form method="post">
    <input type="hidden" name="update_profile">
    <label>Login</label>
    <input type="text" name="username" value="<?= htmlspecialchars($_SESSION['user']['username']) ?>" required>
    <label>Nouveau mot de passe</label>
    <input type="password" name="password" placeholder="Laisser vide pour garder l'ancien">
    <label>Confirmer mot de passe</label>
    <input type="password" name="confirm_password">
    <input type="submit" value="Modifier">
</form>

<hr>

<h3>Mes réservations</h3>

<?php if (empty($reservations)): ?>
    <p>Vous n'avez encore réservé aucun massage.</p>
<?php else: ?>
    <table border="1" cellpadding="5" style="border-collapse: collapse; width:100%; text-align:center;">
        <tr>
            <th>Début</th>
            <th>Fin</th>
            <th>Détails / Modifier</th>
        </tr>
        <?php foreach($reservations as $res): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($res['start_date'])) ?></td>
                <td><?= date('H:i', strtotime($res['end_date'])) ?></td>
                <td>
                    <a href="reservation_detail.php?id=<?= $res['id'] ?>">Voir / Modifier / Annuler</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>