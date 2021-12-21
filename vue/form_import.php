<?php require 'includes/header.php'?>

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
        <?php endif ?>
    </div>

    <div class="container">
        <h2 class="text-center">Module d'importation des données</h2>
    </div>


    <div class="container">
        <h3 class="text-center">Importer des utilisateurs</h3>
        <div class="col-md-8 col-md-offset-2">

            <form method="POST" action="import_users.php" enctype="multipart/form-data">
                <!-- COMPONENT START -->
                <div class="form-group">
                    <div class="input-group input-file" name="Fichier">
                        <input type="text" class="form-control" placeholder='Choisir le fichier des utilisateurs' />
            <span class="input-group-btn">
                <button class="btn btn-default btn-choose" type="button">Choisir un fichier</button>
            </span>


                    </div>
                </div>
                <!-- COMPONENT END -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">Lancer le traitement</button>
                    <button type="reset" class="btn btn-danger">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!is_null($_SESSION['msg_err_users'])) {


        if ($_SESSION['msg_err_users']!='OK') { ?>


            <div class="container">
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <span class="sr-only">Error:</span><?php echo $_SESSION['msg_err_users'];?>
                    </div>
                </div>
            </div>


        <?php } else  { ?>

            <div class="container">
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-success" role="alert">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        Tous les utilisateurs ont été importés dans la plateforme
                    </div>
                </div>
            </div>


        <?php }
    }?>

    <div class="container">
        <h3 class="text-center">Importer des données lots</h3>
        <div class="col-md-8 col-md-offset-2">

            <form method="POST" action="import_lots.php" enctype="multipart/form-data">
                <!-- COMPONENT START -->
                <div class="form-group">
                    <div class="input-group input-file" name="Fichier">
                        <input type="text" class="form-control" placeholder='Choisir le fichier des lots' />
            <span class="input-group-btn">
                <button class="btn btn-default btn-choose" type="button">Choisir un fichier</button>
            </span>


                    </div>
                </div>
                <!-- COMPONENT END -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">Lancer le traitement</button>
                    <button type="reset" class="btn btn-danger">Annuler</button>
                </div>
            </form>
        </div>
    </div>


    <?php if (!is_null($_SESSION['msg_err_lot'])) {


    if ($_SESSION['msg_err_lot']!='OK') { ?>



        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span><?php echo $_SESSION['msg_err_lot'];?>
                </div>
            </div>
        </div>


    <?php } else { ?>

        <div class="container">
            <div class="col-md-8 col-md-offset-2">
                <div class="alert alert-success" role="alert">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    Tous les lots ont été importés dans la plateforme
                </div>
            </div>
        </div>


    <?php } 
    }?>


    <div class="container">
        <h3 class="text-center">Importer des réserves</h3>
        <div class="col-md-8 col-md-offset-2">

            <form method="POST" action="import_reserves.php" enctype="multipart/form-data">
                <!-- COMPONENT START -->
                <div class="form-group">
                    <div class="input-group input-file" name="Fichier">
                        <input type="text" class="form-control" placeholder='Choisir le fichier des réserves' />
            <span class="input-group-btn">
                <button class="btn btn-default btn-choose" type="button">Choisir un fichier</button>
            </span>


                    </div>
                </div>
                <!-- COMPONENT END -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary pull-right">Lancer le traitement</button>
                    <button type="reset" class="btn btn-danger">Annuler</button>
                </div>
            </form>
        </div>
    </div>


    <?php if (!is_null($_SESSION['msg_err_reserve'])) {

        if ($_SESSION['msg_err_reserve']!='OK') { ?>

            <div class="container">
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-danger" role="alert">
                        <span class="sr-only">Error:</span><?php echo $_SESSION['msg_err_reserve'];?>
                    </div>
                </div>
            </div>

        <?php } else { ?>

            <div class="container">
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-success" role="alert">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        Toutes les réserves ont été importées pour le projet
                    </div>
                </div>
            </div>


        <?php  }
        }?>



    <div class="container">
        <h3 class="text-center">Effacer les données du projet :</h3>
        <div class="col-md-8 col-md-offset-2">

            <form method="POST" action="supp_donnees.php">

                <div class="form-group">
                    <input type="texte" maxlength="75" data-validation="required" class="form-control" id="description" name="description" placeholder=""
                           value="<?php echo isset($_POST['description'])?$_POST['description']:stripslashes($projet_update->p_description);?>">
                </div>
                
                <!-- COMPONENT END -->
                <button type="submit" class="btn btn-primary">Effacer</button>
            </form>
        </div>
    </div>



        <?php if ($_GET['msg_supp_donnees']!='OK') {

            if (!is_null($_GET['msg_supp_donnees'])) { ?>

                <div class="container">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="alert alert-danger" role="alert">
                            <span class="sr-only">Error:</span><?php echo $_GET['msg_supp_donnees'];?>
                        </div>
                    </div>
                </div>


            <?php } ?>

        <?php } else { ?>

            <div class="container">
                <div class="col-md-8 col-md-offset-2">
                    <div class="alert alert-success" role="alert">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        Toutes les données du projet ont été supprimées
                    </div>
                </div>
            </div>


        <?php }?>


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>


    <script type="text/javascript" language="javascript" >
        function bs_input_file() {
            $(".input-file").before(
                function() {
                    if ( ! $(this).prev().hasClass('input-ghost') ) {
                        var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                        element.attr("name",$(this).attr("name"));
                        element.change(function(){
                            element.next(element).find('input').val((element.val()).split('\\').pop());
                        });
                        $(this).find("button.btn-choose").click(function(){
                            element.click();
                        });
                        $(this).find("button.btn-reset").click(function(){
                            element.val(null);
                            $(this).parents(".input-file").find('input').val('');
                        });
                        $(this).find('input').css("cursor","pointer");
                        $(this).find('input').mousedown(function() {
                            $(this).parents('.input-file').prev().click();
                            return false;
                        });
                        return element;
                    }
                }
            );
        }
        $(function() {
            bs_input_file();
        });





    </script>


<?php require 'includes/footer.php';?>