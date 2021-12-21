<?php require 'includes/includes_back.php';

if(!empty($_GET['id'])){

    $info_reserve=Reserve::getIdReserve($DB,$_GET['id']);
    $lst_statut=Reserve::GetLstStatut($DB);

    $info_lot=lot::getLotById($DB,$info_reserve['r_id_lot']);
    
    $data = array(
        'role'=>6,
        'id_projet'=>$_SESSION["id_projet"]
    );

    $lst_entreprises=Utilisateur::getEntreprise($DB,$data);

} else {
    echo "Erreur dans le chargement des informations";
}
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-center">Confirmation ou infirmation de la GPA :
        <br />Lot : <?php echo $info_lot->l_numero_lot; ?> - <?php echo utf8_decode(stripslashes($info_reserve['r_description']))?></h4>
</div>

<div class="alert alert-info" >Attention !! avez vous pris tous les avis nécessaire (Maître d'ouvrage, Maître d'oeuvre) avant de statuer sur la recevabilité de la demande de GPA ?</div>

    <div class="panel-body">
        <form id="signalementGPA" class="contact" name="contact" action="trt_signalement_GPA.php" method="post">

            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <input data-validation='required' type="radio" name="choix_action" onchange="whataction(this)" value="confirmer">&nbsp;Confirmer la prise en charge

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->

            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <input data-validation='required' type="radio" name="choix_action" onchange="whataction(this)" value="refuser">&nbsp;Refuser la prise en charge

                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->


            <div class="modal-body lst-entreprise" id="signa-gpa-lst-entreprise">

                <div class="row">
                    <label class="col-sm-12">Choix des entreprises * :</label>
                    <div class="col-sm-12">
                        <ul class="lstchekbox scroll-lst-entreprise-res">
                            <?php
                            if(!empty($lst_entreprises)) {
                                foreach ($lst_entreprises as $entreprise) {
                                    ?>
                                    <li><input data-validation="checkbox_group" data-validation-qty="min1" type="checkbox" name="entreprise_list[]" value="<?php echo $entreprise['u_id']?>">
                                        <label>&nbsp;<?php echo utf8_decode(stripslashes($entreprise['u_entreprise']));?></label></li>
                                    <?
                                }
                            } else {
                                ?>
                                <li>Ajouter une entreprise pour pouvoir créer une réserve</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

            </div>
            <br />


            <div class="row refus-gpa" id="signa-gpa-refus">
                <div class="col-lg-12">Indiquer les raisons du refus * :</div>
                <div class="col-lg-12">
                    <textarea data-validation='required' name="motif" id="motif" rows="10" cols="70" class="form-control"></textarea>
                </div>
            </div>
            

            <br/>

            <input type="hidden" name="id_reserve" id="id_reserve" value="<?php echo $info_reserve['r_id']?>">
            <input type="hidden" name="id_reserve" id="id_reserve" value="<?php echo $info_reserve['r_id']?>">

            <div class="row">
                <div class="col-lg-12">
                    <input class="btn btn-primary btn-sm pull-right" type="submit" value="Envoyer" id="Majstatutreserve">
                    </div>
                </div>
        </form>
    </div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.43/jquery.form-validator.min.js"></script>

<script>

    function whataction(choix) {

        if (choix.value == "confirmer") {



            $('#signa-gpa-lst-entreprise').html("<div class='row'><label class='col-sm-12'>Choix des entreprises * :</label><div class='col-sm-12'><ul class='lstchekbox scroll-lst-entreprise-res'><?php if(!empty($lst_entreprises)) { foreach ($lst_entreprises as $entreprise) {?><li><input data-validation='checkbox_group' data-validation-qty='min1' type='checkbox' name='entreprise_list[]'' value='<?php echo $entreprise['u_id']?>''><label>&nbsp;<?php echo utf8_decode(stripslashes($entreprise['u_entreprise']));?></label></li><?}} else {?><li>Ajouter une entreprise pour pouvoir créer une réserve</li><?php } ?></ul></div></div>");

            $(".lst-entreprise").show();

            $('#signa-gpa-refus').html("");

            $(".refus-gpa").hide();

        } else {

            $(".refus-gpa").html("<div class='col-lg-12'>Indiquer les raisons du refus * :</div><div class='col-lg-12'><textarea data-validation='required' name='motif' id='motif' rows='10' cols='70' class='form-control'></textarea></div>");

            $(".refus-gpa").show();

            $('#signa-gpa-lst-entreprise').html("");

            $(".lst-entreprise").hide();

        }

    }

</script>


<script>
    /* ----   Validation du formulaire d'ajout de réserve ---*/

    var myLanguage = {
        requiredFields: 'Champ obligatoire',
        groupCheckedTooFewStart :"S'il vous plaît choisir au moins ",
        groupCheckedEnd :' entreprise'
    };

    $.validate({
        language : myLanguage,
        form : '#signalementGPA',
        modules : 'security',
        onModulesLoaded : function() {
        }
    });


</script>