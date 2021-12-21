<?php require 'includes/includes_back.php';
/**
 * Created by PhpStorm.
 * User: lionelmoisan
 * Date: 27/03/2017
 * Time: 10:48
 */
$lst_lots=lot::getLots($DB,$_SESSION["id_projet"]);

$data = array(
    'role'=>6,
    'id_projet'=>$_SESSION["id_projet"]
);

$lst_entreprises=Utilisateur::getEntreprise($DB,$data);

if(!empty($_GET['id'])){

    $info_reserve=Reserve::getIdReserve($DB,$_GET['id']);


    $sql_rch_res_img = 'SELECT ri_id, ri_description, ri_url FROM gr_res_images WHERE ri_r_id='.$_GET['id'];

    $req_rch_res_img = $DB->tquery($sql_rch_res_img);

    //var_dump($req_rch_res_img);


} else {
    echo "Erreur dans le chargement de la réserve";
}
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-center">Mise à jour d'une réserve
</div>

<div class="modal-body">

    <form id="reserveupdate" action="up_reserve_trt.php"  method="post"  class="form-horizontal" enctype="multipart/form-data">

        <div class="form-group">
            <label class="control-label col-sm-4" for="pwd">Lot * :</label>
            <div class="col-sm-8">
                <select name="lot" data-validation="required">
                    <option value="">Choisir un lot</option>
                    <?php
                    /* Boucle pour la liste des lots */
                    foreach ($lst_lots as $lot) {

                        if ($lot['l_id']<> $info_reserve['r_id_lot']){
                            ?>
                            <option value="<?php echo $lot['l_id']?>" ><?php echo Chaines::trt_select_string($lot['l_numero_lot'])?></option>
                        <?} else {?>

                            <option value="<?php echo $lot['l_id']?>" selected="selected" ><?php echo Chaines::trt_select_string($lot['l_numero_lot'])?></option>

                        <?} }?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-4" for="description">Description *:</label>
            <div class="col-sm-7">
                <input type="texte" class="form-control" id="description" name="description" placeholder="" value="<?php echo Chaines::trt_select_string($info_reserve['r_description']); ?>" data-validation="letternumeric" data-validation-allowing=" -_?">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-4" for="piece">Pièce ou local *:</label>
            <div class="col-sm-7">
                <input type="texte" class="form-control" id="piece" name="piece" placeholder=""
                       value="<?php echo Chaines::trt_select_string($info_reserve['r_piece']); ?>" data-validation="letternumeric" data-validation-allowing=" -_?">
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-4" for="type">Type *:</label>
            <div class="col-sm-8">

                <?php
                if (($info_reserve['r_type']=='Livraison') || ($info_reserve['r_type']=='livraison')) {?>
                    <input type="radio" data-validation="required"  name="type" value="livraison" checked="checked"> Livraison<br>
                    <input type="radio" data-validation="required"  name="type" value="GPA"> GPA<br>
                <? } else { ?>
                    <input type="radio" data-validation="required"  name="type" value="livraison"> Livraison<br>
                    <input type="radio" data-validation="required"  name="type" value="GPA" checked="checked"> GPA<br>

                <?php }

                ?>
            </div>
        </div>

        <?php if (($info_reserve['r_type']=='Livraison') || ($info_reserve['r_type']=='livraison')) {?>

        <div class="form-group date-signalement" id="datesignalement_update"></div>

        <?php } else {

            $date_de_signalement=Db::convertDate($info_reserve['r_date_signalement']);

            ?>
            <div class="form-group" id="datesignalement_update">
                <label class='control-label col-sm-4' for='datesignalement'>Date de signalement *:</label><div class='col-sm-7'><input type='date' class='form-control' id='datesignalement' name='datesignalement' value='<?php echo $date_de_signalement?>' data-validation='required' data-validation-format='dd/mm/yyyy'></div>
            </div>

        <?php } ?>

        <div class="form-horizontal">
            <label class="control-label col-sm-4" for="">Choix des entreprises * :</label>
            <div class="col-sm-8">
                <ul class="lstchekbox scroll-lst-entreprise-res">
                    <?php
                    if(!empty($lst_entreprises)) {
                        /* Boucle pour la liste des roles sans l'administrateur */
                        foreach ($lst_entreprises as $entreprise) {
                            if ($info_reserve['r_id_entreprise']==$entreprise['u_id']) { ?>
                                <li><input type="radio" name="entreprise_list" value="<?php echo $entreprise['u_id']?>" checked="checked">
                                    <label><?php echo Chaines::trt_select_string($entreprise['u_entreprise']);?></label></li>
                            <? } else { ?>
                                <li><input type="radio" name="entreprise_list" value="<?php echo $entreprise['u_id']?>">
                                    <label><?php echo Chaines::trt_select_string($entreprise['u_entreprise']);?></label></li>
                            <?php }
                        }
                    } else {
                        ?>
                        <li>Ajouter une entreprise pour pouvoir créer une réserve</li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-8">Les images</div>
        </div>


        <?php

        if (empty($req_rch_res_img)) { ?>


            <div class="form-group">
                        <label class="control-label col-sm-4" for="logo">Ajouter une image :</label>
                        <div class="col-sm-8"><input type="file" name="image_1" id="image_1" /><br />
                            <small id="fileHelp" class="">Formats autorisés : jpg et png - Taille maxi : 2 Mo</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="logo">Ajouter une autre image :</label>
                        <div class="col-sm-8"><input type="file" name="image_2" id="image_2" /><br />
                            <small id="fileHelp" class="">Formats autorisés : jpg et png - Taille maxi : 2 Mo</small>
                        </div>
                    </div>

             <input type="hidden" name="image_action" value="add">


        <?php } else {

            $num_photo=1;

            foreach ($req_rch_res_img as $res_image) {?>

                <div class="row">
                    <div class="col-xs-12 col-sm-12">

                        <div class="col-sm-4">
                            <a href="<?php echo $res_image['ri_url']; ?>" target="_blank"><img src="<?php echo $res_image['ri_url']; ?>" class="" height="150px" width="150px"></a>
                        </div>
                        <div class="col-sm-8">
                            <label class="control-label" for="logo">Modifier l'image N°<?php echo $num_photo?> :</label>
                            <div class="col-sm-12"><input type="file" name="image_<?php echo $num_photo?>" id="image_<?php echo $num_photo?>" />
                                <small id="fileHelp" class="">Formats autorisés : jpg et png - Taille maxi : 2 Mo</small>
                            </div>
                        </div>
                        <input type="hidden" name="id_ri_photo_<?php echo $num_photo?>" value="<?php echo $res_image['ri_id'];?>">

                    </div>
                </div>
                <div class="row">&nbsp;</div>

                <?php
                $num_photo=$num_photo+1;
                ?>

            <?php }?>

        <?php } ?>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10 text-right">
                <a id="Modal_annuler" class="btn btn-default" href="#" role="button" data-dismiss="modal">Annuler</a>
                <?php if(!empty($lst_entreprises)) { ?>
                    <input class="btn btn-primary" type="submit" value="Mettre à jour"><?php } ?>
            </div>
        </div>

        <input type="hidden" name="id_reserve" value="<?php echo $_GET['id'];?>">

    </form>

    </div>
<script>
    var myLanguage = {
        requiredFields: 'Champ obligatoire',
        badDate: 'Le format de la date est incorrect',
        groupCheckedTooFewStart :"S'il vous plaît choisir au moins ",
        groupCheckedEnd :' entreprise(s)'
    };

    $.validate({
        language : myLanguage,
        form : '#reserveupdate',
        modules : 'date, security',
        onModulesLoaded : function() {
        }
    });


    $('input[type="radio"]').click(function(){
        if($(this).attr("value")=="GPA"){
            $(".date-signalement").show();
            $('#datesignalement_update').html("<label class='control-label col-sm-4' for='datesignalement'>Date de signalement *:</label><div class='col-sm-8'><input type='date' class='form-control' id='datesignalement' name='datesignalement' value='' data-validation='required' data-validation-format='dd/mm/yyyy'></div>");

        }
        if($(this).attr("value")=="livraison"){
            $('#datesignalement_update').html("");

        }
    });


</script>


