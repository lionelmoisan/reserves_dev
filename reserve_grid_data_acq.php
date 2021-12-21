<?php require 'includes/includes_back.php';

/* Database connection start */
$conn = mysqli_connect(HOST, USER, PWD, DBNAME) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$requestData['start']=0;
$requestData['length']=5000;


$columns = array( 
// datatable column index  => database column name
	0 =>'r_id', 
	1 =>'r_description',
	2=>'r_piece',
	3=>'r_type',
	4=>'l_numero_lot',
	5=>'u_nom',
	6=>'u_email',
	7=>'u_portable_1',
	8=>'u_portable_2',
	9=>'u_telephone',
	10=>'ls_description',
    11=>'actions', 
);



// getting total number records without any search
$sql = "SELECT r_id,r_description,r_piece,r_type,r_date_signalement,l_numero_lot,u_nom,u_email,u_portable_1,u_portable_2,u_telephone,ls_description,r_ls_id ";
$sql.=" FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id";
$sql.=" LEFT JOIN gr_lst_statut ON ls_id = r_ls_id ";
$sql.=" WHERE l_id_projet='".$_SESSION['id_projet']."' AND l_id_acquereur='".$_SESSION['id_user']."'";
if (!empty($requestData['columns'][7]['search']['value']) ){
    $sql.=" AND ls_id <> 7";
} else {
    $sql.=" AND (ls_id=1 OR ls_id=8)";
}

//var_dump($sql);

$query=mysqli_query($conn, $sql) or die("reserve-grid-data.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT r_id,r_description,r_piece,r_type,r_date_signalement,l_numero_lot,u_nom,u_email,u_portable_1,u_portable_2,u_telephone,ls_description,r_ls_id ";
$sql.=" FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id";
$sql.=" LEFT JOIN gr_lst_statut ON ls_id = r_ls_id ";
$sql.=" WHERE l_id_projet='".$_SESSION['id_projet']."' AND l_id_acquereur='".$_SESSION['id_user']."'";
if (!empty($requestData['columns'][7]['search']['value']) ){
    $sql.=" AND ls_id <> 7";
} else {
    $sql.=" AND (ls_id=1 OR ls_id=8)";
}
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( r_description LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR l_numero_lot LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR r_piece LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR r_type LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR u_portable_1 LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR ls_description LIKE '%".$requestData['search']['value']."%' "; 
    $sql.=" OR u_nom LIKE '%".$requestData['search']['value']."%' ";

	$sql.=" OR u_portable_1 LIKE '%".$requestData['search']['value']."%')";
}

$query=mysqli_query($conn, $sql) or die("reserve-grid-data.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
//$sql.=" ORDER BY l_numero_lot, r_description, ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

$sql.=" ORDER BY l_numero_lot ASC, r_type DESC, r_piece ASC , r_description ASC ";


/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("reserve-grid-data.php: get employees");


//var_dump($query);

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
    if ($row['r_ls_id']<> 1) {
    
        /* Cas particulier - Signalement d'une GPA */
		if ($row['r_ls_id']==8) {
			$chgt_statut_le=Db::DecodeDate($row['r_date_signalement']);
		} else {

			$lst_statut_historique=Reserve::GetHistoStatut($DB,$row['r_id']);

			//print_r($lst_statut_historique);

			$chgt_statut_le=Db::DecodeDate($lst_statut_historique[0]['rsh_date_modifier']);
			
		}

    } else {
        
           $chgt_statut_le=Db::DecodeDate($row['r_date_signalement']);

    }


	$nbr_images=Reserve::GetNbrResImgById($DB,$row['r_id']);

	if ($nbr_images <> 0) {

		$afficher_img="<i class=\"fa fa-camera fa-4\" aria-hidden=\"true\"></i>";

	} else {

		$afficher_img="";

	}



	$bt_action_info = "<button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#Modaldetaillot' id=".$row['r_id']."><i class='fa fa-info fa-4' title='Informations Générales'></i>&nbsp;".$afficher_img."</button>";

     
    $nestedData=array(); 
	$nestedData[] = $row["l_numero_lot"];
    $nestedData[] = stripslashes($row["r_description"]);
	$nestedData[] = stripslashes($row["r_piece"]);
	$nestedData[] = $row["r_type"];
	$nestedData[] = "<a href='mailto:".$row["u_email"]."'>".$row["u_nom"]."</a></br>".$row["u_portable_1"];
	$nestedData[] = $row["ls_description"];
    $nestedData[] = $chgt_statut_le;
    $nestedData[] = $bt_action_info." ".$bt_action_remarques;
    $nestedData[] = "";
    $nestedData[] = "";
	
	$data[] = $nestedData;
}


$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format


?>
