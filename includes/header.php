<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Salon de massage Zénitude</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
    <header>
        <h1>Salon de Massage Zenitude</h1>
        <nav>
            <ul>
                <li>
                    <a href="accueil.php">Accueil</a>
                </li>
                <?php
                    if(!empty($_SESSION['id'])){
                        echo '
                        <li><a href="schedule.php">Planning</a></li>
                        <li><a href="profil.php">Profil</a></li>
                        <li><a href="deconnexion.php">Déconnexion</a></li>';
                    } else{
                        echo '
                        <li><a href="signup.php">Inscription</a></li>
                        <li><a href="signin.php">Connexion</a></li>';
                    }
                    ?>
            </ul>
        </nav>
        <hr>
    </header>
<main>