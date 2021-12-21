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
  <h2 class="text-center">Ajouter un intervenant au projet</h2>  
    
<form id="AddUtiExistant" action="aj_util_ex.php" class="form-horizontal" method="post" id="signup">
    
    <div class="row">
       <div class="col-xs-0 col-sm-6 col-md-8 col-lg-8"></div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
           <div class="input-group"><span class="input-group-addon">Recherche</span>
               <input id="filter" type="text" class="form-control" placeholder="">
            </div>
        </div>
    </div>   
       
       
   <br/>
      
      <div class="scroll-lst-utilisateur"> 
      <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Entreprise</th>
                    <th>Identifiant</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Rôle</th>
                    <th>Ajouter</th>
                </tr>
            </thead>
            <tbody class="searchable">
                
      <?php     
    
    if(!empty($lst_uti_not_in_proj)) {
                        /* Boucle pour la liste des roles sans l'administrateur */
                        foreach ($lst_uti_not_in_proj as $utilisateur) {    
                    ?>
                  <tr>
                    <td class="col-xs-1"><?php echo utf8_decode(stripslashes($utilisateur['u_nom']))?></td>
                    <td class="col-xs-2"><?php echo utf8_decode(stripslashes($utilisateur['u_prenom']))?></td>
                      <td class="col-xs-2"><?php echo utf8_decode(stripslashes($utilisateur['u_entreprise']))?></td>
                    <td class="col-xs-1"><?php echo $utilisateur['u_identifiant']?></td>
                    <td class="col-xs-1"><?php echo stripslashes($utilisateur['u_email'])?></td>
                    <td class="col-xs-2">Port : <?php echo stripslashes($utilisateur['u_portable_1'])?></br>Fixe : <?php echo stripslashes($utilisateur['u_telephone'])?><br/>Por 2 : <?php echo stripslashes($utilisateur['u_portable_2'])?></td>
                    <td class="col-xs-3"><?php echo utf8_decode(stripslashes($utilisateur['u_adresse']))?><br/><?php echo stripslashes($utilisateur['u_cp'])?> - <?php echo utf8_decode(stripslashes($utilisateur['u_ville']))?></td>
                    <td><?php echo $utilisateur['u_role']?></td>
                    <td class="col-xs-2"><input data-validation="checkbox_group" data-validation-qty="min1" type="checkbox" name="utilisateur_list[]" value="<?php echo $utilisateur['u_id']?>"></td>
                      </tr>  
                    <? 
                        }
                    } else { ?>
                        <tr>
                            <td class="col-xs-1 text-center" colspan="8">Tous les intervenants disponibles sont déjà présents dans le projet</td>
                        </tr>
                    
                    <?}?>
            </tbody>
        </table>
</div>
    
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10 text-right">
          <a class="btn btn-default" href="lst_utilisateurs.php" role="button">Annuler</a>
          <button type="submit" class="btn btn-primary">Enregistrer</button>      
      </div>
    </div>
  </form>

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.43/jquery.form-validator.min.js"></script>
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
    
    
 /* ----   Validation du formulaire xxx ---*/
        var myLanguage = {
        requiredFields: 'Champ obligatoire',
        groupCheckedTooFewStart :"S'il vous plaît choisir au moins ",
        groupCheckedEnd :' projet(s)' 
    };
    
    $.validate({
        language : myLanguage,
        form : '#AddUtiExistant',
         modules : 'security',
          onModulesLoaded : function() {
          }
    });    
</script>

<?php require 'includes/footer_rub.php';?>