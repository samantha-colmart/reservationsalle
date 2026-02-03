<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Salon de Massage Zenitude</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <h1>🌿 Salon de Massage Zenitude</h1>
    <nav>
        <ul>
            <li><a href="accueil.php">Accueil</a></li>
            <?php if(isset($_SESSION['user'])): ?>
                <li><a href="schedule.php">Planning</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="signin.php">Connexion</a></li>
                <li><a href="signup.php">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <hr>
</header>
<main>
