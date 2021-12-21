<?php require 'includes/includes_back.php';


// Message pour l'administrateur / et l'admin
if (($_SESSION['role']==1) || ($_SESSION['role']==8)) {

    if (!isset($_SESSION['message'])) {
        $_SESSION['message']="<span class='info-bleu'>info</span> : Seuls les intervenants actifs ont accès à la plate-forme sur Internet. Les intervenants non actifs reçoivent néanmoins les notifications par mail (relances entreprises, infos acquéreurs). <br>".
            "Pour être actifs, les intervenants doivent cliquer sur le lien présent dans l'e-mail de connexion qu'ils ont reçu automatiquement.<br><br>".
            "Ils ne l'ont pas reçu !! : vérifier que leur adresse est bonne puis renvoyez-leur en cliquant sur l'icône <i class='fa fa-newspaper-o'></i>";
    }
} elseif ($_SESSION['role']==2) {

    if (!isset($_SESSION['message'])) {
        $_SESSION['message']="<span class='info-bleu'>info :</span> Avec votre profil MOE, vous êtes limités dans la création et la modification des intervenants.";
    }
}

/* ----  Recherche la liste des utilisateurs ---- */
$data= array(
    'id_projet'=>$_SESSION['id_projet']
    );

$suisjeadmin=Auth::isadmin($DB,$_SESSION['id_user']);

if (isset($_SESSION['email_uti_encours'])){
    unset($_SESSION['email_uti_encours']);  
}

if (isset($_SESSION['identifiant_uti_encours'])){
    unset($_SESSION['identifiant_uti_encours']);  
}

if ($suisjeadmin) {
    $lst_utilisateurs=Utilisateur::getAllUtiWithAdmin($DB,$data); 
    
} else {
    $lst_utilisateurs=Utilisateur::getAllUtiOrAdmin($DB,$data);
}

?>
<?php require 'includes/header.php'; ?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info" role="alert "><?php echo $_SESSION['message'];?></div>
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
  <h2 class="text-center">Liste des intervenants</h2>       
    <?php if (!empty($_SESSION["id_projet"])) { ?>
    
    <div class="row">
        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
        <a href="aj_utilisateur.php" class="btn btn-primary active btn-sm" role="button">Créer un intervenant</a>
        </div>
        
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"></div>
          <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
           <div class="input-group"><span class="input-group-addon">Recherche</span>
                <input id="filter" type="text" class="form-control" placeholder="">
            </div>
        </div>

   </div>
   <br />
    

    <?php } ?>
  <table class="table table-condensed">
    <thead>
      <tr>
        <th class="col-lg-1 col-md-1 col-xs-1">Nom</th>
        <th class="col-lg-1 col-md-1 col-xs-1">Prénom</th>
		<th class="col-lg-1 col-md-1 col-xs-1">Entreprise</th>
        <th class="col-lg-1 col-md-1 col-xs-1">Société</th>
        <th class="col-lg-1 col-md-1 col-xs-1">Email</th>
        <th class="col-lg-2 col-md-2 col-xs-2">Téléphone</th>
        <th class="col-lg-1 col-md-1 col-xs-1">Adresse</th>
        <th class="col-lg-1 col-md-1 col-xs-1">Actif</th>
        <th class="col-lg-1 col-md-1 col-xs-1">Rôle</th>
        <th class="col-lg-1 col-md-1 col-xs-1">Actions</th>
      </tr>
    </thead>

    <?php
    /* Boucle pour la liste des utilisateurs */
    foreach ($lst_utilisateurs as $utilisateur) {?>
    <tbody class="searchable">
      <tr>
        <td class="col-xs-1"><?php echo Chaines::trt_select_string($utilisateur['u_nom'])?></td>
        <td class="col-xs-1"><?php echo Chaines::trt_select_string($utilisateur['u_prenom'])?></td>
		<td class="col-xs-1"><?php echo Chaines::trt_select_string($utilisateur['u_entreprise'])?></td>
        <td class="col-xs-1"><?php echo Chaines::trt_select_string($utilisateur['u_societe'])?></td>
        <td class="col-xs-1"><?php echo stripslashes($utilisateur['u_email'])?></td>
        <td class="col-xs-1">Port : <?php echo stripslashes($utilisateur['u_portable_1'])?></br>Fixe : <?php echo stripslashes($utilisateur['u_telephone'])?><br/>Por 2 : <?php echo stripslashes($utilisateur['u_portable_2'])?></td>
        <td class="col-xs-1"><?php echo Chaines::trt_select_string($utilisateur['u_adresse'])?><br/><?php echo stripslashes($utilisateur['u_cp'])?> - <?php echo Chaines::trt_select_string($utilisateur['u_ville'])?></td>

          <?php  if  (($utilisateur['u_role']==5) || ($utilisateur['u_role']==7)) { ?>

              <td class="col-xs-1">Sans Objet</td>

          <?php } else {  ?>
              <?php if ($utilisateur['u_actif']==1) { ?>
                  <td class="col-xs-1 greenlight">Oui</td>
              <?php } else { ?>
                  <td class="col-xs-1 highlight">Non</td>
              <?php } ?>
          <?php  } ?>

        <td class="col-xs-1"><?php     
            $description_role=$DB->descRole($utilisateur['u_role']);
            echo $description_role;
        ?>  
          </td>
          <td class="col-lg-3 col-md-3 col-xs-3">
              <?php
                // BOUTONS D'ACTION EN FONCTION DU ROLE DE L'UTILISATEUR CONNECTE
                if ($_SESSION['role']==2) {
                    if  (($utilisateur['u_role']==5) ||($utilisateur['u_role']==6) || ($utilisateur['u_role']==7)) { ?>
                        <a class="btn btn-primary btn-xs" href="up_utilisateur.php?id=<?php echo $utilisateur['u_id']?>" role="button">Modifier</a>
               <?php  }
              }?>

              <?php

              //var_dump($utilisateur);

              // BOUTONS D'ACTION EN FONCTION DU ROLE DE L'UTILISATEUR CONNECTE
              if (($_SESSION['role']==1) || ($_SESSION['role']==8)) { ?>
                  <a class="btn btn-primary btn-xs" href="up_utilisateur.php?id=<?php echo $utilisateur['u_id']?>" role="button">Modifier</a>

                  <?php if (($utilisateur['u_email']<>'') && ($utilisateur['u_role']<>5) && ($utilisateur['u_role']<>7)) { ?>
                      <a class="btn btn-primary btn-xs" href="validate_email.php?id=<?php echo $utilisateur['u_id']?>" role="button"><i class="fa fa-newspaper-o"  title="Envoi d'un e-mail de connexion"></i></a>
                      <!--<a class="btn btn-primary btn-xs" href="envoyer_infos_connexion.php?id=<?php echo $utilisateur['u_id']?>" role="button"><i class="fa fa-key" title='Envoi du code de connexion'></i></a>-->
                  <?php } ?>
              <?php }?>


              <?php $test=Utilisateur::VerifyUserIsdelete($DB,$utilisateur['u_id']);
              
              print_r($test);
                
              ?>
            
          </td>
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

<?php require 'includes/footer_rub.php';?>