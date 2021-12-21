<?php require 'includes/includes_back.php';
require 'includes/header.php';
require_once 'Classes/projet.php';

/* recherche la liste des projets */    
//$lst_projets = $DB->query('SELECT p_id,p_description FROM gr_projets');

$lst_projets = Projet::getAllProjets($DB);

?>

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
    <?php unset($_SESSION['erreur']) ?>
<?php endif ?>        
</div> 


<div class="container">
    <h2 class="text-center">Liste des projets</h2>    
    
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
        <a href="aj_projet.php" class="btn btn-primary btn-sm btn-block" role="button">Ajouter un projet</a>        
        </div>
    </div> 
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           
           
        <table class="table table-hover">
        <thead>
          <tr>
             <th>Description</th>
              <th></th>	
          </tr>
        </thead>
    
    <?php
    /* Boucle pour la liste des utilisateurs */
    foreach ($lst_projets as $projet) {?>  
    <tbody>
      <tr>
        <td><?php echo stripslashes($projet->p_description)?></td>
        <td class="text-right"><a class="btn btn-primary" href="aj_projet.php?id=<?php echo $projet->p_id?>" role="button">Modifier</a></td>
      </tr>
    </tbody>
      <?php  }?>
  </table>
            
        
        </div>
        
</div>
<?php require 'includes/footer_rub.php';?>