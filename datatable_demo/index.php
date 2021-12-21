<!DOCTYPE html>
<html>
	<title>Datatable Demo1 | CoderExample</title>
	<head>
		<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		
		<style>
			div.container {
			    margin: 0 auto;
			    max-width:760px;
			}
			div.header {
			    margin: 100px auto;
			    line-height:30px;
			    max-width:760px;
			}
			body {
			    background: #f7f7f7;
			    color: #333;
			    font: 90%/1.45em "Helvetica Neue",HelveticaNeue,Verdana,Arial,Helvetica,sans-serif;
			}
		</style>
	</head>

	<body>
		<div class="header"><h1>DataTable demo (Server side) in Php,Mysql and Ajax </h1></div>
		<div class="container">
            <td><input type="button" data-column="10"  class="allreserve-input-button" value="Toutes les réserves" id="allreserve"></input></td>
                        <td><input type="button" data-column="10"  class="nonleve-input-button" value="Non levée" id=""></input></td>
			<table id="employee-grid"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">        
                <thead>
						<tr>
								<th>r_id</th> 
								<th>r_description</th>
								<th>r_piece</th>
								<th>r_type</th>
								<th>l_numero_lot</th>
								<th>u_nom</th>
								<th>u_email</th>
								<th>u_portable_1</th>
								<th>u_portable_2</th>
								<th>u_telephone</th>
								<th>ls_description</th>
						</tr>
					</thead>
                <!--
                    <thead>
                    <tr>
                        <td><input type="button" data-column="10"  class="allreserve-input-button" value="Toutes les réserves" id="allreserve"></input></td>
                        <td><input type="button" data-column="10"  class="nonleve-input-button" value="Non levée" id=""></input></td>
                    </tr>
                    </thead>-->
                
                    
			</table>
		</div>
        
    <script type="text/javascript" language="javascript" >
			

        $(document).ready(function() {
				var dataTable = $('#employee-grid').DataTable( {
					"processing": true,
					"serverSide": true,
					"ajax":{
						url :"employee-grid-data.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".employee-grid-error").html("");
							$("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#employee-grid_processing").css("display","none");
							
						}
					}
				} );
            
    $('.allreserve-input-button').on( 'keyup click', function () {   // for text boxes
    var i =$(this).attr('data-column');  // getting column index
    var v =$(this).attr('id');  // getting search input value
        dataTable.columns(i).search(v).draw();
} );
            
    $('.nonleve-input-button').on( 'keyup click', function () {   // for text boxes
    var i =$(this).attr('data-column');  // getting column index
    var v =$(this).attr('id');  // getting search input value
        dataTable.columns(i).search(v).draw();
} );
            
            
            
            
			} );
		</script>    
        
        
	</body>
</html>