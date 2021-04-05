<?php

require_once('inc/init.php');

//Gérer la déconnexion
if (isset($_GET['action']) && $_GET['action'] == 'deco') {
    
    //Je mets une date d'activité qui me fera sortir de la liste des utilisateurs actifs
    goSQL("UPDATE users SET date_activite='' WHERE id_user=:id_user", array(
        'id_user' => $_SESSION['user']['id_user']
    ));

    session_destroy(); //détruit la session(cela prend effet à la fin du script)
    header('location:'.URLSITE. 'connexion.php');
    exit();
    /*
        Quand j'arrive ici c'est que j'ai cliqué sur le lien
      connexion.php?action=deco
      grace à  $_GET, je récupère l'action, et je fais le necessaire pour détruire la session et rediriger vers la page de connexion

    */ 
}

if(isConnected()){
    header('location:'. URLSITE);
    exit();
}

if (!empty($_POST)) {
    $errors = array();

    if(empty($_POST['login'])){
        $errors[] = "Merci de saisir un login";
    }
    if(empty($_POST['password'])){
        $errors[] = "Merci de saisir votre mot de passe";
    }
    // expression régulière pour les caractères requis
    // $pattern = '#^(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,20}$#';

    // vérification des caractères requis
    // if(!preg_match($pattern, $_POST['password'])){
    //     $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une lettre et un chiffre.";
    // }



    if(empty($errors)){
        //tout est ok

        if ($user = getUserByLogin($_POST['login']) ) {
            // user trouvé en BDD
            if (password_verify($_POST['password'], $user['password']) ) {
                //mot de passe correct
                $_SESSION['user'] = $user; //Notre repère indiquant le statur de l'utilisateur
                goSQL("UPDATE users SET date_activite=NOW() WHERE id_user=:id_user",array(
                    'id_user' => $user['id_user']
                ));

                header('location:' . URLSITE);
                exit();
            }else{
                $errors[] = 'Erreur sur les identifiants 1'; //mauvais password
            }
            
        }else{
            $errors[] = 'Erreur sur les identifiants 2'; //mauvais login
        }
    }
}


$title = 'Connexion';
require_once('inc/header.php');
?>
<div class="row">
    <div class="col-md-5 mx-auto mt-5 text-light">
        <h2 class="text-center">Se connecter</h2>
        <?php   if(!empty($errors) ) : ?>
            <div class="alert alert-danger"><?php echo implode('<br>',$errors) ?></div>
        <?php   endif; ?>
        <form action="" method="post" class="my-3">
            <div class="form-group">
                <input type="text" name="login" class="form-control" placeholder="Saisir le login" value="<?php echo $_POST['login'] ?? '' ?>">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Saisir le mot de passe">
            </div>
            
            <button type="submit" class="btn btn-primary d-block mx-auto">Se connecter</button>
        </form>
    </div>
</div>

<?php
require_once('inc/footer.php');
