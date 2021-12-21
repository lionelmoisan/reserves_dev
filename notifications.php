<?php require 'includes/includes_back.php';?>
<?php require_once 'Classes/MessageAuto.php';


// Message pour l'administrateur / et l'admin
if (($_SESSION['role']==1) || ($_SESSION['role']==8)) {

    if (!isset($_SESSION['message'])) {
        $_SESSION['message']="<span class='info-bleu'>info</span> : <br>Réserves levées : email envoyé à l'acquéreur pour l'informer de la levée de réserves ou de GPA.<br>".
            "Nouvelle GPA :  email envoyé à l'acquéreur pour l'informer de la prise en compte d'une nouvelle demande de GPA.<br>".
            "Relance Entreprise : email envoyé à l'entreprise pour rappel des réserves et GPA restantes à lever.<br>".
            "Refus GPA :  email envoyé à l'acquéreur pour l'informer qu'une demande de GPA ne peut-etre prise en charge.<br>".
            "";
    }
}


$nbr_notification = MessageAuto::getnbrnotification($DB,$_SESSION['id_projet'],$_SESSION['role']);

//var_dump($nbr_notification);

if ($nbr_notification != 0) {

    $notparpage = 1000;
    $nbrdepage = ceil($nbr_notification / $notparpage);


    if (isset($_GET['p']) && ($_GET['p']>0) &&  $_GET['p']<=$nbrdepage ) {
        $cPage=$_GET['p'];
    } else {
        $cPage=1;
    }

    $lst_notifications=MessageAuto::getnotification($DB,$cPage,$notparpage,$_SESSION['id_projet'],$_SESSION['role']);

}

//var_dump($nbr_notification);

?>

<?php require 'includes/header.php';?>

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
    <?php unset($_SESSION['erreur']) ?>
<?php endif ?> 


<div class="container">
    <h2 class="text-center">Historique des notifications et relances</h2>

    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <div class="input-group"><span class="input-group-addon">Recherche</span>
                <input id="filter" type="text" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8"></div>

    </div>
        <table class="table table-condensed">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sujet</th>
                <th>Prénom et nom</th>
                <th>Nom de l'entreprise</th>
                <th>Email</th>
            </tr>

            <?php if (isset($lst_notifications)) {
            ?>
            <?php foreach($lst_notifications as $notification) {?>
            <tbody class="searchable">
            <?php
                $date = Db::DecodeDate($notification['n_date']);

                /* -- Recherche les informations de l'acquéreur--*/
                $info_utilisateur=Utilisateur::getUtilisateurById($DB,$notification['n_id_acquereur']);
            ?>
            <tr>
                <td><?php echo $date?></td>
                <td><?php echo $notification['n_sujet']?></td>
                <td><?php echo utf8_decode(stripslashes($info_utilisateur[0]->u_prenom))." ".utf8_decode(stripslashes($info_utilisateur[0]->u_nom))?></td>
                <td><?php echo utf8_decode(stripslashes($info_utilisateur[0]->u_entreprise))?></td>
                <td><?php echo $info_utilisateur[0]->u_email?></td>
            </tr>
            </tbody>
          <?php } ?>



            <?php } else { ?>
                <tbody >
                <tr>
                    <td colspan="4">Pas de données </td>
                </tr>
                </tbody>

           <?php } ?>


        </thead>
      </table>

    <div class="row">
        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10"></div>
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <ul class="pagination">
<?php
        for($i=1;$i<=$nbrdepage;$i++) {
            if ($i == $cPage) {
                $active="class='active'";
        } else {
                $active="";
            }

            //echo "<li $active><a href=\"notifications.php?p=$i\">$i</a></li>";

        }
?>          </ul>
        </div>

    </div>

</div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            (function ($) {
                $('#filter').keyup(function () {
                    var rex = new RegExp($(this).val(), 'i');
                    $('.searchable tr').hide();
                    $('.searchable tr').filter(function () {
                        return rex.test($(this).text());
                    }).show();
                })
            }(jQuery));

        });
    </script>

<?php require 'includes/footer_rub.php';?>