<?php require 'includes/includes_back.php';

if(!empty($_GET['id'])){
    
    $info_reserve=Reserve::getIdReserve($DB,$_GET['id']);    
    $lst_statut=Reserve::GetLstStatut($DB);
    $lst_statut_historique=Reserve::GetHistoStatut($DB,$_GET['id']);
    
    $info_lot=lot::getLotById($DB,$info_reserve['r_id_lot']);

} else {
    echo "Erreur dans le chargement des informations";
}
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-center">Mise à jour du statut de la réserve :
    <br />Lot : <?php echo utf8_decode(stripslashes($info_lot->l_numero_lot)); ?> - <?php echo utf8_decode(stripslashes($info_reserve['r_description']))?></h4>
</div>


<div class="panel panel-default">
        <div class="panel-body">
          <form class="contact" name="contact" action="update_statut_reserve.php" method="post">
                    <label for="message"><h4>Mise à jour du statut : </h4></label><br>
                   <select name="statut" class="input-sm">
                       <?php foreach ($lst_statut as $statut) { ?>
                       <option value="<?php echo $statut['ls_id'] ?>" <?php if($info_reserve['r_ls_id']==$statut['ls_id']){ print 'selected';}?> class='btn-xs'> <?php echo utf8_decode($statut['ls_description'])?></option>
                    <?php }?>            
                     </select>    
                
              <input type="hidden" name="id_reserve" value="<?Php echo $info_reserve['r_id']; ?>">
              <input type="hidden" name="id_statut_old" value="<?Php echo $info_reserve['r_ls_id']; ?>">

                <input class="btn btn-primary text-right btn-sm" type="submit" value="Envoyer" id="Majstatutreserve">  
          </form>
        </div>
    </div>
        <div class="modal-body scroll-lst">
            <h4>Historique des statuts</h4>
                <table class='table'>
                    <thead>
                        <tr>
                            <th class="col-xs-3">Status</th>
                            <th class="col-xs-4">Emetteur</th>
                            <th class="col-xs-5">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    
                    if (!empty($lst_statut_historique)) {
    
                        foreach ($lst_statut_historique as $statut_historique) {

                            $date_historique=Db::DecodeDateWithHeure($statut_historique['rsh_date_modifier']);

                            echo "<tr><td class='col-xs-3'>".utf8_decode($statut_historique['ls_description'])."</td><td class='col-xs-4'>".utf8_decode(stripslashes($statut_historique['u_prenom']))." ".utf8_decode(stripslashes($statut_historique['u_nom']))."</td><td class='col-xs-5'>".$date_historique."</td></tr>";
                        }

                    } else {
                        echo "<tr class='odd gradeX'><td colspan='3' class='text-center'>Pas de données</td></tr>";
                    }
                    ?>
                        </table>
                    </tbody>
                    
      </div>