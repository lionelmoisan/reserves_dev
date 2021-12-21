<?php require 'includes/includes_back.php';

if(!empty($_GET['id'])){
    
    $info_reserve=Reserve::getIdReserve($DB,$_GET['id']);  
	
	$lst_remarques=Reserve::GetResRemById($DB,$_GET['id']);
    
    $info_lot=lot::getLotById($DB,$info_reserve['r_id_lot']);
	
	//print_r($info_reserve);
		
} else {
    echo "Erreur dans le chargement des informations";
}
?>
    
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center">Historique des commentaires
            <br />Lot : <?php echo utf8_decode(stripslashes($info_lot->l_numero_lot));?> - <?php echo utf8_decode(stripslashes($info_reserve['r_description']))?></h4>
           
    </div>

    <div class="modal-body scroll-lst"> 
        <table class='table'>
                <thead>
                    <tr>
                        <th class="col-xs-5">Commentaire</th>
                        <th class="col-xs-3">Emetteur</th>
                        <th class="col-xs-4">Date</th>
                    </tr>
                </thead>
                <?php

                if (!empty($lst_remarques)) {

                    foreach ($lst_remarques as $remarque) {

                        $date_commentaire=Db::DecodeDateWithHeure($remarque['rr_date']);

                        echo "<tr><td class='col-xs-5'>".stripslashes($remarque['rr_remarque'])."</td><td class='col-xs-3'>".utf8_decode(stripslashes($remarque['u_prenom']))." ".utf8_decode(stripslashes($remarque['u_nom']))." - ".utf8_decode(stripslashes($remarque['u_entreprise']))."</td><td class='col-xs-4'>".$date_commentaire."</td></tr>";
                    }

                } else {
                    echo "<tr><td class='col-xs-12 text-center'><b>Pas de données</b></td></tr>";
                }
                ?>
        </table>
    </div>
    <hr>
    <div class="modal-body">

        <form id="contact" class="contact" name="contact" action="insert_remarque_reserve.php" method="post">
                <label for="message"><h4>Ajouter un commentaire : </h4></label><br>
           
                   <textarea data-validation="required" name="remarque" id="remarque" rows="4" cols="60"></textarea>
			  
              	<input type="hidden" name="id_reserve" value="<?Php echo $info_reserve['r_id']; ?>"> 
                
                <div class="text-right"> <!--You can add col-lg-12 if you want -->
                    <input class="btn btn-primary btn-sm text-right" type="submit" value="Envoyer" id="">
                </div>  
          </form>
    
    </div>

<script>
    /* ----   Validation du formulaire d'ajout d'une remarque---*/
    var myLanguagetmp = {
        requiredFields: 'Champ obligatoire',
    };

    $.validate({
        language : myLanguagetmp,
        form : '#contact',
        modules : 'security',
        onModulesLoaded : function() {
        }
    });
</script>
	