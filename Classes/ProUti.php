<?php 

/**
* Projet uti
*/
class ProjUti{
	    
    public static function deleteProjUti($DB,$id) {
           $nb= $DB->insert('DELETE FROM gr_proj_uti WHERE pu_u_id=:id',array('id'=>$id));
       /* if($nb){
				$_SESSION['message'] = "Catégorie  supprimée avec succès";
			}else{
				$_SESSION['erreur'] = "Un problème est survenu lors de la suppression de la catégorie.";
			}*/
    }
    
    public static function getProUti($DB,$user_email) {

        $sql_get_Pro_uti= $DB->tquery("SELECT pu_p_id,p_description,p_logo_nom from gr_proj_uti,gr_projets where pu_p_id=p_id and pu_u_identifiant=:email ORDER BY p_description",array('email'=>$user_email));
    
		return $sql_get_Pro_uti;
    }


    public static function getProUtiRole($DB,$id_projet,$user_email) {
        
        $sql_get_Pro_uti_role= $DB->tquery("SELECT pu_u_role,pu_u_id FROM gr_proj_uti WHERE pu_p_id=".$id_projet." AND pu_u_identifiant='".$user_email."'");

        return $sql_get_Pro_uti_role[0];

    }
    
    
    public static function insertProjUti($DB,$data) {

        $sql_insert_proj_uti = 'INSERT INTO gr_proj_uti (pu_p_id,pu_u_id,pu_u_identifiant,pu_u_role) VALUES (:id_projet,:id_user,:pro_uti_identifiant,:role)';
                        
        $req=$DB->insert($sql_insert_proj_uti,$data);

        return $req;
    }

    public static function getProUtiEmailbyprojet($DB,$data) {

        $sql_getProUtiEmailbyprojet= $DB->tquery("SELECT pu_u_identifiant FROM gr_proj_uti WHERE pu_p_id=:id_projet AND pu_u_role=:role",$data);

        return $sql_getProUtiEmailbyprojet;

    }

}