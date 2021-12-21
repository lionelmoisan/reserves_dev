<?php require 'includes/includes_back.php';
require_once 'Classes/Statistiques.php';

/* --- Recherche de la date de 1er livraison pour le projet ---*/
$infoslot=Lot::Getlotdatefirst($DB,$_SESSION['id_projet']);

//var_dump($infoslot);

$date_first_lot=$infoslot[0]['date_livraison'];

$datedujour = new DateTime();

$date_premiere_livraison = new DateTime($infoslot[0]['date_livraison']);

//var_dump($datedujour);
//var_dump($date_premiere_livraison);

$interval = $date_premiere_livraison->diff($datedujour);
$nbrjour= $interval->format('%a');

//var_dump($nbrjour);

//$nbrjour=30;

// CALUL DU NOMBRE DE POINT DE REFERENCE EN FONCTION DE L'INTERVALLE ENTRE LA DATE DE DEBUT ET AUJOURD'HUI
switch ($nbrjour) {
	case ($nbrjour < 4):
		$rchbyxjour='PT2H';
		break;
	case ($nbrjour < 15):
		$rchbyxjour='P2D';
		break;
	case ($nbrjour < 30):
		$rchbyxjour='P2D';
		break;
	case ($nbrjour < 45):
		$rchbyxjour='P2D';
		break;
	case ($nbrjour < 90):
		$rchbyxjour='P2D';
		break;
	case ($nbrjour < 180):
		$rchbyxjour='P4D';
		break;
	case ($nbrjour > 180):
		$rchbyxjour='P16D';
		break;
}

//var_dump("echelle ".$rchbyxjour);

$data=array();
$tab=array();
$i=0;
$etat='nok';

