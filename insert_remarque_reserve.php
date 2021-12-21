<?php require 'includes/includes_back.php';

/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST)){ 
    
    $date_jour = date("Y-m-d H:i:s");
	
	$remarque=addslashes($_POST['remarque']);
    
    $data = array(
        'r_id'=>$_POST['id_reserve'],
		'u_id'=>$_SESSION['id_user'],
		'r_date'=>$date_jour,
		'r_remarque'=>$remarque
    );
    
    /* ajouter une remarque dans une réserve */	
	$inser_remarque_reserve=Reserve::AddRemarqueReserve($DB,$data);
   	$_SESSION['message']="Un commentaire vient d'être ajouté à une réserve";
    header('location:index.php');
    
	    
} else {
    echo "erreur dans le formulaire";   
}

?>