<?php
include '../includes/config.php';
include '../includes/header.php';

// Définir les massages avec photo et durée
$massages = [
    ["name" => "Massage relaxant", "duration" => 1, "image" => "../images/relaxant.jpg"],
    ["name" => "Massage tonifiant", "duration" => 1, "image" => "../images/tonifiant.jpg"],
    ["name" => "Massage Shiatsu", "duration" => 1, "image" => "../images/shiatsu.png"],
    ["name" => "Massage aux pierres chaudes", "duration" => 1, "image" => "../images/pierres.jpg"],
    ["name" => "Massage aromathérapie", "duration" => 1, "image" => "../images/aroma.jpg"],
    ["name" => "Massage détente luxe", "duration" => 2, "image" => "../images/luxe.jpeg"],
];
?>

<h2>Bienvenue au Salon de Massage Zenitude 🌿</h2>
<p>Découvrez nos massages relaxants et réservez facilement votre créneau en ligne.</p>

<section class="massages">
    <?php foreach($massages as $massage): ?>
        <div class="massage-card">
            <img src="<?= $massage['image'] ?>" alt="<?= htmlspecialchars($massage['name']) ?>">
            <h3><?= htmlspecialchars($massage['name']) ?></h3>
            <p>Durée : <?= $massage['duration'] ?> heure<?= $massage['duration'] > 1 ? 's' : '' ?></p>
        </div>
    <?php endforeach; ?>
</section>

<?php if(!isset($_SESSION['user'])): ?>
    <p>Pour réserver, veuillez vous <a href="signin.php">connecter</a> ou <a href="signup.php">créer un compte</a>.</p>
<?php else: ?>
    <p>Pour réserver un massage, accédez directement au <a href="schedule.php">planning</a>.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>