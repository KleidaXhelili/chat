<?php
//Définition du fuseau horaire
date_default_timezone_set('Europe/Paris');

//Ouverture de session
session_start();

//Connexion à la BDD(à changer en ligne)
$pdo = new PDO(
    'mysql:host=localhost;charset=utf8;dbname=tchat',
    'root',
    '',
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, //en prod => ERRMODE_SILENT
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC //en prod => FETCH_OBJ
    )
);

//Définition de constante (à changer en ligne)
define('URLSITE', '/ajax/C-projet/');

//Inclusion du fichier de fonctions
require_once('functions.php');
