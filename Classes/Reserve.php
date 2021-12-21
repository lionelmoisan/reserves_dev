<?php 
/**
* Réserves
*/
class Reserve{
    
    public static function getAllReserve($DB,$id_projet) {

    /* recherche la liste des infos pour les réserves*/
    $sql_get_all_reserves = $DB->tquery('SELECT r_id,r_description,r_piece,r_type,l_numero_lot,u_nom,u_email,u_portable_1,u_portable_2,u_telephone,ls_description FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE l_id_projet='.$id_projet);
        
        return $sql_get_all_reserves;
        
        if(!empty($sql_get_all_reserves)){
            return true;
        } else {
            return false;
        }
    }
    
    
    public static function GetAllReserveEntreprise($DB,$id_projet,$id_utilisateur) {
      
        /* recherche la liste des infos pour les réserves*/    
    $sql_get_all_reserves_by_role = $DB->tquery('SELECT r_id,r_description,r_piece,r_type,l_numero_lot,u_nom,u_email,u_portable_1,u_portable_2,u_telephone,ls_description FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot INNER JOIN gr_res_ent ON r_id = re_r_id LEFT JOIN gr_utilisateurs ON l_id_contact=u_id LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE l_id_projet='.$id_projet.' AND re_u_id='.$id_utilisateur);
        
        return $sql_get_all_reserves_by_role; 
    }
    
    
    public static function GetAllReserveAcquereur($DB,$id_projet,$id_utilisateur) {
      
        /* recherche la liste des infos pour les réserves*/    
    $sql_get_all_reserves_by_role = $DB->tquery('SELECT r_id,r_description,r_piece,r_type,l_numero_lot,u_nom,u_email,u_portable_1,u_portable_2,u_telephone,ls_description FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id LEFT JOIN gr_lst_statut ON ls_id = r_ls_id WHERE l_id_projet='.$id_projet.' AND l_id_acquereur='.$id_utilisateur);
        
        return $sql_get_all_reserves_by_role; 
    }
    
    
    public static function AddReserve($DB,$data=array()) {
        
        $sql_insert_lot = 'INSERT INTO gr_reserves (r_id_lot,r_description,r_piece,r_type,r_date_signalement,r_date_creation,r_date_modifier,r_ls_id,r_id_entreprise) VALUES (:id_lot,:description,:piece,:type,:date_signalement,:date_creation,:date_modifier,:statut,:id_entreprise)';

        $req_insert_reserve = $DB->insert($sql_insert_lot,$data);
        
        
        
        if(!empty($req_insert_reserve)){
                    return true;
        } else {
            return false;
        }    
    }
    
    
    public static function getIdReserve($DB,$id_reserve) {
    
        /* recherche les données d'un réserve */
        $sql_get_id_reserve= $DB->tquery("SELECT r_id,r_id_lot,r_description,r_piece,r_type,DATE_FORMAT(r_date_signalement, '%d/%m/%Y') AS r_date_signalement,DATE_FORMAT(r_date_creation, '%d/%m/%Y') AS r_date_creation,DATE_FORMAT(r_date_modifier, '%d/%m/%Y') AS r_date_modifier,r_ls_id,r_id_entreprise
 FROM gr_reserves WHERE r_id=".$id_reserve);
        
        return $sql_get_id_reserve[0];

    }

    
    
    public static function GetLstStatut($DB) {
        
        $sql_get_lst_statut = $DB->tquery('SELECT ls_id,ls_description FROM gr_lst_statut WHERE ls_id <> 8 ORDER BY ls_description');
        
        return $sql_get_lst_statut;
    }
    
    
    public static function GetHistoStatut($DB,$id_reserve) {
        
        $sql_get_histo_Statut = $DB->tquery('SELECT rsh_id,rsh_r_id,ls_description,u_prenom,u_nom,rsh_date_modifier FROM gr_res_sta_histo INNER JOIN gr_lst_statut ON rsh_ls_id= ls_id INNER JOIN gr_utilisateurs ON rsh_u_id = u_id WHERE rsh_r_id='.$id_reserve.' ORDER BY rsh_date_modifier DESC');
        
        return $sql_get_histo_Statut;
    }
    
    
    public static function AddHistoStatut($DB,$data) {
        
        $sql_insert_histo_statut = 'INSERT INTO gr_res_sta_histo (rsh_r_id,rsh_ls_id,rsh_u_id,rsh_date_modifier) VALUES (:r_id,:ls_id,:u_id,:date_modifier)';

        $req_insert_histo_statut = $DB->insert($sql_insert_histo_statut,$data);
        
        if(!empty($req_insert_histo_statut)){
                    return true;
        } else {
            return false;
        }    
    }
    
    
    public static function UpdateStatutReserve($DB,$data) {
     
         /*---- Mise à jour du statut d'une réserce----*/
         $rep_update_uti = $DB->insert("UPDATE gr_reserves SET r_ls_id=:id_statut,r_date_modifier=:date_modifier WHERE r_id=:id_reserve",$data);
        
        return $rep_update_uti;
    
        
    }
    
    public static function LastIdreserve($DB) {
        $sql_get_last_id_reserve = $DB->tquery('SELECT r_id FROM gr_reserves ORDER BY r_id DESC limit 1');
        
        return $sql_get_last_id_reserve[0];
        
    }
    
    public static function AddReserveEntr($DB,$reserve_id,$utilisateur_id) {
     
         $data = array(
            'r_id'=>$reserve_id,
            'u_id'=>$utilisateur_id
        );
    
        $sql_insert_res_ent = 'INSERT INTO gr_res_ent (re_r_id,re_u_id) VALUES (:r_id,:u_id)';

        $req_insert_res_ent = $DB->insert($sql_insert_res_ent,$data);
        
        if(!empty($req_insert_res_ent)){
                    return true;
        } else {
            return false;
        }     
    }
    
    public static function GetReserveEntr($DB,$reserve_id) {
          $sql_get_res_ent = $DB->tquery('SELECT re_u_id,u_nom FROM gr_res_ent, gr_utilisateurs WHERE re_u_id=u_id and re_r_id='.$reserve_id);
        
        return $sql_get_res_ent;
        
        
    }
	
	public static function GetResRemById($DB,$reserve_id) {
		
		$sql_get_res_rem = $DB->tquery('SELECT rr_id,u_prenom,u_nom,u_entreprise,rr_u_id,rr_date,rr_remarque FROM gr_res_rem INNER JOIN gr_utilisateurs ON u_id=rr_u_id where rr_r_id='.$reserve_id.' ORDER BY rr_date DESC');

        return $sql_get_res_rem;
      
	}
    
    
    
    public static function GetNbrResRemById($DB,$reserve_id) {
		
		$sql_get_res_rem = $DB->tquery('SELECT count(*) as nbr_total_rem FROM gr_res_rem where rr_r_id='.$reserve_id.' ');

        return $sql_get_res_rem[0][nbr_total_rem];
      
	}
	
	    
	public static function AddRemarqueReserve($DB,$data) {
		
		 $sql_insert_histo_statut = 'INSERT INTO gr_res_rem (rr_r_id,rr_u_id,rr_date,rr_remarque) VALUES (:r_id,:u_id,:r_date,:r_remarque)';

        $req_insert_histo_statut = $DB->insert($sql_insert_histo_statut,$data);
        
        if(!empty($req_insert_histo_statut)){
                    return true;
        } else {
            return false;
        }    
		
	
		
	}


    public static function GetReserveAsChange($DB,$data) {

        $get_reserves_as_change = $DB->tquery('SELECT r_id,r_description,r_piece,r_type,r_date_signalement,r_date_creation,r_date_modifier,r_ls_id FROM gr_reserves WHERE (r_ls_id <> 1) AND (r_ls_id <> 7)  AND (r_ls_id <> 8) AND r_id_lot=:id_lot AND r_date_modifier < NOW() AND r_date_modifier > DATE_ADD( NOW(), INTERVAL -1 DAY)',$data);

        return $get_reserves_as_change;


    }



    public static function trtuploadfile ($data_file,$description){

        if ($data_file["error"] > 0) {
            $tab_info_file = array(
                'nom_image' => '',
                'message' => 'Erreur lors du transfert du fichier'
            );

            return $tab_info_file;
        } else {

            $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $extension_upload = strtolower(  substr( strrchr($data_file["name"], '.')  ,1)  );

            if (in_array($extension_upload,$extensions_valides) ) {


                if ($data_file["size"] > 2048000) {

                    $tab_info_file = array(
                        'nom_image' => '',
                        'message' => 'La taille maximum pour une image est de 2 Mo'
                    );
                    return $tab_info_file;


                } else {

                    // renommage du fichier et enregistrement

                    $nom_image=sprintf('Fichiers/reserves/%s.%s', sha1_file($data_file['tmp_name']), $extension_upload);

                    if (!move_uploaded_file($data_file['tmp_name'],$nom_image)) {
                        throw new RuntimeException('Failed to move uploaded file.');
                    } else {

                        $tab_info_file = array(
                            'nom_image' => $nom_image,
                            'message' => NULL
                        );
                        return $tab_info_file;

                    }

                }


            } else {

                $tab_info_file = array(
                    'nom_image' => '',
                    'message' => 'Le fichier doit avoir comme extension jpg,jpeg,png'
                );

                return $tab_info_file;
            }
        }
    }

    public static function AddResImage($DB,$data) {

        $sql_insert_res_image = 'INSERT INTO gr_res_images (ri_r_id,ri_description,ri_url) VALUES (:id_reserve,:description,:URL)';

        $req_insert_res_image = $DB->insert($sql_insert_res_image,$data);

        if(!empty($req_insert_res_image)){
            return true;
        } else {
            return false;
        }
    }


    public static function GetNbrResImgById($DB,$reserve_id) {

        $sql_get_res_img = $DB->tquery('SELECT count(*) as nbr_total_res_img FROM gr_res_images where ri_r_id='.$reserve_id.' ');

        return $sql_get_res_img[0][nbr_total_res_img];

    }


    public static function GetNbrRemdutidiff($DB,$reserve_id,$user_id) {

        $sql_get_rem_uti_diff = $DB->tquery('SELECT count(*) as nbr_total_rem  FROM gr_res_rem WHERE rr_r_id = '.$reserve_id.' AND rr_date >= DATE_ADD( NOW(), INTERVAL -15 DAY) and rr_u_id <> '.$user_id);

        return $sql_get_rem_uti_diff[0][nbr_total_rem];

    }

    public static function UppReserve($DB,$data=array()) {

        $rep_update_reserve = $DB->insert("UPDATE gr_reserves SET r_id_lot=:id_lot,r_description=:description,r_piece=:piece,r_type=:type,r_date_signalement=:date_signalement,r_date_modifier=:date_modifier,r_id_entreprise=:id_entreprise WHERE r_id=:id_reserve",$data);

        return $rep_update_reserve;

    }

    public static function UppResImage($DB,$data=array()) {

        $rep_update_img_reserve = $DB->insert("UPDATE gr_res_images SET ri_url=:URL WHERE ri_id=:id",$data);

        return $rep_update_img_reserve;

    }

}