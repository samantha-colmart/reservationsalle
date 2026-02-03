<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

$error = "";

if (!empty($_POST)) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $user = user_by_username($pdo, $username);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: schedule.php");
        exit;
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<h2>Connexion</h2>
<?php if($error) echo '<p class="form-error">'.$error.'</p>'; ?>
<form method="post">
    <label>Login</label>
    <input type="text" name="username" required>
    <label>Mot de passe</label>
    <input type="password" name="password" required>
    <input type="submit" value="Se connecter">
</form>
<p>Pas encore de compte ? <a href="signup.php">S'inscrire</a></p>

<?php include '../includes/footer.php'; ?>