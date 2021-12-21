<!---- BLOC DE MESSAGE D'ALERTE OU D'ERREUR ----->
<div class="row">
    <div class="col-lg-12">
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
        <?php unset($_SESSION['erreur']) ?>
    <?php endif ?> 
    </div>
</div>  

<div class="row">
       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><span class="tit-page">Liste des réserves</span></div>
</div>
<br>

<table id="reserve-grid-ent"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
    <thead>
    <tr>
        <th>Ref lot</th>    
        <th>Description</th>
        <th>Piece ou Local</th>
        <th>Type</th>
        <th>Date délai</th>
        <th>Contact pour RDV</th>
        <th>Statut</th>
        <th>Chgt statut le</th>
        <th>Actions</th>
        <th>Nom du signataire</th>
        <th>Dater et signer</th>

    </tr>
    </thead>
</table>
   
</br></br>  
    
<!------------- DEBUT MODAL AJOUT D'UNE RESERVE  ----------------------->
<div id="reservemodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><center>Ajouter une réserve</center></h4>
            </div>
            
            <div class="modal-body">
                <form id="reserveform" action="aj_reserve.php"  method="post"  class="form-horizontal">
                    
                      <div class="form-group">
      <label class="control-label col-sm-4" for="pwd">Lot * :</label>
      <div class="col-sm-8">   
         <select name="lot" data-validation="required"> 
             <option value="">Choisir un lot</option>
     <?php     
            /* Boucle pour la liste des lots */
            foreach ($lst_lots as $lot) {
        ?>
              <option value="<?php echo $lot['l_id']?>" ><?php echo $lot['l_numero_lot']?></option> 
        <?
    }
?>
        </select>
      </div>
    </div>    
               
                <div class="form-group">
                  <label class="control-label col-sm-4" for="description">Description *:</label>
                  <div class="col-sm-8">
                    <input type="texte" class="form-control" id="description" maxlength="100" name="description" placeholder="" value="" data-validation="letternumeric" data-validation-allowing=" -_">
                  </div>
                </div> 
      
                 <div class="form-group">
              <label class="control-label col-sm-4" for="piece">Pièce ou local *:</label>
              <div class="col-sm-8">
                <input type="texte" class="form-control" id="piece" name="piece" placeholder=""
                        value="" data-validation="letternumeric" data-validation-allowing=" -_">
              </div>
            </div> 
      
      
            <div class="form-group">
              <label class="control-label col-sm-4" for="type">Type *:</label>
              <div class="col-sm-8">
                <input type="radio" data-validation="required"  name="type" value="livraison"> Livraison<br>
                <input type="radio" data-validation="required"  name="type" value="GPA"> GPA<br>
              </div>
            </div> 
                    
            <div class="form-group">
              <label class="control-label col-sm-4" for="datesignalement">Date de signalement *:</label>
              <div class="col-sm-8">
                <input type="date" class="form-control" id="datesignalement" name="datesignalement" placeholder="" value="" data-validation="required">
              </div>
            </div>         
       
                 <div class="form-horizontal">
              <label class="control-label col-sm-4" for="datesignalement">Choix des entreprises * :</label>
              <div class="col-sm-8">
                <ul class="lstchekbox scroll-lst-entreprise-res">
                 <?php     
                        
                    if(!empty($lst_entreprises)) {
                        /* Boucle pour la liste des roles sans l'administrateur */
                        foreach ($lst_entreprises as $entreprise) {    
                    ?>
                    <li><input data-validation="checkbox_group" data-validation-qty="min1" type="checkbox" name="entreprise_list[]" value="<?php echo $entreprise['u_id']?>">
                        <label><?php echo $entreprise['u_nom'];?></label></li>
                    <? 
                        }
                    } else { 
                    ?> 
                    <li>Ajouter une entreprise pour pouvoir créer une réserve</li>
                    <?php } ?>
                  </ul>
              </div>
            </div>    
                    
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10 text-right">
          <a id="Modal_annuler" class="btn btn-default" href="#" role="button" data-dismiss="modal">Annuler</a>
          <?php if(!empty($lst_entreprises)) { ?> 
          <input class="btn btn-primary" type="submit" value="Enregister">
          <?php } ?>
      </div>
    </div>
                                     
  </form>       
            </div>
        </div>
    </div>

