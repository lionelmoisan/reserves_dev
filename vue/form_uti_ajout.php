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

    <form id="AddUtilisateur" action="aj_utilisateur.php" class="form-horizontal" method="post" data-toggle="validator">

        <div class="form-group">
            <label class="control-label col-sm-3" for="pwd">Rôle * :</label>
            <div class="col-sm-4">
                    <select name="role"  data-validation="required" onchange="whatrole(this)" id="role">
                        <option value="">Choisir un rôle</option>
                        <?php
                        /* Boucle pour la liste des roles  */
                        foreach ($lst_roles as $role) {
                            ?>
                            <option  value="<?php echo $role['r_id']?>" <?php if($role['r_id']==$utilisateur->u_role){ print 'selected';}?> ><?php echo stripslashes($role['r_description'])?></option>
                            <?
                        }
                        ?>
                    </select>
            </div>
        </div>

        <!-- Input Prénom -->
        <div class="form-group">
            <label class="control-label col-sm-3" for="prenom">Prénom :</label>
            <div class="col-sm-4">
                <input type="texte" class="form-control" id="prenom" name="prenom" placeholder=""
                       value="<?php echo isset($_POST['prenom'])?$_POST['prenom']:utf8_decode(stripslashes($utilisateur->u_prenom));?>">
            </div>
        </div>

        <!-- Input Nom * -->
        <div class="form-group" id="nom">
            <label class="control-label col-sm-3" for="nom">Nom *:</label>
            <div class="col-sm-4">
                <input data-validation="required" type="texte" class="form-control" id="nom" name="nom" placeholder=""
                       value="<?php echo isset($_POST['nom'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_nom));?>">
            </div>
        </div>

        <!-- Input Societe *-->
        <div class="form-group aff-societe" id="societe">
            <label class="control-label col-sm-3" for="nom">Société *:</label>
            <div class="col-sm-4">
                <input data-validation="required" type="texte" class="form-control" id="societe" name="societe"
                       value="<?php echo isset($_POST['societe'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_societe));?>">
            </div>
        </div>


        <!-- Input Entreprise *-->
        <div class="form-group aff-nomentreprise" id="entreprise" style="display: none">
            <label class="control-label col-sm-3" for="nom">Nom de l'entreprise *:</label>
            <div class="col-sm-4">
                <input data-validation="required" type="texte" class="form-control" id="entreprise" name="entreprise"
                       value="<?php echo isset($_POST['entreprise'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_entreprise));?>">
            </div>
        </div>

        <!-- Identifiant * -->
        <div class="form-group aff-identifiant" id="identifiant" style="display: none">
            <label class="control-label col-sm-3" for="identifiant">Identifiant * :</label>
            <div class="col-sm-4">
                <input data-validation="required" type="texte" class="form-control" id="identifiant" name="identifiant" placeholder=""
                       value="<?php echo isset($_POST['identifiant'])?$_POST['identifiant']:stripslashes($utilisateur->u_identifiant);?>">
            </div>
        </div>

        <!-- Email * -->
        <div class="form-group" id="email">
            <label class="control-label col-sm-3" for="email">Email *:</label>
            <div class="col-sm-4">
                <input data-validation="required" type="email" class="form-control" id="email" name="email" placeholder=""
                       value="<?php echo isset($_POST['email'])?$_POST['email']:stripslashes($utilisateur->u_email);?>">
            </div>
            <?php if (!empty($erreur_email)): ?>
                <span class="label label-danger"><?php echo $erreur_email;?></span>
            <?php endif ?>

        </div>


        <!-- Input Adresse -->
        <div class="form-group">
            <label class="control-label col-sm-3" for="adresse">Adresse :</label>
            <div class="col-sm-4">
                <input type="texte" class="form-control" id="adresse" name="adresse" placeholder=""
                       value="<?php echo isset($_POST['adresse'])?$_POST['adresse']:utf8_decode(stripslashes($utilisateur->u_adresse));?>">
            </div>
        </div>

        <!-- Input VILLE -->
        <div class="form-group">
            <label class="control-label col-sm-3" for="ville">Ville :</label>
            <div class="col-sm-4">
                <input type="texte" class="form-control" id="ville" name="ville" placeholder=""
                       value="<?php echo isset($_POST['ville'])?$_POST['ville']:utf8_decode(stripslashes($utilisateur->u_ville));?>">
            </div>
        </div>

        <!-- Input CP -->
        <div class="form-group">
            <label class="control-label col-sm-3" for="cp">Code Postal :</label>
            <div class="col-sm-2">
                <input type="texte" class="form-control" id="cp" name="cp" placeholder=""
                       value="<?php echo isset($_POST['cp'])?$_POST['cp']:stripslashes($utilisateur->u_cp);?>">
            </div>
        </div>

        <!-- Input Portable principal OBLIGATOIRE-->
        <div class="form-group">
            <label class="control-label col-sm-3" for="portable-1">N° de portable Principal* :</label>
            <div class="col-sm-2">
                <input data-validation="required" type="texte" class="form-control" id="portable_1" name="portable_1" placeholder=""
                       value="<?php echo isset($_POST['portable_1'])?$_POST['portable_1']:stripslashes($utilisateur->u_portable_1);?>">
            </div>
        </div>

        <!-- Input Telephone fixe-->
        <div class="form-group">
            <label class="control-label col-sm-3" for="telephone">N° de telephone fixe :</label>
            <div class="col-sm-2">
                <input type="texte" class="form-control" id="telephone" name="telephone" placeholder=""
                       value="<?php echo isset($_POST['telephone'])?$_POST['telephone']:stripslashes($utilisateur->u_telephone);?>">
            </div>
        </div>

        <!-- Input Portable secondaire-->
        <div class="form-group">
            <label class="control-label col-sm-3" for="portable-2">N° de portable secondaire:</label>
            <div class="col-sm-2">
                <input type="texte" class="form-control" id="portable_2" name="portable_2" placeholder=""
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

    function whatrole(role) {

        if (role.value == 1) {
            $('#entreprise').html("<label class='control-label col-sm-3' for='nom'>Nom entreprise *:</label><div class='col-sm-4'><input data-validation='required' type='texte' class='form-control' id='entreprise' name='entreprise' value=''></div>");

            $(".aff-nomentreprise").show();

        }


        // Choix du rôle Acquéreur,
        if (role.value == 4) {
            $('#nom').html("<label class='control-label col-sm-3' for='nom'>Nom *:</label> <div class='col-sm-4'> <input data-validation='required' type='texte' class='form-control' id='nom' name='nom' value=''></div>");

            $('#societe').html("<label class='control-label col-sm-3' for='societe'>Société :</label> <div class='col-sm-4'> <input type='texte' class='form-control' id='societe' name='societe' value=''></div>");

            $('#entreprise').html("");
            $(".aff-nomentreprise").hide();

            $('#identifiant').html("<label class='control-label col-sm-3' for='identifiant'>Identifiant *:</label><div class='col-sm-4'><input data-validation='required' type='texte' class='form-control' id='identifiant' name='identifiant' value=''></div><div class='col-sm-5 txt-info'><span class='info-bleu'>info</span> : Choississez un identifiant pour cet intervenant par exemple son adresse mail.</div>");
            $(".aff-identifiant").show();
            $('#email').html("<label class='control-label col-sm-3' for='email'>Email :</label><div class='col-sm-4'> <input  type='email' class='form-control' id='email' name='email' value=''></div><div class='col-sm-5 txt-info'><span class='info-bleu'>info</span> : Il est recommandé de renseigner l'e-mail si vous souhaitez que l'intervenant ai accès à la plate forme web et soit informé de manière automatique de la levée de ses réserves.</div>");

        }

        // autre intervenant et Locataire
        if ((role.value == 7) || (role.value == 5) ) {

            $('#nom').html("<label class='control-label col-sm-3' for='nom'>Nom *:</label> <div class='col-sm-4'> <input data-validation='required' type='texte' class='form-control' id='nom' name='nom' value=''></div>");

            $('#societe').html("<label class='control-label col-sm-3' for='societe'>Société :</label> <div class='col-sm-4'> <input type='texte' class='form-control' id='societe' name='societe' value=''></div>");

            $('#entreprise').html("");
            $(".aff-nomentreprise").hide();

            $('#identifiant').html("");
            $(".aff-identifiant").hide();

            $('#email').html("");

            $('#email').html("<label class='control-label col-sm-3' for='email'>Email :</label><div class='col-sm-4'> <input type='email' class='form-control' id='email' name='email' value=''></div>");

        }


        // Choix du rôle Admin, MOE et MOA
        if ((role.value == 8) || (role.value == 2) || (role.value == 3) ) {

            $('#entreprise').html("");
            $(".aff-nomentreprise").hide();
            $('#nom').html("<label class='control-label col-sm-3' for='nom'>Nom *:</label> <div class='col-sm-4'> <input data-validation='required' type='texte' class='form-control' id='nom' name='nom' value=''></div>");

            $('#societe').html("<label class='control-label col-sm-3' for='societe'>Société *:</label> <div class='col-sm-4'> <input data-validation='required' type='texte' class='form-control' id='societe' name='societe' value=''></div>");
            $(".aff-societe").show();

            $('#identifiant').html("");
            $(".aff-identifiant").hide();


            $('#email').html("<label class='control-label col-sm-3' for='email'>Email *:</label><div class='col-sm-4'> <input data-validation='required' type='email' class='form-control' id='email' name='email' value=''></div>");

        }

        // Choix du rôle Entreprise
        if (role.value == 6) {
            $('#nom').html("<label class='control-label col-sm-3' for='nom'>Nom :</label> <div class='col-sm-4'> <input type='texte' class='form-control' id='nom' name='nom' value=''></div>");

            $('#societe').html("");
            $(".aff-societe").hide();

            $('#entreprise').html("<label class='control-label col-sm-3' for='nom'>Nom entreprise *:</label><div class='col-sm-4'><input data-validation='required' type='texte' class='form-control' id='entreprise' name='entreprise' value=''></div>");

            $(".aff-nomentreprise").show();
            $('#identifiant').html("");
            $(".aff-identifiant").hide();

            $('#email').html("<label class='control-label col-sm-3' for='email'>Email *:</label><div class='col-sm-4'> <input data-validation='required' type='email' class='form-control' id='email' name='email' value=''></div>");
        }

    }
</script>

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
