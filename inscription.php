<?php

require_once('inc/init.php');

if(isConnected()){
    header('location:'. URLSITE);
    exit();
}
//Traitement PHP
if (!empty($_POST)) { //si j'ai soumis le formulaire
    // var_dump($_POST);
    // var_dump($_FILES); // rôle : récupérer les infos de type file

    $errors = array();

    if (empty($_POST['login'])) {
        $errors[] = 'Merci de saisir un login';
    }else{
        if (getUserByLogin($_POST['login'])) {
            $errors[] = "Login est déjà utilisé, merci d'en choisir un autre";
        }
    }
    if (empty($_POST['password'])) {
        $errors[] = 'Merci de saisir un mot de passe';
    }else {
        /*
            (?=.*[a-z]) impose une minuscule
            (?=.*[A-Z]) impose une majuscule
            (?=.*[0-9]) impose un chiffre
            (?=.*[\!\$\-\_\@]) impose un caractère spécial
            [\w] minuscule, majuscule, chiffre + undescore
            {8,20} Nb de caractères compris entre 8 et 20
        */
        $pattern = '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[\w]{8,20}$
        #';

        if ( !preg_match($pattern, $_POST['password']) ) {
            $errors[] = 'Le mot de passe doit contenir 8 à 20 caractères comprenant au moins 1 minuscule, 1 majuscule et 1 chiffre';
        }
    }
    if (empty($_POST['email'])) {
        $errors[] = 'Merci de saisir une adresse mail';
    }else{
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
            $errors[] = 'Adresse mail incorrecte';
        }
    }
    if (empty($_FILES['avatar']['name'])) {
        $errors[] = 'Merci de choisir un avatar';
    }

    // Si j'ai pas d'erreur
    if(empty($errors)){
        //Aucune erreur rencontrée jusque là

        //Contrôle du type de fichier
        $mime_autorises = ['image/jpeg', 'image/png'];
        if (!in_array($_FILES['avatar']['type'], $mime_autorises)) {
            $errors[] = 'Format de fichier incorrect. JPEG ou PNG uniquement';
        }else{
            //Copie physique du fichier dans le répertoire des avatars
            $nomfichier = $_POST['login'] . '_'. $_FILES['avatar']['name'];
            $chemin = $_SERVER['DOCUMENT_ROOT']. URLSITE . 'avatars/';
            // var_dump($_SERVER);
            // var_dump($chemin);
            move_uploaded_file($_FILES['avatar']['tmp_name'],$chemin.$nomfichier);

            //Insertion de l'utilisateur en base de données
            goSQL("INSERT INTO users VALUES (NULL, :login, :password, :email, :avatar, NOW())", array(
                'login' => $_POST['login'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'email' => $_POST['email'],
                'avatar' => $nomfichier
            ));
            //Redirection vers la connexion - Auto-connexion
            header('location:'. URLSITE . 'connexion.php');
            exit(); //stopper le script PHP

        }

    }
}

//Traitements PHP
$title = 'Inscription';
require_once('inc/header.php');
?>
<div class="row">
    <div class="col-md-5 mx-auto mt-5 text-light">
        <h2 class="text-center">S'inscrire au tchat</h2>
        <?php   if(!empty($errors) ) : ?>
            <div class="alert alert-danger"><?php echo implode('<br>',$errors) ?></div>
        <?php   endif; ?>
        <form action="" method="post" enctype="multipart/form-data" class="my-3">
            <div class="form-group">
                <input type="text" name="login" class="form-control" placeholder="Choisir un login" value="<?php echo $_POST['login'] ?? '' ?>">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Choisir un mot de passe">
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="email@perso.com" value="<?php echo $_POST['email'] ?? '' ?>">                
            </div>
            <div class="form-group text-center">
                <input type="file" id="avatar" name="avatar" class="d-none" accept="image/jpeg, image/png">
                <label for="avatar">
                    <img id="preview" alt="avatar" src="https://dummyimage.com/300x300&text=Choisir un avatar" class="img-fluid">
                </label>
            </div>
            <button type="submit" class="btn btn-primary d-block mx-auto">S'inscrire</button>
        </form>
    </div>
</div>
<?php
require_once('inc/footer.php');
