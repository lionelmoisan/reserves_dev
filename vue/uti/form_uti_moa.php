<?php require 'includes/header.php' ?>

    <!-- Gestion des messages -->
    <div>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info" role="alert"><?php echo $_SESSION['message'];?></div>
            <?php unset($_SESSION['message']) ?>
        <?php endif ?>


        <?php if (isset($_SESSION['erreur'])): ?>
            <span class="label label-danger"></span>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span><?php echo $_SESSION['erreur'];?>
            </div>
            <?php unset($_SESSION['erreur'])?>
        <?php endif ?>
    </div>
    <div class="container">
        <h2 class="text-center"><?php echo $titre_rubrique?></h2>

        <form id="AddUtilisateur" action="up_utilisateur.php?id=<?php echo $_GET['id']?>" class="form-horizontal" method="post" data-toggle="validator">

            <div class="form-group">
                <label class="control-label col-sm-3" for="pwd">Rôle * :</label>
                <div class="col-sm-4">
                    <?php
                    $role=Role::getRoleDes($DB,$utilisateur->u_role);
                    ?>
                    <input type="texte" class="form-control" id="role" name="role" <?php echo $modif_champ?> value="<?php echo $role["r_description"]?>">

                </div>
            </div>

            <!-- Input Prénom -->
            <div class="form-group">
                <label class="control-label col-sm-3" for="prenom">Prénom :</label>
                <div class="col-sm-4">
                    <input type="texte" class="form-control" id="prenom" name="prenom"
                           value="<?php echo isset($_POST['prenom'])?$_POST['prenom']:utf8_decode(stripslashes($utilisateur->u_prenom));?>">
                </div>
            </div>

            <!-- Input Nom * -->
            <div class="form-group" id="nom">
                <label class="control-label col-sm-3" for="nom">Nom *:</label>
                <div class="col-sm-4">
                    <input data-validation="required" type="texte" class="form-control" id="nom" name="nom"
                           value="<?php echo isset($_POST['nom'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_nom));?>">
                </div>
            </div>

            <!-- societe * -->
            <div class="form-group aff-societe" id="societe">
                <label class="control-label col-sm-3" for="societe">Société *:</label>
                <div class="col-sm-4">
                    <input data-validation="required" type="texte" class="form-control" id="societe" name="societe"
                           value="<?php echo isset($_POST['societe'])?$_POST['societe']:utf8_decode(stripslashes($utilisateur->u_societe));?>">
                </div>
            </div>

            <!-- Email * -->
            <div class="form-group" id="email">
                <label class="control-label col-sm-3" for="email">Email *:</label>
                <div class="col-sm-4">
                    <input data-validation="required" type="email" class="form-control" id="email" name="email"
                           value="<?php echo isset($_POST['email'])?$_POST['email']:stripslashes($utilisateur->u_email);?>">
                </div>
                <div class='col-sm-5 txt-info'><span class='info-bleu'>info</span> : Si vous modifiez l'adresse e-mail, cet intervenant recevra un nouveau mail de connexion et il lui sera proposé une nouvelle fois d'activer son compte.</div>
                <?php if (!empty($erreur_email)): ?>
                    <span class="label label-danger"><?php echo $erreur_email;?></span>
                <?php endif ?>

            </div>

            <!-- Input Adresse -->
            <div class="form-group">
                <label class="control-label col-sm-3" for="adresse">Adresse :</label>
                <div class="col-sm-4">
                    <input type="texte" class="form-control" id="adresse" name="adresse"
                           value="<?php echo isset($_POST['adresse'])?$_POST['adresse']:utf8_decode(stripslashes($utilisateur->u_adresse));?>">
                </div>
            </div>

            <!-- Input VILLE -->
            <div class="form-group">
                <label class="control-label col-sm-3" for="ville">Ville :</label>
                <div class="col-sm-4">
                    <input type="texte" class="form-control" id="ville" name="ville"
                           value="<?php echo isset($_POST['ville'])?$_POST['ville']:utf8_decode(stripslashes($utilisateur->u_ville));?>">
                </div>
            </div>

            <!-- Input CP -->
            <div class="form-group">
                <label class="control-label col-sm-3" for="cp">Code Postal :</label>
                <div class="col-sm-2">
                    <input type="texte" class="form-control" id="cp" name="cp"
                           value="<?php echo isset($_POST['cp'])?$_POST['cp']:stripslashes($utilisateur->u_cp);?>">
                </div>
            </div>

            <!-- Input Portable principal OBLIGATOIRE-->
            <div class="form-group">
                <label class="control-label col-sm-3" for="portable-1">N° de portable Principal* :</label>
                <div class="col-sm-2">
                    <input data-validation="required" type="texte" class="form-control" id="portable_1" name="portable_1"
                           value="<?php echo isset($_POST['portable_1'])?$_POST['portable_1']:stripslashes($utilisateur->u_portable_1);?>">
                </div>
            </div>

            <!-- Input Telephone fixe-->
            <div class="form-group">
                <label class="control-label col-sm-3" for="telephone">N° de telephone fixe :</label>
                <div class="col-sm-2">
                    <input type="texte" class="form-control" id="telephone" name="telephone"
                           value="<?php echo isset($_POST['telephone'])?$_POST['telephone']:stripslashes($utilisateur->u_telephone);?>">
                </div>
            </div>

            <!-- Input Portable secondaire-->
            <div class="form-group">
                <label class="control-label col-sm-3" for="portable-2">N° de portable secondaire:</label>
                <div class="col-sm-2">
                    <input type="texte" class="form-control" id="portable_2" name="portable_2"
                           value="<?php echo isset($_POST['portable_2'])?$_POST['portable_2']:stripslashes($utilisateur->u_portable_2);?>">
                </div>
            </div>

            <!-- Bouton Enregistrer/Annuler -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-5 text-right">
                    <a class="btn btn-default" href="lst_utilisateurs.php" role="button">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>

        </form>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.43/jquery.form-validator.min.js"></script>

    <script>
        /* ----   Validation du formulaire d'ajout de réserve ---*/

        var myLanguage = {
            requiredFields: 'Champ obligatoire',
            groupCheckedTooFewStart :"S'il vous plaît choisir au moins ",
            groupCheckedEnd :' projet(s)'
        };

        $.validate({
            language : myLanguage,
            form : '#AddUtilisateur',
            modules : 'security',
            onModulesLoaded : function() {
            }
        });

    </script>
<?php require 'includes/footer_rub.php';?>