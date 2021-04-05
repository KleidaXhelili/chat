<?php

require_once('inc/init.php');

//Traitements PHP
$title = 'Accueil';
require_once('inc/header.php');
?>
<div class="row"></div>
    <div class="col text-light my-5">
        <h1 class="text-center">Fred's tchat</h1>
        <hr>
        <?php if(isConnected()) : ?>
        <!-- fenêtre du chat -->
        <div class="row">
            <div class="col-md-9 border border-light p-3" id="conversation"></div>
            <div class="col-md-3 border border-light p-3" id="users"></div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <form method="post" id="formulaire">
                    <div class="form-group">
                        <input type="text" id="phrase" class="form-control">
                    </div>
                </form>
            </div>            
        </div>
        <?php else : ?>
            <p>Pour accéder à ce tchat, vous devez être connectés. Merci de vous <a href="<?php echo URLSITE ?>inscription.php">inscrire</a> ou vous <a href="<?php echo URLSITE ?>connexion.php">connecter</a> si vous avez déjà un compte</p>
        <?php endif; ?>
    </div>
</div>
<?php
require_once('inc/footer.php');