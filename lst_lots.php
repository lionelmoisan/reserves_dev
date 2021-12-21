<?php require 'includes/includes_back.php';

require 'includes/header.php';

/*---- Recherche la liste des lots ----*/    
$lst_lots=lot::getLots($DB,$_SESSION['id_projet']);


$tab_lots = array();
$i=0;

/*--- Traitement du données ----*/
foreach ($lst_lots as $lot) {
    
    $tab_lots[$i]['id']=$lot['l_id'];
    $tab_lots[$i]['lot']=$lot['l_numero_lot'];
    $tab_lots[$i]['date_livraison']=$lot['l_date_livraison'];
    $tab_lots[$i]['date_reception']=$lot['l_date_reception'];
    
    /* -- Recherche les informations de l'acquéreur--*/
    $Info_utilisateur=Utilisateur::getUtilisateurById($DB,$lot['l_id_acquereur']);

    $tab_lots[$i]['acquereur']= $Info_utilisateur[0]->u_prenom." " .$Info_utilisateur[0]->u_nom;
    
     /* -- Recherche les informations de lu locataire--*/
    $Info_utilisateur=Utilisateur::getUtilisateurById($DB,$lot['l_id_locataire']);

    $tab_lots[$i]['locataire']= $Info_utilisateur[0]->u_prenom." " .$Info_utilisateur[0]->u_nom; 


    if ($lot['l_id_contact']==0) {
        $tab_lots[$i]['contact']=NULL;

    } else {

        /* -- Recherche les informations pour le contact--*/
        $Info_utilisateur=Utilisateur::getUtilisateurById($DB,$lot['l_id_contact']);

        $tab_lots[$i]['contact']= $Info_utilisateur[0]->u_prenom." " .$Info_utilisateur[0]->u_nom;

    }
    
    $i++;
}

/*--- Cas particulier : pas de lot pour le projet ----*/
if (!isset($lst_lots)) {
    $_SESSION['erreur']="pas de données";
} 
?>

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


  <h2 class="text-center">Liste des lots</h2>   
    <?php if (!empty($_SESSION["id_projet"])) { ?>
       <!-- L'administrateur principal et l'administrateur sont les seuls à pouvoir ajouter un lot -->
        <?php if (($_SESSION["role"]==1) || ($_SESSION["role"]==8)) {?>
            <div class="row">
                <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
                    <a href="aj_lot.php" class="btn btn-primary btn-sm" role="button">Ajouter un lot</a>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"></div>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <div class="input-group"><span class="input-group-addon">Recherche</span>
                        <input id="filter" type="text" class="form-control" placeholder="">
                    </div>
                </div>
            </div>
            <?php } else { ?>

            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8"></div>
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <div class="input-group"><span class="input-group-addon">Recherche</span>
                        <input id="filter" type="text" class="form-control" placeholder="">
                    </div>
                </div>
            </div>



            <?php } ?>

   <?php } ?>
    <br />
  <table class="table table-condensed">
    <thead>
      <tr>
        <th>Lot</th>
		 <th>Date de livraison</th>
		  <th>Date de réception</th>
          <th>Acquéreur</th>
          <th>locataire</th>
          <th>Contact pour rdv</th>
          <th>Actions</th>
      </tr>
    </thead>
    
    <?php
    /* Boucle pour la liste des utilisateurs */
    foreach ($tab_lots as $lot) {?>
        <tbody class="searchable">
      <tr>
        <td class="vert-align"><?php echo utf8_decode(stripslashes($lot['lot'])) ?></td>
        <td class="vert-align"><?php echo $lot['date_livraison']?></td>
          <td class="vert-align"><?php echo $lot['date_reception']?></td>
          <td class="vert-align"><?php echo utf8_decode(stripslashes($lot['acquereur']))?></td>
          <td class="vert-align"><?php echo utf8_decode(stripslashes($lot['locataire']))?></td>

          <?php if (is_null($lot['contact'])) { ?>


              <td class="vert-align highlight">Information indispensable !</td>

          <?php } else { ?>

              <td class="vert-align"><?php echo utf8_decode(stripslashes($lot['contact']))?></td>

          <?php } ?>


          
        <td><a href="aj_lot.php?id=<?php echo $lot['id']?>" class="btn btn-primary" role="button">Modifier</a></td>

          <td><a href="sup_lot.php?id=<?php echo $lot['id']?>" class="btn btn-primary" role="button">Supprimer</a></td>

      </tr>
    </tbody>
      <?php  }?>
  </table>
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


<?php require 'includes/footer_rub.php'?>
