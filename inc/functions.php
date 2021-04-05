<?php
//Fonction qui indique si l'utilisateur est connecté
function isConnected(){
   
    return (isset($_SESSION['user']));
}

//Fonction qui va traiter nos requêtes SQL
function goSQL($sql, $params=array() ){
    
    //je rends accessible la variable $pdo déclarée à l'extérieur de la fonction, en l'occurence dans init.php
    global $pdo;

    if (!empty($params)) {
        foreach($params as $key => $value){
            $params[$key] = htmlspecialchars($value);
        }
    }

    $requete = $pdo->prepare($sql);
    $requete->execute($params);

    return $requete;
}

//Fonction qui nous renvoie les infos d'un user à partir de son login
function getUserByLogin($login){
    $user = goSQL("SELECT * FROM users WHERE login=:login", array('login'=>$login));
    if ($user->rowCount() == 1) {
        return $user->fetch();
    }else{
        return false;
    }
}