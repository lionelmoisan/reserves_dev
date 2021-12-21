<?php require 'includes/includes_back.php';

$timestamp_debut = microtime(true);

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
    3=>'entreprise.u_nom',
    4=>'r_type',
    5=>'l_numero_lot',
    6=>'contact.u_nom',
    7=>'ls_description',
    8=>'r_date_signalement'
);

// getting total number records without any search
$sql = "SELECT r_id,r_description,r_piece,r_type,r_date_signalement,r_id_entreprise,l_numero_lot,";
$sql.= "entreprise.u_entreprise as EntrepriseNom,entreprise.u_email as EntrepriseEmail,entreprise.u_portable_1 as EntreprisePortable,";
$sql.= "contact.u_nom,contact.u_email,contact.u_portable_1,contact.u_portable_2,contact.u_telephone,ls_description,r_ls_id, ";
$sql.= "(SELECT count(*) as nbr_total_rem FROM gr_res_rem WHERE rr_r_id=r_id) as NBR_REM, ";
$sql.= "(SELECT count(*) as nbr_total_rem  FROM gr_res_rem WHERE rr_r_id = r_id AND rr_date >= DATE_ADD( NOW(), INTERVAL -15 DAY) and rr_u_id <> ".$_SESSION['id_user'].") as NBR_REM_USER_ID, ";
$sql.= "(SELECT count(*) as nbr_total_res_img FROM gr_res_images where ri_r_id=r_id) as NBR_IMAGE ";
$sql.=" FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot";
$sql.=" LEFT JOIN gr_utilisateurs as contact ON l_id_contact=contact.u_id";
$sql.=" LEFT JOIN gr_utilisateurs as entreprise ON r_id_entreprise=entreprise.u_id";
$sql.=" LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE l_id_projet='".$_SESSION['id_projet']."'";
if (!empty($requestData['columns'][7]['search']['value']) ){
    $sql.="";
} else {
    $sql.=" AND (ls_id=1 OR ls_id=8) ";
}

//var_dump($sql);



$query=mysqli_query($conn, $sql) or die("reserve-grid-data.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT r_id,r_description,r_piece,r_type,r_date_signalement,r_id_entreprise,l_numero_lot,";
$sql.= "entreprise.u_entreprise as EntrepriseNom,entreprise.u_email as EntrepriseEmail,entreprise.u_portable_1 as EntreprisePortable,";
$sql.= "contact.u_nom,contact.u_email,contact.u_portable_1,contact.u_portable_2,contact.u_telephone,ls_description,r_ls_id, ";
$sql.= "(SELECT count(*) as nbr_total_rem FROM gr_res_rem WHERE rr_r_id=r_id) as NBR_REM, ";
$sql.= "(SELECT count(*) as nbr_total_rem  FROM gr_res_rem WHERE rr_r_id = r_id AND rr_date >= DATE_ADD( NOW(), INTERVAL -15 DAY) and rr_u_id <> ".$_SESSION['id_user'].") as NBR_REM_USER_ID, ";
$sql.= "(SELECT count(*) as nbr_total_res_img FROM gr_res_images where ri_r_id=r_id) as NBR_IMAGE ";
$sql.=" FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot";
$sql.=" LEFT JOIN gr_utilisateurs as contact ON l_id_contact=contact.u_id";
$sql.=" LEFT JOIN gr_utilisateurs as entreprise ON r_id_entreprise=entreprise.u_id";
$sql.=" LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE l_id_projet='".$_SESSION['id_projet']."'";
if (!empty($requestData['columns'][7]['search']['value']) ){
    $sql.="";
} else {
    $sql.=" AND (ls_id=1 OR ls_id=8) ";
}

if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $sql.=" AND ( r_description LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR l_numero_lot LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR r_piece LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR r_type LIKE '%".$requestData['search']['value']."%' ";

    $sql.=" OR contact.u_nom LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR contact.u_portable_1 LIKE '%".$requestData['search']['value']."%' ";

    $sql.=" OR entreprise.u_entreprise LIKE '%".$requestData['search']['value']."%' ";
    $sql.=" OR entreprise.u_portable_1 LIKE '%".$requestData['search']['value']."%' ";

    $sql.=" OR ls_description LIKE '%".$requestData['search']['value']."%')";
}

//echo ($sql);