</div>
<!------------- FIN  ----------------------->

<!------------- DEBUT DETAIL LOT ----------------------->
<div id="Modaldetaillot" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div id="detaillot"></div>
        <div class="modal-footer"></div>  
    </div>
  </div>
</div>
<!------------- FIN ----------------------->

<!------------- DEBUT MODAL REMARQUES RESERVE STATUT ET HISTORIQUE  ----------------------->
<div id="ModalResRemarque" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    <div id="lstremarques"></div>
    <div class="modal-footer"></div>  
    </div>
  </div>
</div>
<!------------- FIN  ----------------------->

<!------------- DEBUT MODAL MAJ STATUT ET HISTORIQUE  ----------------------->
<div id="ModalHistoriquesstatut" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      
    <div id="lststatut"></div> 
    <div class="modal-footer"></div>  
    </div>
  </div>
</div>
<!------------- FIN  ----------------------->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.2.43/jquery.form-validator.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src='pdfmake-master/build/pdfmake.min.js'></script>



<script>
            
/*--- Afficher le formulaire d'ajout de reserve ---- */
$("#ajoutReserve").click(function(){
                $("#reservemodal").modal('show');
        }); 
            
/* ----   Validation du formulaire d'ajout de réserve ---*/
var myLanguage = {
        requiredFields: 'Champ obligatoire',
        badDate: 'Le format de la date est incorrect',
        groupCheckedTooFewStart :"S'il vous plaît choisir au moins ",
        groupCheckedEnd :' entreprise(s)'
    };
    
$.validate({
        language : myLanguage,
        form : '#reserveform',
         modules : 'date, security',
          onModulesLoaded : function() {
          }
    });    
            
/* ----   DEBUT Chargement du détail d'un lot et de la réserve ---*/
$('#Modaldetaillot').on('show.bs.modal', function(e) {
    var $modal = $(this),
        esseyId = e.relatedTarget.id;

   $.ajax({
        cache: false,
        type: 'GET',
        url: 'rch_detail_lot.php',
        data: 'id='+esseyId,
        dataType : 'html',
        success: function(code_html) 
        {
            $modal.find('#detaillot').html(code_html);
        }
    });            
})
/* ----   FIN Chargement du détail d'un lot et de la réserve ---*/ 
        
        
/* ----  DEBUT HISTORIQUE DES REMARQUES ---*/
$('#ModalResRemarque').on('show.bs.modal', function(e) {
    var $modal = $(this),
        esseyId = e.relatedTarget.id;

   $.ajax({
        cache: false,
        type: 'GET',
        url: 'rch_lst_remarques.php',
        data: 'id='+esseyId,
        dataType : 'html',
        success: function(code_html) 
        {
            $modal.find('#lstremarques').html(code_html);
        }
    });            
})
/* ----   FIN HISTORIQUE DES REMARQUES---*/
        
/* ----   DEBUT Chargement de la MAJ du Statut et de l'historique des statut d'une réserve ---*/
$('#ModalHistoriquesstatut').on('show.bs.modal', function(e) {
    var $modal = $(this),
        esseyId = e.relatedTarget.id;

   $.ajax({
        cache: false,
        type: 'GET',
        url: 'rch_histo_statut.php',
        data: 'id='+esseyId,
        dataType : 'html',
        success: function(code_html) 
        {
            $modal.find('#lststatut').html(code_html);
        }
    });            
})
/* ----   FIN Chargement de l'historique des statut d'une réserve ---*/


        
</script>
    



