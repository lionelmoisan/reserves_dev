
        /*--- Afficher le formulaire d'ajout de reserve ---- */
        $("#ajoutReserve").click(function(){
                $("#reservemodal").modal('show');
        }); 
            
            
        /* ----   Validation du formulaire d'ajout de réserve ---*/
        var myLanguage = {
        requiredFields: 'Champ obligatoire',
        badDate: 'Le format de la date est incorrect'
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
        
			