$query=mysqli_query($conn, $sql) or die("reserve-grid-data.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

if ($columns[$requestData['order'][0]['column']] <> 'r_id' ) {

    $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']." ";


} else {

    $sql.=" ORDER BY l_numero_lot ASC, r_type DESC, r_piece ASC , r_description ASC";

}

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */


//var_dump($sql);

$query=mysqli_query($conn, $sql) or die("reserve-grid-data.php: get employees");

$data = array();

while( $row=mysqli_fetch_array($query) ) {  // preparing an array



    if ($row['r_ls_id']<> 1) {

        $date_delai="";

    } else {


        // CALCUL DE LA DATE DE DELAI
        $data_delai = array(
            'id_projet'=>$_SESSION['id_projet'],
            'type'=>$row["r_type"],
            'date'=>$row['r_date_signalement']
        );
        $date_delai=Date::CalculDateNbrjours($DB,$data_delai);
    }


    if($row['NBR_REM'] <> 0) {

        if ($row['NBR_REM_USER_ID'] <> 0) {
            $afficher_nbr_remarques="<i class='fa fa-comments fa-4' style=\"color:red;\" title='Commentaires sur réserve'></i>&nbsp;<span class='badge primary' title='Nombre de commentaires'>".$row['NBR_REM']."</span>";
        } else {
            $afficher_nbr_remarques="<i class='fa fa-comments fa-4' title='Commentaires sur réserve'></i>&nbsp;<span class='badge primary' title='Nombre de commentaires'>".$row['NBR_REM']."</span>";

        }

    } else {
        $afficher_nbr_remarques="<i class='fa fa-comments fa-4'  title='Commentaires sur réserve'></i>";
    }



    if ($row['NBR_IMAGE'] <> 0) {

        $afficher_img="<i class=\"fa fa-camera fa-4\" aria-hidden=\"true\"></i>";

    } else {

        $afficher_img="";

    }

    $bt_action_info = "<button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#Modaldetaillot' id=".$row['r_id']."><i class='fa fa-info fa-4' title='Informations Générales'></i>&nbsp;".$afficher_img."</button>";

    $bt_action_remarques = "<button type='button' class='btn btn-info btn-sm' data-toggle='modal' data-target='#ModalResRemarque' id=".$row['r_id'].">".$afficher_nbr_remarques."</button>";

    if ($row['r_ls_id']== 8) {

        $bt_action_maj_statut = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#ModalSignalementGPA' id=".$row['r_id']."><i class='fa fa-check-square fa-4' title='Changer le statut'></i></button>";

    } else {

        $bt_action_maj_statut = "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#ModalHistoriquesstatut' id=".$row['r_id']."><i class='fa fa-check-square fa-4' title='Changer le statut'></i></button>";

    }

    $bt_action_up_reserve ="<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#ModalUpdateReserve' id=".$row['r_id']."><i class='fa fa-pencil fa-4' title='Changer le statut'></i></button>";


//var_dump($row['EntrepriseNom']);

    $nestedData=array();
    $nestedData[] = $row["l_numero_lot"];
    $nestedData[] = stripslashes($row["r_description"]);
    $nestedData[] = stripslashes($row["r_piece"]);
    $nestedData[] =  "<a href='mailto:".$row['EntrepriseEmail']."'>".$row['EntrepriseNom']."</a></br>\r\n".$row['EntreprisePortable'];
    $nestedData[] = $row["r_type"];
    $nestedData[] = $date_delai;
    $nestedData[] = "<a href='mailto:".$row["u_email"]."'>".$row["u_nom"]."</a></br>\r\n".$row["u_portable_1"];
    $nestedData[] = $row["ls_description"];
    $nestedData[] = $bt_action_info." ".$bt_action_remarques." ".$bt_action_maj_statut." ".$bt_action_up_reserve;
    $nestedData[] = "";
    $nestedData[] = "";


    $data[] = $nestedData;


}



//var_dump($data);



$json_data = array(
    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal"    => intval( $totalData ),  // total number of records
    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
);


//print_r($json_data);

//$timestamp_fin = microtime(true);

//$difference_ms = $timestamp_fin - $timestamp_debut;

//echo 'Exécution du script : ' . $difference_ms . ' secondes.';

echo json_encode($json_data);  // send data as json format


?>