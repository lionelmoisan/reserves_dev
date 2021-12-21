<?php require 'includes/includes_back.php';

if(!empty($_GET['id'])){

    $info_reserve=Reserve::getIdReserve($DB,$_GET['id']);    
    
    $lst_statut=Reserve::GetLstStatut($DB);
    
    $info_lot=lot::getLotById($DB,$info_reserve['r_id_lot']);
    
    $id_contact=$info_lot->l_id_contact;
    
    $info_contact=Utilisateur::getUtilisateurById($DB,$id_contact);
    
    
    $id_acquereur=$info_lot->l_id_acquereur;
    
    $info_acquereur=Utilisateur::getUtilisateurById($DB,$id_acquereur);
    
    
    $id_locataire=$info_lot->l_id_locataire;
    
    $info_locataire=Utilisateur::getUtilisateurById($DB,$id_locataire);

    $sql_rch_statut = 'SELECT ls_description FROM gr_lst_statut WHERE ls_id='.$info_reserve['r_ls_id'];
    $req_rch_statut = $DB->tquery($sql_rch_statut);


    $sql_rch_res_img = 'SELECT ri_description, ri_url FROM gr_res_images WHERE ri_r_id='.$_GET['id'];

    $req_rch_res_img = $DB->tquery($sql_rch_res_img);



    /* Recherche de la date de chgt de statut */

    if ($info_reserve['r_ls_id'] <> 1 ) {

        if ($info_reserve['r_ls_id'] <> 8 ) {

            $lst_statut_historique = Reserve::GetHistoStatut($DB, $info_reserve['r_id']);
            $chgt_statut_le = Db::DecodeDateWithHeure($lst_statut_historique[0]['rsh_date_modifier']);

        } else {

            $chgt_statut_le=$info_reserve['r_date_signalement'];
        }

    } else {
        $chgt_statut_le=$info_reserve['r_date_signalement'];
    }

    
} else {
    echo "Erreur dans le chargement des informations";
}
?>

<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Détail du lot
              <br /><?php echo utf8_decode(stripslashes($info_lot->l_numero_lot));?> - <?php echo utf8_decode(stripslashes($info_reserve['r_description']))?></h4>
</div>

<div class="modal-body">
 
  <div class="row">
      <div class="col-xs-12 col-sm-6">
          <b>Pièce ou Local :</b> <?php echo utf8_decode(stripslashes($info_reserve['r_piece']))?>
          <br/><br/>
          <b>Type :</b> <?php echo utf8_decode($info_reserve['r_type'])?>
      </div>
      
      

      <div class="col-xs-12 col-sm-6">
          <b>Date de signalement :</b> <?php echo $info_reserve['r_date_signalement']?>
          <br/>
          <b>Date de livraison :</b> <?php echo $info_lot->l_date_livraison ?>
          <br/>
          <b>Date de réception :</b> <?php echo $info_lot->l_date_reception ?>
      </div>
      
    </div>

    <hr>
    <div class="row bg-info">
        <div class="col-xs-12 col-sm-12">
            <div class="text-center"><b>Statut : </b><?php echo utf8_decode($req_rch_statut[0]['ls_description']);?></div>
            <div class="text-center"><b>Dernier changement le  : </b><?php echo $chgt_statut_le;?></div>

        </div>
    </div>


    <hr>
    <div class="row">


        <div class="col-xs-12 col-sm-2">
            <b>image : </b>
        </div>


        <div class="col-xs-12 col-sm-10">

            <div class="row">

                <?php

                $nbr_img=1;
                ?>

               <?php foreach ($req_rch_res_img as $res_image) {?>


                <div class="col-xs-6 col-sm-6">
                   <!--<label><?php //echo stripslashes($res_image['ri_description'])?></label>-->

                    <a href="<?php echo $res_image['ri_url']; ?>" target="_blank"><img id="myImg_<?php echo $nbr_img;?>" src="<?php echo $res_image['ri_url']; ?>" class="" height="150px" width="150px"></a>

                    </div>

                   <?php $nbr_img++;?>



                <?php }?>

            </div>



        </div>
    </div>
    <hr>
    
    <div class="row">
      <div class="col-xs-12 col-sm-12">
          <h4 class="text-center">Contact</h4>
           <p class="text-center">
           <?php echo utf8_decode(stripslashes($info_contact[0]->u_nom)); ?>
           <?php echo utf8_decode(stripslashes($info_contact[0]->u_prenom)); ?>
           <br/>
           <?php echo utf8_decode(stripslashes($info_contact[0]->u_adresse))." - ";?>
           <?php echo stripslashes($info_contact[0]->u_cp);?>
           <?php echo utf8_decode(stripslashes($info_contact[0]->u_ville));?> 
          <br/><br/>
            Portable 1 : <?php echo $info_contact[0]->u_portable_1;?>
            <br/>
            Téléphone : <?php echo $info_contact[0]->u_telephone;?>
            <br/>
            Portable 2 :<?php echo $info_contact[0]->u_portable_2;?>
          <br/>
         <?php  echo "<a href='mailto:".$info_contact[0]->u_email."'>".$info_contact[0]->u_email."</a>" ?>
          <br/></p>
          </div>  
    </div>
        
  <hr>
  
   <div class="row">
                <div class="col-xs-12 col-sm-6">
                 <h4 class="text-center">Acquéreur</h4> 
                <p class="text-center">
                <?php echo utf8_decode(stripslashes($info_acquereur[0]->u_nom));?>
                 <?php echo utf8_decode(stripslashes($info_acquereur[0]->u_prenom));?>
                  <br >
                  <?php echo utf8_decode(stripslashes($info_acquereur[0]->u_adresse))." - ";?>
                    <?php echo stripslashes($info_acquereur[0]->u_cp);?>
                    <?php echo utf8_decode(stripslashes($info_acquereur[0]->u_ville));?> 
                  <br/><br/>
                  Portable 1 : <?php echo $info_acquereur[0]->u_portable_1;?>
                  <br/>
                  Téléphone : <?php echo $info_acquereur[0]->u_telephone;?>
                  <br/>
                  Portable 2 :<?php echo $info_acquereur[0]->u_portable_2;?>
                  <br/>
                 <?php  echo "<a href='mailto:".$info_acquereur[0]->u_email."'>".$info_acquereur[0]->u_email."</a>" ?>
                    </p>
                  
                </div>
                <div class="col-xs-12 col-sm-6">
                  <h4 class="text-center">Locataire</h4> 
                <p class="text-center">
                <?php echo utf8_decode(stripslashes($info_locataire[0]->u_nom));?>
                 <?php echo utf8_decode(stripslashes($info_locataire[0]->u_prenom));?>
                  <br >
                  <?php echo utf8_decode(stripslashes($info_locataire[0]->u_adresse))." - ";?>
                    <?php echo stripslashes($info_locataire[0]->u_cp);?>
                    <?php echo utf8_decode(stripslashes($info_locataire[0]->u_ville));?> 
                  <br/><br/>
                  Portable 1 : <?php echo $info_locataire[0]->u_portable_1;?>
                  <br/>
                  Téléphone : <?php echo $info_locataire[0]->u_telephone;?>
                  <br/>
                  Portable 2 :<?php echo $info_locataire[0]->u_portable_2;?>
                   <br/>
                 <?php  echo "<a href='mailto:".$info_locataire[0]->u_email."'>".$info_locataire[0]->u_email."</a>" ?>
                    </p>
                </div>
              </div>
  
  
</div>
