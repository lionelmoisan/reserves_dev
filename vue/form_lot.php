<?php require 'includes/header.php';?>

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

    <h2 class="text-center"><?php echo $titre_rubrique?></h2>  
    
    <form id="Addlot" action="aj_lot.php?id=<?php echo $_GET['id']?>" class="form-horizontal" method="post" id="formlot" name="lot">
    
    <div class="form-group <?php echo $erreur_numero_lot ?>">
      <label class="control-label col-sm-4" for="numero">N° du lot * :</label>
      <div class="col-sm-2">
        <input type="texte" class="form-control" id="numero_lot" name="numero_lot" <?php echo $modif_champ_numero_lot?>
               value="<?php echo isset($_POST['numero_lot'])?$_POST['numero_lot']:utf8_decode(stripslashes($lot->l_numero_lot));?>">
      </div>
    </div>
    
    <div class="form-group <?php echo $erreur_date_livraison ?>">
      <label class="control-label col-sm-4" for="date">Date de livraison * :</label>
      <div class="col-sm-3">
        <input  class="form-control" id="date_livraison" name="date_livraison" <?php echo $modif_champ_date_livraison?>
               value="<?php echo isset($_POST['date_livraison'])?$_POST['date_livraison']:$lot->l_date_livraison;?>">
      </div>
    </div>
        
     <div class="form-group">
      <label class="control-label col-sm-4" for="date">Date de réception :</label>
      <div class="col-sm-3">
        <input  class="form-control" id="date_reception" name="date_reception" <?php echo $modif_champ?>
               value="<?php echo isset($_POST['date_reception'])?$_POST['date_reception']:$lot->l_date_reception;?>">
      </div>
    </div>
        
    <div class="form-group">
        <label class="control-label col-sm-4" for="acquereur">Acquéreur : </label>
        <div class="col-sm-3">
            <select name="lstacquereur" id="lstacquereur" class="form-control" <?php echo $modif_champ?>">
                <option value="">Choisir l'acquéreur :</option>
                <?php foreach($lst_acquereurs as $acquereur) {?>   
                    <option value="<?php echo $acquereur['u_id'] ?>"   <?php if($lot->l_id_acquereur==$acquereur['u_id']) {print 'selected';} ?>      > <?php echo utf8_decode(stripslashes($acquereur['u_nom'])).' '.utf8_decode(stripslashes($acquereur['u_prenom']));?></option>
                <? } ?>
            </select>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-4" for="locataire">Locataire :</label>
        <div class="col-sm-3">
            <select name="lstlocataire" id="lstlocataire" class="form-control">
                <option value="">Choisir le locataire :</option>
                <?php foreach($lst_locataires as $locataire) { ?>    
                    <option value="<?php echo $locataire['u_id'] ?>" <?php if($lot->l_id_locataire==$locataire['u_id']) { print 'selected';} ?> ><?php echo utf8_decode(stripslashes($locataire['u_nom'])).' '.utf8_decode(stripslashes($locataire['u_prenom']));?></option>
                <? } ?>
            </select>
        </div>
        <div class="col-sm-3"></div>
    </div> 

    <div class="form-group">
        <label class="control-label col-sm-4" for="contact">Contact pour prise de rdv d'intervention :</label>
            <div class="col-sm-3 text-center">
                    <?php

                        foreach ($data_choix_contact as $trigramme => $type_contact) {
                            ?>
                            <label class="radio-inline"><input onchange="getAutrecontact(this)" type="radio" name="contact_radio" value="<?php echo $trigramme;?>" <?php if($lot->l_choix_contact==$trigramme) { print 'checked';} ?>  id="contact_radio" onchange="getAutrecontact(this);"><?php echo $type_contact;?></label>
                          <?
                        }
                    ?>

            </div>
        <div class="col-sm-3 <?php echo $afficher ?>" name="choix_autre_contact" id="choix_autre_contact">

            <select class="form-control" name="lstcontact" id="lstcontact">
                <option value="">Choisir un autre contact</option>
                <?php
                foreach($lst_contacts as $contact) {
                    ?>
                    <option value="<?php echo $contact['u_id'] ?>" <?php if($lot->l_id_contact==$contact['u_id']) { print 'selected';} ?> ><?php echo utf8_decode(stripslashes($contact['u_societe'])).' '.utf8_decode(stripslashes($contact['u_nom']));?></option>
                <? } ?>
            </select>


        </div>
</div>

    <div class="form-horizontal">
      <div class="col-sm-7 text-right">
          <a class="btn btn-default" href="lst_lots.php" role="button">Annuler</a>
          <button type="submit" class="btn btn-primary" name="sumit_lot">Enregistrer</button>
      </div>
    </div>
</form>
</div>



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.43/jquery.form-validator.min.js"></script>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<!--<script src="dist/validator.min.js"></script>-->


<script>

    var myLanguage = {
    requiredFields: 'Champ obligatoire',
    badAlphaNumeric: 'Champ obligatoire ',
    badEmail: 'Adresse email incorrect',
    badDate:"Le format de la date est jj/mm/aaaa",
    andSpaces: ' et espace ',
    badAlphaNumericExtra: ' autorisé ',
    };
    
  $.validate({
     language : myLanguage,
      form : '#newutilisateurform-acquereur',
      modules : 'date, security',
          onModulesLoaded : function() {
          }
});
    
      $.validate({
     language : myLanguage,
      form : '#newutilisateurform-locataire',
      modules : 'date, security',
          onModulesLoaded : function() {
          }
});
    
    
          $.validate({
     language : myLanguage,
      form : '#newutilisateurform-contact',
      modules : 'date, security',
          onModulesLoaded : function() {
          }
});
    
    

      $.validate({
     language : myLanguage,
      form : '#Addlot',
      modules : 'date, security',
          onModulesLoaded : function() {
          }
});

</script>


<script type="text/javascript">

    function getAutrecontact(autre) {

        if ((autre.value=='acq') || (autre.value == 'loc')) {

            $("#choix_autre_contact").hide();

        }
        if(autre.value=='autre') {

            $("#choix_autre_contact").show();
        }

    }


    function getinfoutilisateur(id,role) {               
    $.ajax({
       url : 'rch_utilisateur_ajax.php',
       type : 'GET',
       data : 'id=' + id.value,
       dataType : 'html',
       success : function(code_html){ // success est toujours en place, bien sûr !
           $(role).html(code_html);
           $(role).removeClass("col-sm-8 display-none").addClass("col-sm-8");
        },
            error : function(resultat, statut, erreur){
            alert("erreur");
        }
        });        
    }

    
</script>

<?php require 'includes/footer.php'; ?>