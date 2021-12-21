<?php
/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "gestion_reserves";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$requestData['start']=0;
$requestData['length']=10;


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
	10=>'ls_description'
);

// getting total number records without any search
$sql = "SELECT r_id,r_description,r_piece,r_type,l_numero_lot,u_nom,u_email,u_portable_1,u_portable_2,u_telephone,ls_description ";
$sql.=" FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id";
$sql.=" LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE l_id_projet=2";
if (!empty($requestData['columns'][10]['search']['value']) ){
$sql.="";    
} else {
$sql.=" AND ls_id=1";
}
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT r_id,r_description,r_piece,r_type,l_numero_lot,u_nom,u_email,u_portable_1,u_portable_2,u_telephone,ls_description ";
$sql.=" FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id";
$sql.=" LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE l_id_projet=2";

if (!empty($requestData['columns'][10]['search']['value']) ){
$sql.="";    
} else {
$sql.=" AND ls_id=1 ";
}

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( r_description LIKE '%".$requestData['search']['value']."%' ";    
	$sql.=" OR ls_description LIKE '%".$requestData['search']['value']."%' )";
}


$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");


$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

    
	$nestedData[] = $row["r_id"];
	$nestedData[] = stripslashes($row["r_description"]);
	$nestedData[] = stripslashes($row["r_piece"]);
	$nestedData[] = $row["r_type"];
	$nestedData[] = $row["l_numero_lot"];
	$nestedData[] = $row["u_nom"];
	$nestedData[] = $row["u_email"];
	$nestedData[] = $row["u_portable_1"];
	$nestedData[] = $row["u_portable_2"];
	$nestedData[] = $row["u_telephone"];
	$nestedData[] = $row["ls_description"];
	
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
