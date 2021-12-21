<?php require 'includes/includes_back.php';
/**
 * Created by PhpStorm.
 * User: lionelmoisan
 * Date: 10/02/2017
 * Time: 08:17
 */

require 'Classes/Import.php';


/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST['description'])) {

    
    /*
     * 1) rechercher l'id du projet
     */

    $data = array(
        'name'=>addslashes($_POST['description'])
    );
    $projet=Import::getProjetByName($DB,$data);

    if (empty($projet)) {

        $msg_supp_donnees="Le projet n'existe pas";

        header('location:import_tmp.php?msg_supp_donnees='.$msg_supp_donnees);
        exit();

    }

    /*
    * 2) Recherche tous les id des utilisateurs dans la table de correspondance
     *
     */

    $sql_lst_uti_projet = $DB->tquery("SELECT pu_id, pu_u_id FROM gr_proj_uti WHERE pu_u_role <> 1 AND  pu_p_id=".$projet[0]->p_id);

    /*
    * Supprimer les utilisateurs de la table gr_utilisateurs
    * Supprimer les utilisateurs de la table de correspondance
    */

  foreach ($sql_lst_uti_projet as $utilisateur) {
      $supp_ref_uti_projets=ProjUti::deleteProjUti($DB,$utilisateur['pu_u_id']);
  }

  foreach ($sql_lst_uti_projet as $utilisateur) {
      $supp_uti=$DB->insert("DELETE FROM gr_utilisateurs WHERE u_id=".$utilisateur['pu_u_id']);
  }


    /*
     * 3) rechercher tous les id des lots du projet
     * Select l_id FROM gr_lots WHERE l_id_projet = id_projet
     * Stocker les id dans un tableau
     */

    $lst_lots=lot::getLots($DB,$projet[0]->p_id);

    
    

    /*
     * 4) Recherche toutes les réserves du projet
     * SELECT r_id FROM gr_reserves WHERE r_id_lot=id_lot
     * Stocker les r_id dans un tableau
     */

    $lst_reserves=Reserve::getAllReserve($DB,$projet[0]->p_id);

    
    
    /*
     * 4-1) Rechercher toutes les remarques des réserves
     * SELECT rr_id FROM gr_res_rem WHERE rr_r_id = r_id
     * Stocker les rr_id dans un tableau
     */

    /*
    * 4-2) Rechercher tous les historiques des remarques
    * SELECT rsh_id FROM gr_res_sta_histo WHERE rsh_r_id = r_id
    * Stocker les rsh_id dans un tableau
    */


    foreach ($lst_reserves as $reserve) {

       //var_dump($reserve['r_id']);

        $lst_histo_statut=Reserve::GetHistoStatut($DB,$reserve['r_id']);

        //var_dump($lst_histo_statut);

        if (!empty($lst_histo_statut)) {

            $supp_histo_statut=$DB->insert("DELETE FROM gr_res_sta_histo WHERE rsh_r_id=".$reserve['r_id']);

        }


        $lst_res_rem=Reserve::GetResRemById($DB,$reserve['r_id']);

        //var_dump($lst_res_rem);

        if (!empty($lst_res_rem)) {

            $supp_res_rem=$DB->insert("DELETE FROM gr_res_rem WHERE rr_r_id=".$reserve['r_id']);
        }


        // Effacer les réserves du projets
        $supp_reserves=$DB->insert("DELETE FROM gr_reserves WHERE r_id=".$reserve['r_id']);

    }

    // Effacer les lots du projets

    foreach ($lst_lots as $lot) {

        $supp_lot=$DB->insert("DELETE FROM gr_lots WHERE l_id=".$lot['l_id']);

    }

    $msg_supp_donnees="OK";

    unset($_SESSION['msg_err_users']);
    unset($_SESSION['msg_err_lot']);
    unset($_SESSION['msg_err_reserve']);

    header('location:import_tmp.php?msg_supp_donnees='.$msg_supp_donnees);
    exit();

    
} else {

    $msg_supp_donnees="Veuillez saisir un nom de projet";

    header('location:import_tmp.php?msg_supp_donnees='.$msg_supp_donnees);
    exit();

}