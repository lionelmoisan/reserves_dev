<?php require 'includes/includes_back.php';?>

<?php

/*---- CALCUL DU NBR DE RESERVE LIVRAISON <> statut non levée et de réserve supprimée  ----*/

$sql_nbr_res_liv_nonlevee="SELECT count(*) as nbre FROM gr_reserves,gr_lots WHERE l_id=r_id_lot AND (r_ls_id <> 1 AND r_ls_id <> 7) and r_type='livraison' and l_id_projet=".$_SESSION['id_projet']."";

$req_nbr_res_liv_nonlevee=$DB->tquery($sql_nbr_res_liv_nonlevee);

$nbr_res_liv_nonlevee=$req_nbr_res_liv_nonlevee[0]['nbre'];

//var_dump("test ".$nbr_res_liv_nonlevee);


/*---- CALCUL DU NBR TOTAL DE RESERVE LIVRAISON  ----*/

$sql_nbr_total_res_liv="SELECT count(*) as nbre FROM gr_reserves,gr_lots WHERE l_id=r_id_lot AND r_ls_id <> 7 AND r_type='livraison' and l_id_projet=".$_SESSION['id_projet']."";

$req_nbr_total_res_liv=$DB->tquery($sql_nbr_total_res_liv);

$nbr_res_liv_total=$req_nbr_total_res_liv[0]['nbre'];

//var_dump($nbr_res_liv_total);


if ($nbr_res_liv_nonlevee == 0) {
   $pour_res_liv_nonlevee=0;
} elseif ($nbr_res_liv_nonlevee < $nbr_res_liv_total) {
    $pour_res_liv_nonlevee=round((($nbr_res_liv_nonlevee*100)/$nbr_res_liv_total));
} elseif ($nbr_res_liv_nonlevee == $nbr_res_liv_total) {
   $pour_res_liv_nonlevee=100;
}


/*---- CALCUL DU NBR DE RESERVE GPA <> statut non levée et <> réserve supprimée  ----*/
$sql_nbr_res_gpa_nonlevee="SELECT count(*) as nbre FROM gr_reserves,gr_lots WHERE l_id=r_id_lot  AND (r_ls_id <> 1 AND r_ls_id <> 7)  and r_type='GPA' and l_id_projet=".$_SESSION['id_projet']."";

$req_nbr_res_gpa_nonlevee=$DB->tquery($sql_nbr_res_gpa_nonlevee);

$nbr_res_gpa_nonlevee=$req_nbr_res_gpa_nonlevee[0]['nbre'];


/*---- CALCUL DU NBR DE TOTAL DE RESERVE GPA  ----*/
$sql_nbr_total_res_gpa="SELECT count(*) as nbre FROM gr_reserves,gr_lots WHERE l_id=r_id_lot AND r_ls_id <> 7 AND r_type='GPA' and l_id_projet=".$_SESSION['id_projet']."";

$req_nbr_res_gpa_total=$DB->tquery($sql_nbr_total_res_gpa);

$nbr_res_gpa_total=$req_nbr_res_gpa_total[0]['nbre'];

// CALCUL PERMETTANT L'AFFICHAGE DES BARRE D'AVANCEMENT
if ($nbr_res_gpa_nonlevee == 0) {
    $pour_res_gpa_nonlevee=0;
} elseif ($nbr_res_gpa_nonlevee < $nbr_res_gpa_total) {
    $pour_res_gpa_nonlevee=round((($nbr_res_gpa_nonlevee*100)/$nbr_res_gpa_total));
} elseif ($nbr_res_gpa_nonlevee == $nbr_res_gpa_total) {
    $pour_res_gpa_nonlevee=100;
}

?>

<?php require 'includes/header.php';?>

<div class="row">
    <div class="col-lg-12">
    <h2>Statistiques du projet : <?php echo $_SESSION['nom_projet'];?></h2>
        </div>
</div>

<div class="container">
    <div class="col-md-6">
        <label>Réserves de livraison levées</label>
        <div class="progress">
            <div id="livraison" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:<?php echo $pour_res_liv_nonlevee.'%';?>"><?php echo $pour_res_liv_nonlevee.'%';?></div>
        </div>
    </div>


    <div class="col-md-6">
        <label>GPA levées</label>
        <div class="progress">
            <div id="gpa" class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:<?php echo $pour_res_gpa_nonlevee.'%';?>"><?php echo $pour_res_gpa_nonlevee.'%';?></div>
        </div>
    </div>

    <div class="col-md-12">
        <h4>Evolution du pourcentage de réserves de Livraison levées</h4>
        <div id="myfirstchart" style="height: 250px;"></div>
    </div>

    <div class="col-md-12">
            <h4>Répartition des réserves de livraison et GPA restantes</h4>
            <div id="graph" style="height: 450px;"></div>
    </div>



</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
    <script src="js/morris.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r224/prettify.min.css">
    <link rel="stylesheet" href="css/morris.css">



<?php

// NBR TOTAL DE RESERVE POUR LE PROJET
$sql_nbr_res_total="SELECT count(*) as nbre FROM gr_reserves,gr_lots WHERE l_id=r_id_lot AND (r_ls_id <> 7) and l_id_projet=".$_SESSION['id_projet']."";

$req_nbr_res_total=$DB->tquery($sql_nbr_res_total);

$nbr_res_total=$req_nbr_res_total[0]['nbre'];


$data=array(
    'id_statut'=>1,
    'id_projet'=>$_SESSION['id_projet']
);

$lst_nbr_resbyentre=Statistiques::cpt_res_ent($DB,$data);

$data = array();
foreach ($lst_nbr_resbyentre as $entreprise) {

    $info_utilisateur=Utilisateur::getUtilisateurById($DB,$entreprise['r_id_entreprise']);

    $nestedData=array();
    // Ajout d'un retour chariot dans le nom des entreprises

    $nbr_de_mots=strlen($info_utilisateur[0]->u_entreprise);

    if ($nbr_de_mots > 12) {

        $chaine = explode(" ",  $info_utilisateur[0]->u_entreprise);

        foreach ($chaine as $value) {
            $nom_entreprise.=$value."\n";
        }

        unset($chaine);

    } else {

     $nom_entreprise=$info_utilisateur[0]->u_entreprise;

    }

    $nestedData['entreprise'] = utf8_decode($nom_entreprise);
    //$nestedData['pourcentage'] = round(($entreprise['nbr']*100)/$nbr_res_total,2);

    $nestedData['pourcentage'] = $entreprise['nbr'];

    $data[] = $nestedData;

    unset($nom_entreprise);
}

$data_json=json_encode($data);
?>


<script>

    $(function() {
        $("#livraison").addClass("progress-bar-livraison");
        $("#gpa").addClass("progress-bar-gpa");
    });


</script>


    <script type="text/javascript">
    Morris.Bar({
        element: 'graph',
        data: <?php echo $data_json?>,
        xkey: 'entreprise',
        ykeys: ['pourcentage'],
        labels: ['Nombre de réserves non levées'],
        resize: true,
        xLabelAngle: 60
    }).on('click', function(i, row){
        console.log(i, row);
    });

</script>


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
                        ymax : 100,
                        labels: ['% de réserve levée'],
                        resize: true,

                        dateFormat: function (ts) {
                            var d = new Date(ts);

                            var months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
                            var annee = d.getFullYear();
                            var mois = months[d.getMonth()];

                            var date_fr= d.getDate();

                            var time_fr = date_fr + '/' + mois + '/' + annee ;

                            return time_fr;

                            //return d.getDay() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();

                        }
                    });

                }
            });
        });

    </script>


<?php require 'includes/footer_rub.php';?>