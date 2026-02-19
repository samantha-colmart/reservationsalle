<?php
include '../includes/config.php';
include '../includes/header.php';
include '../includes/tools.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

$creneau = null;
if(isset($_GET['date'])){
    $creneau = $_GET['date'];
}

$jour_choisi = date("Y-m-d", strtotime($creneau));
$heure_choisie = date("H:i", strtotime($creneau));
$semaine = get_days();
$heures = get_hours();

$user = get_information_user($pdo, $_SESSION['id']);
$massages = get_all_services($pdo);


$event_id = null;
$event = null;

if (isset($_GET['id'])) {
    $event_id = (int)$_GET['id'];
    $event = event_by_id($pdo, $event_id);
}



$error = '';

if (!empty($_POST['step'])) {
    $duration = null;
    if (isset($_POST['service']) && $_POST['service'] !== 'libre') {
        $duration = get_duration_by_service($massages, $_POST['service']);
    }
    if ($_POST['step'] === 'Réserver' || $_POST['step'] === 'Modifier') {
        $result = event_process($pdo, $_POST, $_SESSION['id'], $duration, $event_id);
        if ($result !== true) {
            $error = $result;
        } else {
            header("Location: schedule.php");
            exit;
        }
    }
}
?>


<section class="container-form">
    <article class="auth-header">
        <i class="auth-icon fa-solid fa-calendar-check"></i>
        <?php
        if ($event_id !== null) {
            echo '<h1>Modifier la réservation</h1>';
        } else {
            echo '<h1>Formulaire de réservation</h1>';
        }
        ?>
        <p class="subtitle">Utilisateur : <?php echo htmlspecialchars($user['username']); ?></p>
    </article>
    <?php
    if (!empty($error)) {
        echo '<p class="form-error">' . $error . '</p>';
    }
    ?>
    <form method="post">
        <label for="service">Choix de la prestation</label>
        <select name="service" id="service">
        <?php
        $selected_service = '';
        if ($event_id !== null && !empty($event)) {
            $selected_service = $event[0]['event_title'];
        } 
        elseif (isset($_POST['service'])) {
            $selected_service = $_POST['service'];
        }
        if ($selected_service === 'libre' || $selected_service === '') {
            echo '<option value="libre" selected>Prestation libre</option>';
        } else {
            echo '<option value="libre">Prestation libre</option>';
        }
        foreach ($massages as $massage) {
            if ($selected_service === $massage['name']) {
                echo '<option value="' . htmlspecialchars($massage['name']) . '" selected>' . htmlspecialchars($massage['name']) . '</option>';
            } else {
                echo '<option value="' . htmlspecialchars($massage['name']) . '">' . htmlspecialchars($massage['name']) . '</option>';
            }
        }
        ?>
        </select>
        <input type="submit" name="step" value="Choisir cette prestation">
        <?php 
        if($selected_service !== "libre" && $selected_service !== 'Prestation libre' && $selected_service !== ''){
            echo '<p>Durée : ' . get_duration_by_service($massages, $selected_service) . ' h</p>';
        }
        ?>
        <label for="debut">Heure de début</label>
        <select name="debut" id="debut">
            <?php
            if($event_id !== null){
                $heure_choisie = date("H:i", strtotime($event[0]['start_date']));
            }
            $hour = 2;
            if($selected_service !== "libre" && $selected_service !== 'Prestation libre' && $selected_service !== ''){
                $hour = (int)get_duration_by_service($massages, $selected_service)+1;
            }
             for ($i = 0; $i <= count($heures)-$hour; $i++){
                if ($heures[$i] == $heure_choisie){ 
                    echo '<option value="' . $heures[$i] . '" selected>' . $heures[$i] . '</option>'; 
                } else { 
                    echo '<option value="' . $heures[$i] . '">' . $heures[$i] . '</option>';
                } 
            }
            ?>
        </select>
        <?php
        if ($selected_service === 'libre' || $selected_service === 'Prestation libre' || $selected_service === ''){ 
            echo '<label for="fin">Heure de fin</label> 
            <select name="fin" id="fin">';
            for ($i = 1; $i < count($heures); $i++) { 
                if(strtotime($heures[$i]) === strtotime($heure_choisie . "+1 hour")){ 
                    echo "<option value=".$heures[$i]." selected>".$heures[$i]."</option>"; 
            } else{ 
                echo "<option value=".$heures[$i].">".$heures[$i]."</option>"; 
                } 
            } 
            echo '</select>'; 
        }
        ?>
        <label for="jour">Date</label>
        <?php
        $date_value = $jour_choisi;
        if ($event_id !== null) {
            $date_value = date('Y-m-d', strtotime($event[0]['start_date']));
        }
        echo '<input type="date" name="jour" id="jour" value="' . $date_value .  '"min="' . date("Y-m-d") . '"max="' . $semaine[4] . '">';
        ?>
        <label for="description">Description</label>
        <?php  
        if ($event_id !== null) {
                echo '<textarea name="description" id="description" maxlength="450">' . htmlspecialchars($event[0]['description']) . '</textarea>';
        }else {
            echo '<textarea name="description" id="description" maxlength="450"></textarea>';
        }
        if ($event_id !== null) {
            echo '<input type="submit" name="step" value="Modifier">';
        } else {
            echo '<input type="submit" name="step" value="Réserver">';
        }
        ?>
    </form>
</section>

<?php include '../includes/footer.php'; ?>