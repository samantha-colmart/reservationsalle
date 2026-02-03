<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

$error = "";

if (!empty($_POST)) {
    $result = sign_up($pdo, $_POST);

    if ($result === true) {
        header("Location: signin.php");
        exit;
    } else {
        $error = $result;
    }
}


?>

<main>
    <section class="container-form">
        <article class="auth-header">
            <i class="auth-icon fa-solid fa-user-plus"></i>
            <h1>Inscription</h1>
            <p class="subtitle">Créez votre compte</p>
        </article>
        <?php 
        if (!empty($error)){
            echo '<p class="form-error">' . $error .  '</p>';
        }
        ?>
        <form action="" method="POST">
            <label for="username">Login</label>
            <input type="text" name="username" id="username" placeholder="Votre identifiant">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Min. 6 caractères">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez votre mot de passe">
            <input type="submit" value="S'inscrire">
        </form>
        <p class="footer-link">
            Vous avez déjà un compte ? <a href="signin.php">Se connecter</a>
        </p>
    </section>
</main>


<?php include '../includes/footer.php'; ?>