while ($etat<>'ok') {

	$nestedData=array(); 

	//var_dump($i);

	// La premier
	if ($i==0) {

		$date = new DateTime($dateDebut);

		$nestedData['period']=$date_premiere_livraison->format('U')*1000;
		$nestedData['nbr_res_leve']=0;

		$date_premiere_livraison->add(new DateInterval($rchbyxjour));
		$tab[$i]=$date_premiere_livraison->format('Y-m-d H:i:s');

		//var_dump($date_premiere_livraison);

		//$nestedData[]=$date_premiere_livraison->format('U')*1000;


	// NOMBRE DE RESERVE TOTAL DE TYPE LIVRAISON POUR LE PROJET
$sql_total_res_livraison_date=" SELECT count(*) as nbr_total_res
FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
 WHERE r_type='livraison' AND l_id_projet=".$_SESSION['id_projet']." AND r_ls_id <> 7 AND r_type='livraison'";

$req_total_res_livraison_date=$DB->tquery($sql_total_res_livraison_date);

		//var_dump($sql_total_res_livraison_date);


// NOMBRE DE RESERVE TOTAL NON LEVE POUR LE PROJET
$sql_res_leve_date="SELECT count(*) as nbr_res_leve
FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
WHERE r_type='livraison' AND l_id_projet=".$_SESSION['id_projet']." AND (r_ls_id <> 1 AND r_ls_id <> 7) AND r_type='livraison' AND r_date_modifier < '".$tab[$i]."'";

$req_res_leve_date=$DB->tquery($sql_res_leve_date);

		//var_dump($sql_res_leve_date);

		if (($req_res_leve_date[0]['nbr_res_leve']<>0) && ($req_total_res_livraison_date[0]['nbr_total_res'])) {

			//$nestedData['nbr_res_leve']=round((($req_res_leve_date[0]['nbr_res_leve']/$req_total_res_livraison_date[0]['nbr_total_res'])*100));
		}



	} else {

		//$date_du_jour_test = new DateTime();

		//$date_du_jour_test=$date_du_jour_test->format('Y-m-d H:i:s');


		$date = new DateTime($tab[$i-1]);
		$date->add(new DateInterval($rchbyxjour));
		$tab[$i]=$date->format('Y-m-d H:i:s');
		
		$nestedData['period']=$date->format('U')*1000;
		// pour vérifier
		//$nestedData[]=$date->format('Y-m-d H:i:s');


	// NOMBRE DE RESERVE TOTAL NON LEVE POUR LE PROJET
	$sql_total_res_livraison_date=" SELECT count(*) as nbr_total_res
	FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
 	WHERE r_type='livraison' AND l_id_projet=".$_SESSION['id_projet']." AND r_ls_id <> 7 AND r_type='livraison'";


	//var_dump($sql_total_res_livraison_date);

	$req_total_res_livraison_date=$DB->tquery($sql_total_res_livraison_date);


	// NOMBRE DE RESERVE TOTAL LEVE POUR LE PROJET
	$sql_res_leve_date="SELECT count(*) as nbr_res_leve
	FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
	WHERE r_type='livraison' AND l_id_projet=".$_SESSION['id_projet']." AND (r_ls_id <> 1 AND r_ls_id <> 7) AND r_type='livraison' AND r_date_modifier < '".$tab[$i]."'";

	$req_res_leve_date=$DB->tquery($sql_res_leve_date);

		//var_dump("Leve",$sql_res_leve_date);

		//var_dump($req_res_leve_date[0]['nbr_res_leve']);
		//var_dump($req_total_res_livraison_date[0]['nbr_total_res']);

		if  ($req_total_res_livraison_date[0]['nbr_total_res']==0) {

			$nestedData['nbr_res_leve']=0;
		} else {

			$nestedData['nbr_res_leve'] = round((($req_res_leve_date[0]['nbr_res_leve'] / $req_total_res_livraison_date[0]['nbr_total_res']) * 100));
		}

		//var_dump($nestedData['nbr_res_leve']);

	}

	$date_1 = new DateTime();
 	$date_2 = new DateTime($tab[$i]);

	//var_dump($nestedData);
	//var_dump($sql_total_res_livraison_date);
	//var_dump($sql_res_leve_date);
	//var_dump($date_2);

	if ($date_2 > $date_1) {

		// Calcul de la date du jour

		$date_du_jour = new DateTime();

		$nestedData['period']=$date_du_jour->format('U')*1000;
		// pour vérifier
		//$nestedData[]=$date->format('Y-m-d H:i:s');


		// NOMBRE DE RESERVE TOTAL NON LEVE POUR LE PROJET
		$sql_total_res_livraison_date=" SELECT count(*) as nbr_total_res
	FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
 	WHERE r_type='livraison' AND l_id_projet=".$_SESSION['id_projet']." AND r_ls_id <> 7 AND r_type='livraison'";


		$req_total_res_livraison_date=$DB->tquery($sql_total_res_livraison_date);


		// NOMBRE DE RESERVE TOTAL LEVE POUR LE PROJET
		$sql_res_leve_date="SELECT count(*) as nbr_res_leve
	FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
	WHERE r_type='livraison' AND l_id_projet=".$_SESSION['id_projet']." AND (r_ls_id <> 1 AND r_ls_id <> 7) AND r_type='livraison' AND r_date_modifier < '".$tab[$i]."'";

		$req_res_leve_date=$DB->tquery($sql_res_leve_date);

		//var_dump($req_res_leve_date[0]['nbr_res_leve']);
		//var_dump($req_total_res_livraison_date[0]['nbr_total_res']);

		if  ($req_total_res_livraison_date[0]['nbr_total_res']==0) {

			$nestedData['nbr_res_leve']=0;
		} else {

			$nestedData['nbr_res_leve'] = round((($req_res_leve_date[0]['nbr_res_leve'] / $req_total_res_livraison_date[0]['nbr_total_res']) * 100));
		}

		$data[] = $nestedData;


		$etat="ok";
		break;
	} else {
		$etat="nok";
	}
	$i++;

	$data[] = $nestedData;

}

$json_data = $data;// total data array

//var_dump($data);

echo json_encode( $json_data ); ?>