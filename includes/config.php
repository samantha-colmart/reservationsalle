<?php

session_start();
date_default_timezone_set("Europe/Paris");

$pdo = new PDO("mysql:host=localhost;dbname=room-reservation;charset=utf8","root","",[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


?>