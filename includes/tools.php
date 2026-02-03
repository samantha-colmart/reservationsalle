<?php

// Vérification si les champs du formulaire sont bien remplis
function empty_fields($post, $fields) {
    foreach ($fields as $field) {
        if (empty($post[$field])) {
            return true;
        }
    }
    return false;
}



// Vérification validité données inscription utilisateur
function field_verification($username, $password, $confirm_password){
    if (strlen($username) < 3 || strlen($username) > 255) {
        return "Le login doit contenir entre 3 et 255 caractères";
    }
    elseif (strlen($password) < 6 || !preg_match('/[0-9]/', $password)) {
        return "Le mot de passe doit contenir au moins 6 caractères et au moins un chiffre";
    }
    elseif ($password != $confirm_password) {
        return "Les deux mots de passe doivent être égaux";
    }
    return true;
}



// Récupération des informations d'un utilisateur
function user_by_username($pdo, $username) {
    $sql = "SELECT * FROM user WHERE username = :username";
    $query = $pdo->prepare($sql);
    $query->execute([':username' => $username]);
    return $query->fetch(PDO::FETCH_ASSOC);
}



// Vérification si username existe déjà dans la BDD
function username_exists($pdo, $username, $id = null) {
    if ($id === null) {
        $sql = "SELECT id FROM user WHERE username = :username";
        $params = [':username' => $username];
    } else {
        $sql = "SELECT id FROM user WHERE username = :username AND id != :id";
        $params = [':username' => $username, ':id' => $id];
    }
    $query = $pdo->prepare($sql);
    $query->execute($params);
    if ($query->fetch(PDO::FETCH_ASSOC)) {
        return true;
    }
    return false;
}



// Fonction pour enregistrer les données d'inscription de l'utilisateur dans la BDD
function recording($pdo, $username, $password) {
    if (username_exists($pdo, $username)) {
        return "Ce nom d'utilisateur est déjà utilisé";
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (username, password) VALUES (:username, :password)";
    $query = $pdo->prepare($sql);
    $query->execute([':username' => $username, ':password' => $hash]);
    return true;
}



// Processus d'appel pour vérification puis enregistrement inscription dans BDD
function sign_up($pdo, $post) {
    if (empty_fields($post, ["username", "password", "confirm_password"])) {
        return "Veuillez remplir l'ensemble des champs.";
    }
    $result = field_verification(trim($post["username"]), $post["password"], $post["confirm_password"]);
    if ($result === true) {
        return recording($pdo, trim($post["username"]), $post["password"]);
    }
    return $result;
}