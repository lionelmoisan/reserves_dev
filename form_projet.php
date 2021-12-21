<?php require 'includes/header.php'?>

<div>
<?php if (isset($_SESSION['message'])): ?>
    <span class="label label-info"><?php echo $_SESSION['message'];?></span>
    <?php unset($_SESSION['message']) ?>
<?php endif ?>   
    
    
<?php if (isset($_SESSION['erreur'])): ?>
    <span class="label label-danger"><?php echo $_SESSION['erreur'];?></span>
    <?php unset($_SESSION['erreur']) ?>
<?php endif ?>        
</div>


<div class="container">
  <h2>Ajouter/Modifier un projet</h2>  
	<form action="aj_projet.php?id=<?php echo $_GET['id']?>" class="form-horizontal" method="post" id="">
    <div class="form-group">
      <label class="control-label col-sm-2" for="prenom">Description :</label>
      <div class="col-sm-10">
        <input type="texte" class="form-control" id="description" name="description" placeholder="Entrer une description" 
               value="<?php echo isset($_POST['description'])?$_POST['description']:stripslashes($projet_update->p_description);?>">
      </div>
    </div>
	
	    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Enregistrer</button>      
          <a class="btn btn-default" href="lst_utilisateurs.php" role="button">Annuler</a>
      </div>
    </div>
  </form>
</div>

<?php require 'includes/footer.php';?>