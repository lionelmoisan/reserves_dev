<?php require 'includes/includes_back.php';?>

<?php 

/*---- CALCUL DU NBR TOTAL DE RESERVE LIVRAISON  ----*/

$sql_nbr_total_res_liv="SELECT count(*) as nbre FROM gr_reserves,gr_lots WHERE l_id=r_id_lot AND r_type='livraison' and l_id_projet=".$_SESSION['id_projet']."";

$req_nbr_total_res_liv=$DB->tquery($sql_nbr_total_res_liv);

$nbr_res_liv_total=$req_nbr_total_res_liv[0]['nbre'];

/* --- Recherche de la date de 1er livraison pour le projet ---*/
$infoslot=Lot::Getlotdatefirst($DB,$_SESSION['id_projet']);

$date_jour = new DateTime();

$date_livraison = new DateTime($infoslot[0]['date_livraison']);

$interval = $date_livraison->diff($date_jour);
$nbrjour= $interval->format('%a');

// CALUL DE l'ECHELLE
/*
switch ($nbrjour) {
    case ($nbrjour < 15):
        $echelleX='8';
        break;
    case ($nbrjour < 30):
        $echelleX='8';
        break;
    case ($nbrjour < 45):
        $echelleX='8';
        break;
    case ($nbrjour < 90):
        $echelleX='8';
        break;
    case ($nbrjour < 180):
        $echelleX='8';
        break;
    case ($nbrjour < 360):
        $echelleX='8';
        break;
    case ($nbrjour > 360):
        $echelleX='60';
        break;
}
*/


?>

<?php require 'includes/header.php';?>

<div class="row">
    <div class="col-lg-12">
        <h2>Statistique du projet :<?php echo $_SESSION['nom_projet'];?></h2>
        </div>
</div>

<div class="container">
    <div class="col-md-9">
        <div class="row">
                <p>Date de livraison : <?php echo $date_livraison->format('d-m-Y');;?></p>
        </div>

<?php
        if ($date_jour >= $date_livraison){
        ?>

            <div class="col-md-9">
                <h4>Courbe d'évolution en % du nombre de réserve de type Livraison levé</h4>
            </div>

        <?php } else {
         ?>
            <div class="col-md-9">
                <h4>La date de livraison du premier lot est supérieure à la date du jour </h4>
            </div>
<?
        }
?>

    </div>

</div>


    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
    <script src="js/morris.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.js"></script>

    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css">
    <link rel="stylesheet" href="css/morris.css">

    <div id="myfirstchart" style="height: 250px;"></div>


    <script type="text/javascript">
        $(function () {
            $.ajax({
                url: 'stat_courbe_ajax.php',
                dataType : 'JSON',
                type:'GET',
                data: {get_values: true},
                success: function (response) {
                    Morris.Line({
                        element: 'myfirstchart',
                        data:response,
                        xkey: 'period',
                        ykeys: ['nbr_res_leve'],
                        xLabelFormat: function(period) {
                            return period.getDate()+'/'+(period.getMonth()+1)+'/'+period.getFullYear();
                        },
                        yLabels:'day',
                        labels: ['% de réserve levée'],
                        resize: true,
                        dateFormat: function (ts) {
                            var d = new Date(ts);
                            return d.getDay() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();

                        }
                    });
                    
                }
            });
        });

    </script>


<?php require 'includes/footer.php';?>