<?php require 'includes/includes_back.php';

/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST)){ 
    
    $date_jour = date("Y-m-d H:i:s");
    
    $data = array(
        'id_statut'=>$_POST['statut'],
        'id_reserve'=>$_POST['id_reserve'],
        'date_modifier'=>$date_jour
    );

    /*
    var_dump($_POST['id_statut_old']);
    var_dump($_POST['statut']);
    */


    if ($_POST['statut']<>$_POST['id_statut_old']) {

        /* modifier le statut de la réserve */
        $update_statut_reserve=Reserve::UpdateStatutReserve($DB,$data);


        if ($update_statut_reserve) {
            /* ajouter une ligne dans l'historique */

            $date_jour = date("Y-m-d H:i:s");

                $data_histo = array (
                    'r_id'=> $_POST['id_reserve'],
                    'ls_id'=> $_POST['statut'],
                    'u_id'=> $_SESSION['id_user'],
                    'date_modifier'=> $date_jour
            );

            $insert_histo_statut=Reserve::AddHistoStatut($DB,$data_histo);
            $_SESSION['message']="Le statut d'une réserve a été mis à jour";
            header('location:index.php');
        }

    } else  {
        $_SESSION['message']="Pas de changement : Le statut d'une réserve n'a pas changé";
        header('location:index.php');


    }

} else {
    echo "erreur dans le formulaire";   
}

?>