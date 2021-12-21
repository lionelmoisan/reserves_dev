<?php require 'includes/includes_back.php';
require_once 'Classes/Statistiques.php';

// NOMBRE DE RESERVE TOTAL NON LEVE POUR LE PROJET
$nbr_reserve=Statistiques::nbr_res_by_proj($DB,$_SESSION['id_projet']);  

//echo $nbr_reserve['nbr_total_res'];
    
// LISTE DES RESERVES DU PROJET NON LEVE
$lst_reserve=Statistiques::lst_res_by_proj($DB,$_SESSION['id_projet']);


$data=array(
    'id_statut'=>1,
    'id_projet'=>$_SESSION['id_projet']
);

$lst_nbr_resbyentre=Statistiques::cpt_res_ent($DB,$data);


function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}


$data = array();
foreach ($lst_nbr_resbyentre as $entreprise) {
    
    $info_utilisateur=Utilisateur::getUtilisateurById($DB,$entreprise['r_id_entreprise']);

    //print_r($info_utilisateur);   
    
    //echo $info_utilisateur[0]->u_nom;
    $nestedData=array(); 
	$nestedData['label'] = utf8_decode($info_utilisateur[0]->u_nom);
    $nestedData['data'] = $entreprise['nbr'];
    $nestedData['color'] = "#".random_color();
    
    
    $data[] = $nestedData;
    
}    
/*
print_r($data);
echo "<br><br>";


$arr=array(

    array(
        'label'=>"serie1",
        'data'=>10,
        'color'=>"#00FFFF"
        ),
    array(
        "label"=>"serie2",
        "data"=>20,
        "color"=>"#0000FF"
        ),
    array(
        "label"=>"serie3",
        "data"=>30,
        "color"=>"#C0C0C0"
        ),
    array(
        "label"=>"serie4",
        "data"=>15,
        "color"=>"#800080"
        ),
);
print_r($arr);
*/

echo json_encode( $data );









?>