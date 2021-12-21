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
  <h2 class="text-center"><?php echo $titre_rubrique?></h2>  
	
   
   <form id="addprojet" action="aj_projet.php?id=<?php echo $_GET['id']?>" class="form-horizontal" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label class="control-label col-sm-2" for="prenom">Description :</label>
      <div class="col-sm-6">
        <input type="texte" maxlength="75" data-validation="required" class="form-control" id="description" name="description" placeholder="" 
               value="<?php echo isset($_POST['description'])?$_POST['description']:stripslashes($projet_update->p_description);?>">
      </div>
    </div>


<?php if (!is_null($projet_update->p_logo_nom)) { ?>

       <div class="form-group">
           <label class="control-label col-sm-2" for="logo">le logo :</label>
           <div class="col-sm-6">
               <img src="Fichiers/logo/<?php echo $projet_update->p_logo_nom?>">
           </div>
       </div>

       <?php } ?>

       <div class="form-group">

           <label class="control-label col-sm-2" for="logo">Choisir un logo :</label>
           <div class="col-sm-6">
               <input type="file" name="logo" id="logo" /><br />
                <small id="fileHelp" class="">La hauteur du logo doit être de 50 pixels maximum </small>
           </div>
       </div>

       <div class="form-group">
           <label class="control-label col-sm-2" for="logo">Module GPA pour les acquéreurs :</label>
           <div class="col-sm-6">
               
               <?php
               if (!$projet_update->p_module_GPA==0) {
                   ?>

                   <label><input type="checkbox" name="module_GPA" id="module_GPA" value=1 checked> Activer le module</label>

                 <?
               } else { ?>
                   <label><input type="checkbox" name="module_GPA" id="module_GPA" value=1> Activer le module</label>

               <?
               }
               ?>
               
               
               

               </div>
       </div>


    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-6 text-right">
          <a class="btn btn-default" href="lst_projets.php" role="button">Annuler</a>
          <button type="submit" class="btn btn-primary">Enregistrer</button>      
      </div>
    </div>
  </form>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.43/jquery.form-validator.min.js"></script>
<script>
 /* ----   Validation du formulaire d'ajout de réserve ---*/
        var myLanguage = {
        requiredFields: 'Champ obligatoire',
    };
    
    $.validate({
        language : myLanguage,
        form : '#addprojet',
         modules : 'security',
          onModulesLoaded : function() {
          }
    });    
</script>
<?php require 'includes/footer.php';?>