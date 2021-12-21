<?php 
/**
* Projet Utilisateur
*/
class Statistiques{
    
    
    
    public static function nbr_res_by_proj($DB,$id_projet) {
           
    $sql_get_nbr_resbyproj=$DB->tquery("SELECT count(*) as nbr_total_res
FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id
LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE ls_id=1 AND l_id_projet=".$id_projet);
        
    return $sql_get_nbr_resbyproj[0];
    
    }
    
    
    
    public static function lst_res_by_proj($DB,$id_projet) {
           
    $sql_get_lst_resbyproj=$DB->tquery("SELECT r_id,r_description,r_piece,r_type,l_numero_lot,ls_description
FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id
LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE ls_id=1 AND l_id_projet=".$id_projet);
        
    return $sql_get_lst_resbyproj;
    
    }
    
     public static function cpt_res_ent($DB,$data) {
           
    $sql_get_lst_resbyproj=$DB->tquery("SELECT r_id_entreprise,COUNT(*) as nbr FROM gr_reserves,gr_lots,gr_projets
WHERE r_ls_id=:id_statut AND r_id_lot=l_id AND l_id_projet = p_id AND p_id=:id_projet GROUP BY r_id_entreprise",$data);
        
    return $sql_get_lst_resbyproj;
    
    }
    


    public static function cpt_res_by_type($DB,$data) {

    $sql_get_nbr_res=$DB->tquery("SELECT count(*) as nbr_total_res
FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
 WHERE r_type=:type AND l_id_projet=:id_projet",$data);

    return $sql_get_nbr_res;

    }

    // NOMBRE DE RESERVE  NON LEVE POUR LE PROJET
    public static function nbr_res_non_leve($DB,$date) {
        $sql_res_leve_date="SELECT count(*) as nbr_res_leve FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
        WHERE r_type='livraison' AND l_id_projet=".$_SESSION['id_projet']." AND (r_ls_id <> 1 AND r_ls_id <> 7) AND r_date_modifier < '".$date."'";

        $req_res_leve_date=$DB->tquery($sql_res_leve_date);

        return $req_res_leve_date;

    }


    public static function cpt_res_total_liv($DB,$date) {

        // NOMBRE DE RESERVE TOTAL DE TYPE LIVRAISON POUR LE PROJET
$sql_total_res_livraison_date=" SELECT count(*) as nbr_total_res
FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot
WHERE r_type='livraison' AND l_id_projet=".$_SESSION['id_projet']." AND r_ls_id <> 7 AND r_date_modifier < '".$date."'";

        $req_total_res_liv_date=$DB->tquery($sql_total_res_livraison_date);

        return $req_total_res_liv_date;

    }


 
}