<!--AFICHAGE DES RESERVES DE L'ENTREPRISE  -->

   <script type="text/javascript" language="javascript" >
       $(document).ready(function() {
            
        var projet = document.getElementById('nomduprojet');
        
        var dataTableEnt = $('#reserve-grid-ent').DataTable( {
            scrollY: "300px",
            "iDisplayLength": 5,
            "bLengthChange": false,
            paging: false,  
            info:true,
            stateSave: true,
            "stateSaveParams": function (settings, data) {
                data.order.order = "";
            },

            "createdRow": function ( row, data, index ) {

                var Datejour = new Date();


                if (data[6] == "En attente de confirmation") {

                    $('td', row).eq(6).addClass('statutred');

                }
                if (data[6] == "Quitus entreprise" || data[6] == "Quitus locataire" || data[6] == "Quitus MOE" || data[6] == "Quitus MOA ou AMO" || data[6] == "Quitus Acquereur" ) {

                    $('td', row).eq(6).addClass('statutgreen');

                }
                
                if (data[4] != null) {

                    var res = data[4].split("/");

                    var datedelai = res[2] + "-" + res[1] + "-" +res[0];

                    var DatedelaiV2 = new Date(datedelai);

                    if (DatedelaiV2 < Datejour) {
                        $('td', row).eq(4).addClass('highlight');

                    }
                }
            },


            "columnDefs": [
                { "width": "1%", "targets": 0 ,"orderable":false},
                { "width": "20%", "targets": 1,"orderable":false },
                { "width": "10%", "targets": 2,"orderable":false },
                { "width": "10%", "targets": 3 ,"orderable":false},
                { "width": "1%", "targets": 4 ,"orderable":false},
                { "width": "8%", "targets": 5 ,"orderable":false},
                { "width": "12%", "targets": 6 ,"orderable":false},
                { "width": "12%", "targets": 7,"orderable":false },
                { "width": "12%", "targets": 8 ,"orderable":false},
                { "width": "12%", "targets": 9,"visible": false},
                { "width": "12%", "targets": 10,"visible": false},

            ],
           
            dom: '<"toolbar">frptiB',
            buttons: [
            {
                extend: 'print',
                title:'<h1>'+projet.innerText+'<h1>',
                text:'Imprimer',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,9,10]
                }
            },
            {
                extend: 'csvHtml5',
                title:projet.innerText,
                text:'Exporter en .CSV',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
            {
                extend: 'excelHtml5',
                title:projet.innerText,
                text:'Exporter en .XLS',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },  
            {
                extend:'pdfHtml5',
                title:projet.innerText,
                text:'Exporter en PDF',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,9,10]
                }
            }
        ],

            "oLanguage": {
            "sSearch": "Recherche",
            "sInfo": "Nombre total : _TOTAL_ réserves de (_START_ à _END_)",
            "sEmptyTable": "Pas de données",
            },

            "processing": true,
            "serverSide": true,
            "ajax":{
                url :"reserve_grid_data_ent.php", // json datasource
                type: "post",  // method  , by default get
                error: function(){  // error handling
                    $(".reserve-grid-error").html("");
                    $("#reserve-grid").append('<tbody class="reserve-grid-error"><tr><th colspan="3">Pas de données</th></tr></tbody>');
                    $("#employee-grid_processing").css("display","none");

                }
            }
        
    } );
        
        $("div.toolbar").html(' Trier par : <a href="#" id="allreserve" data-column="7" class="ent-allreserve-input-button btn btn-default btn-lg  btn-sm" role="button">Afficher toutes les réserves</a>&nbsp;<a href="#" id="" data-column="7" class="ent-nonleve-input-button btn btn-default btn-lg  btn-sm" role="button">Afficher les réserves non levées</a>');


    $('.ent-allreserve-input-button').on( 'keyup click', function () {   // for text boxes
        var i =$(this).attr('data-column');  // getting column index
        var v =$(this).attr('id');  // getting search input value
        dataTableEnt.columns(i).search(v).draw();
    } );

    $('.ent-nonleve-input-button').on( 'keyup click', function () {   // for text boxes
    var i =$(this).attr('data-column');  // getting column index
    var v =$(this).attr('id');  // getting search input value
        dataTableEnt.columns(i).search(v).draw();
        } );
});

</script>

