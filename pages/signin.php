
<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';


$error = "";

if (!empty($_POST)) {
    $result = username_process($pdo, $_POST);
    if ($result === true) {
        header("Location: schedule.php");
        exit;
    } else {
        $error = $result;
    }
}
?>



<section class="container-form">
    <article class="auth-header">
        <i class="auth-icon fa-solid fa-arrow-right-to-bracket"></i>
        <h1 class="title-login">Connexion</h1>
        <p class="subtitle">Accédez à votre espace personnel</p>
    </article>
    <?php 
    if (!empty($error)){
        echo '<p class="form-error">' . $error .  '</p>';
    }
    ?>
    <form action="" method="POST">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Votre identifiant">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" id="password" placeholder="Votre mot de passe">
        <input type="submit" value="Se connecter">
    </form>
    <p class="footer-link">
        Pas encore de compte ? <a href="signup.php">S'inscrire</a>
    </p>
</section>



<?php include '../includes/footer.php'; ?>