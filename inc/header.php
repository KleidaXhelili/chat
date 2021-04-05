<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Ma feuille de style -->
    <link rel="stylesheet" href="<?php echo URLSITE ?>/css/style.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <title>Simple Tchat - <?php echo $title ?></title>
</head>

<body class="bg-dark">
    <header>
        <!-- Menu -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <!-- Titre-logo du site -->
            <a class="navbar-brand" href="<?php echo URLSITE ?>">SIMPLE TCHAT</a>
            <!-- Burger -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item <?php if($title=='Accueil') echo 'active';?> ">
                        <a class="nav-link" href="<?php echo URLSITE ?>">Accueil <span class="sr-only"></span></a>
                    </li>
                    <?php if (!isConnected()) : ?>
                    <li class="nav-item <?php if($title=='Inscription') echo 'active';?>">
                        <a class="nav-link" href="<?php echo URLSITE ?>inscription.php">Inscription</a>
                    </li>
                    <li class="nav-item <?php if($title=='Connexion') echo 'active';?>">
                        <a class="nav-link" href="<?php echo URLSITE ?>connexion.php">Connexion</a>
                    </li>
                    <?php else : ?>
                        <li class="nav-item <?php if($title=='Profil') echo 'active';?>">
                            <a class="nav-link" href="<?php echo URLSITE ?>profil.php"><?php echo $_SESSION['user']['login'] ?>
</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URLSITE ?>connexion.php?action=deco">Déconnexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <main class="container">