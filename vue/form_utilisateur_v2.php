<?php require 'includes/header.php' ?>

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

        <form id="AddUtilisateur" action="aj_utilisateur.php?id=<?php echo $_GET['id']?>" class="form-horizontal" method="post" id="signup" data-toggle="validator">

            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Rôle * :</label>
                <div class="col-sm-6">
                    <?php if ($utilisateur->u_role==1) {?>
                        <select name="droit">
                            <option value="1" >Administrateur</option>
                        </select>

                    <?}else{ ?>

                        <select name="droit"  data-validation="required" onchange="whatrole(this)" id="droit">
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
                    <?php } ?>
                </div>
            </div>

            
            <div class="form-group">
                <label class="control-label col-sm-2" for="prenom">Prénom :</label>
                <div class="col-sm-6">
                    <input type="texte" class="form-control" id="prenom" name="prenom" placeholder=""
                           value="<?php echo isset($_POST['prenom'])?$_POST['prenom']:utf8_decode(stripslashes($utilisateur->u_prenom));?>">
                </div>
            </div>


            <?php if (isset($_GET['id'])){?>
                <?php if ($utilisateur->u_role == 6) { ?>

                    <div class="form-group aff-nomentreprise" id="champNom">
                        <label class="control-label col-sm-2" for="nom">Nom :</label>
                        <div class="col-sm-6">
                            <input  type="texte" class="form-control" id="nom" name="nom" placeholder=""
                                   value="<?php echo isset($_POST['nom'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_nom));?>">
                        </div>
                    </div>

                    <div class="form-group aff-nomentreprise" id="champEntreprise">
                        <label class="control-label col-sm-2" for="nom">Entreprise *:</label>
                        <div class="col-sm-6">
                            <input data-validation="required" type="texte" class="form-control" id="entreprise" name="entreprise"
                                   value="<?php echo isset($_POST['entreprise'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_entreprise));?>">
                        </div>
                    </div>

                <?php } else { ?>

                    <div class="form-group aff-nomentreprise" id="champNom">
                        <label class="control-label col-sm-2" for="nom">Nom *:</label>
                        <div class="col-sm-6">
                            <input data-validation="required" type="texte" class="form-control" id="nom" name="nom" placeholder=""
                                   value="<?php echo isset($_POST['nom'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_nom));?>">
                        </div>
                    </div>

                    <div class="form-group aff-nomentreprise" id="champEntreprise">
                        <label class="control-label col-sm-2" for="nom">Entreprise :</label>
                        <div class="col-sm-6">
                            <input type="texte" class="form-control" id="entreprise" name="entreprise"
                                   value="<?php echo isset($_POST['entreprise'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_entreprise));?>">
                        </div>
                    </div>


                <?php }  ?>

            <?php }  else  { ?>

                <div class="form-group aff-nomentreprise" id="champNom">
                    <label class="control-label col-sm-2" for="nom">Nom *:</label>
                    <div class="col-sm-6">
                        <input data-validation="required" type="texte" class="form-control" id="nom" name="nom" placeholder=""
                               value="<?php echo isset($_POST['nom'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_nom));?>">
                    </div>
                </div>

                <div class="form-group aff-nomentreprise" id="champEntreprise">
                    <label class="control-label col-sm-2" for="nom">Entreprise :</label>
                    <div class="col-sm-6">
                        <input type="texte" class="form-control" id="entreprise" name="entreprise"
                               value="<?php echo isset($_POST['entreprise'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_entreprise));?>">
                    </div>
                </div>



            <?php } ?>

            
            <div class="form-group aff-identifiant" id="identifiantV2"></div>

            <?php if (isset($_GET['id'])){?>

                <?php if ($utilisateur->u_role != 5 && $utilisateur->u_role != 7) { ?>

                    <div class="form-group">
                        <label class="control-label col-sm-2" for="identifiant">Identifiant * :</label>
                        <div class="col-sm-6">
                            <input data-validation="required" type="texte" class="form-control" id="identifiant" name="identifiant" placeholder=""
                                   value="<?php echo isset($_POST['identifiant'])?$_POST['identifiant']:stripslashes($utilisateur->u_identifiant);?>">
                        </div>
                    </div>
                <?php } ?>


            <?php } ?>


            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Email :</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" id="email" name="email" placeholder=""
                           value="<?php echo isset($_POST['email'])?$_POST['email']:stripslashes($utilisateur->u_email);?>">
                </div>
                <?php if (!empty($erreur_email)): ?>
                    <span class="label label-danger"><?php echo $erreur_email;?></span>
                <?php endif ?>

            </div>


            <div class="form-group">
                <label class="control-label col-sm-2" for="adresse">Adresse :</label>
                <div class="col-sm-6">
                    <input type="texte" class="form-control" id="adresse" name="adresse" placeholder=""
                           value="<?php echo isset($_POST['adresse'])?$_POST['adresse']:utf8_decode(stripslashes($utilisateur->u_adresse));?>">
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-sm-2" for="ville">Ville :</label>
                <div class="col-sm-6">
                    <input type="texte" class="form-control" id="ville" name="ville" placeholder=""
                           value="<?php echo isset($_POST['ville'])?$_POST['ville']:utf8_decode(stripslashes($utilisateur->u_ville));?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="cp">Code Postal :</label>
                <div class="col-sm-6">
                    <input type="texte" class="form-control" id="cp" name="cp" placeholder=""
                           value="<?php echo isset($_POST['cp'])?$_POST['cp']:stripslashes($utilisateur->u_cp);?>">
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-sm-2" for="portable-1">N° de portable Principal* :</label>
                <div class="col-sm-6">
                    <input data-validation="required" type="texte" class="form-control" id="portable_1" name="portable_1" placeholder=""
                           value="<?php echo isset($_POST['portable_1'])?$_POST['portable_1']:stripslashes($utilisateur->u_portable_1);?>">
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-sm-2" for="telephone">N° de telephone fixe :</label>
                <div class="col-sm-6">
                    <input type="texte" class="form-control" id="telephone" name="telephone" placeholder=""
                           value="<?php echo isset($_POST['telephone'])?$_POST['telephone']:stripslashes($utilisateur->u_telephone);?>">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2" for="portable-2">N° de portable Secondaire:</label>
                <div class="col-sm-6">
                    <input type="texte" class="form-control" id="portable_2" name="portable_2" placeholder=""
                           value="<?php echo isset($_POST['portable_2'])?$_POST['portable_2']:stripslashes($utilisateur->u_portable_2);?>">
                </div>
            </div>


            <input type="hidden" name="email_old" value="<?php echo isset($_POST['email'])?$_POST['email']:stripslashes($utilisateur->u_email);?>">

            <input type="hidden" name="identifiant_old" value="<?php echo isset($_POST['identifiant'])?$_POST['identifiant']:stripslashes($utilisateur->u_identifiant);?>">

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-6 text-right">
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

            // Choix du role acquéreur et Autre contact
            if ((role.value == 4) || (role.value == 7) || (role.value == 5) ) {
                $('#champEntreprise').hide();
                $('#identifiantV2').html("<label class='control-label col-sm-2' for='identifiant'>Identifiant * :</label><div class='col-sm-6'><input data-validation='required' type='texte' class='form-control' id='identifiant' name='identifiant' value=''></div>");

            }

            // Choix du rôle Entreprise
            if (role.value == 6) {
                $('#champNom').html("<label class='control-label col-sm-2' for='identifiant'>Nom :</label><div class='col-sm-6'><input  type='texte' class='form-control' id='nom' name='nom'value='<?php echo isset($_POST['nom'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_nom));?>'></div>");
                $('#champEntreprise').html("<label class='control-label col-sm-2' for='identifiant'>Entreprise * :</label><div class='col-sm-6'><input data-validation='required' type='texte' class='form-control' id='entreprise' name='entreprise' value='<?php echo isset($_POST['entreprise'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_entreprise));?>'></div>");
                $(".aff-identifiant").hide();
            }


            /*
            if ((role.value == 5)) {

            }

            if ((role.value == 7) || (role.value == 5)) {
                $('#identifiantV2').html("");
                $(".aff-identifiant").hide();
            } else {
                $(".aff-identifiant").show();
                $('#identifiantV2').html("<label class='control-label col-sm-2' for='identifiant'>Identifiant * :</label><div class='col-sm-6'><input data-validation='required' type='texte' class='form-control' id='identifiant' name='identifiant' value=''></div>");
            }

            if (role.value == 6) {
                $('#champNom').html("<label class='control-label col-sm-2' for='identifiant'>Nom :</label><div class='col-sm-6'><input  type='texte' class='form-control' id='nom' name='nom'value='<?php echo isset($_POST['nom'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_nom));?>'></div>");
                $('#champEntreprise').html("<label class='control-label col-sm-2' for='identifiant'>Entreprise * :</label><div class='col-sm-6'><input data-validation='required' type='texte' class='form-control' id='entreprise' name='entreprise' value='<?php echo isset($_POST['entreprise'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_entreprise));?>'></div>");

            } else {
                $('#champNom').html("<label class='control-label col-sm-2' for='identifiant'>Nom *:</label><div class='col-sm-6'><input data-validation='required' type='texte' class='form-control' id='nom' name='nom'value='<?php echo isset($_POST['nom'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_nom));?>'></div>");
                $('#champEntreprise').html("<label class='control-label col-sm-2' for='identifiant'>Entreprise :</label><div class='col-sm-6'><input type='texte' class='form-control' id='entreprise' name='entreprise' value='<?php echo isset($_POST['entreprise'])?$_POST['nom']:utf8_decode(stripslashes($utilisateur->u_entreprise));?>'></div>");
            }
            */

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