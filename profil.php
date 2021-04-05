<?php

require_once('inc/init.php');
//si je ne suis pas connecté, je suis invité à le faire en étant redirigé vers la page de connnexion
if(!isConnected()){
    header('location:'. URLSITE . 'connexion.php');
    exit();
}


require_once('inc/header.php');



require_once('inc/footer.php');