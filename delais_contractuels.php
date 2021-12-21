<?php require 'includes/includes_back.php';?>

<?php 

$delais=Delais::getDelaisContractuels($DB,$_SESSION['id_projet']);

if(!empty($_POST)){     
    
	$nbrjourdelailivraison=intval($_POST['nbr_jours_delai_livraison']);
	$nbrjourdelailgpa=intval($_POST['nbr_jours_delai_gpa']);
	
	$data = array(
		'id_projet'=>$_SESSION['id_projet'],
		'nbr_jour_livraison'=>$nbrjourdelailivraison,
		'nbr_jour_gpa'=>$nbrjourdelailgpa
	);
	
	$rep_update_delais=Delais::setUpdateDelaisContractuels($DB,$data);
	
	if ($rep_update_delais){
		$_SESSION['message']="Mise à jour du délai contractuel de levée des réserves de livraison";
	} else {
		$_SESSION['erreur']="Problème dans la mise à jour du délai";
	}
}

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
    <h2 class="text-center">Définition des délais contractuels entreprises</h2>
    <p class="text-center">Le Nombre de jour à 0 permet de désactiver la prise en compte des délais contractuels</p>
    <form id="AddUtilisateur" action="delais_contractuels.php" class="form-horizontal" method="post" id="signup" data-toggle="validator">
        <div class="form-group">
            <label class="control-label col-sm-5" for="prenom">Délai contractuel de levée des réservers de livraison :</label>
            <div class="col-sm-1"><input type="number" min="0" max="365" class="form-control" id="nbr_jours_delai_livraison" name="nbr_jours_delai_livraison"            value="<?php echo isset($_POST['nbr_jours_delai_livraison'])?$_POST['nbr_jours_delai_livraison']:$delais->dd_nbr_jour_delai_livraison;?>"></div>
            <label class="control-label col-sm-4 label-left" for="">jours à compter de la date de réception entreprises</label>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-5" for="nom">Délai contractuel de levée des GPA :</label>
            <div class="col-sm-1"><input type="number" min="0" max="365" class="form-control" id="nbr_jours_delai_gpa" name="nbr_jours_delai_gpa" value="<?php echo isset($_POST['nbr_jours_delai_gpa'])?$_POST['nbr_jours_delai_gpa']:$delais->dd_nbr_jour_delai_gpa;?>"></div>
            <label class="control-label col-sm-4 label-left" for="">jours à compter de la date de signalement</label>
        </div>

        <div class="form-group">
          <div class="col-sm-offset-5 col-sm-5">
              <a class="btn btn-default" href="index.php" role="button">Annuler</a>
              <button type="submit" class="btn btn-primary">Enregistrer</button>
          </div>
        </div>
    </form>
</div>


<?php require 'includes/footer_rub.php';?>