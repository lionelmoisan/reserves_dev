<?php
/**
* Projet Projets
*/
class Projet {
    
    
     public static function getNomProjet($DB,$id) {
    
        $sql_nom_projet= $DB->query("SELECT p_description,p_logo_nom FROM gr_projets where p_id=:id",array('id'=>$id));
    
        return $sql_nom_projet[0];

     }
    
    public static function getAllProjets($DB) {
        
        $sql_all_projets = $DB->query("SELECT p_id,p_description FROM gr_projets ORDER BY p_description");
        
        return $sql_all_projets;
        
    }


    public static function getProjetByid($DB,$id) {

        $sql_projet= $DB->query("SELECT p_description,p_logo_nom,p_module_GPA FROM gr_projets where p_id=:id",array('id'=>$id));

        if (!empty($sql_projet)){
            return $sql_projet[0];
        } else {
            return "";
        }
    }


    public static function upProjetByid($DB,$data) {

        $req_update_projet = $DB->insert("UPDATE gr_projets set p_description=:p_description,p_logo_nom=:p_logo_nom,p_module_gpa=:module_gpa WHERE p_id=:id",$data);

        if ($req_update_projet) {
            return true;
        } else {
            return false;
        }

    }


    public static function insertProjet($DB,$data) {
        
        $sql_insert_projet = 'INSERT INTO gr_projets (p_description,p_logo_nom,p_module_gpa) VALUES (:p_description,:p_logo_nom,:module_gpa)';

        $req_insert_projet = $DB->insert($sql_insert_projet,$data);

        if(!empty($req_insert_projet)){
            return true;
        } else {
            return false;
        }
        
    }

    
    public static function trtuploadfile ($data_file,$id_projet) {

        if ($data_file["error"] > 0) {
            $tab_info_file = array(
                'nom_logo' => '',
                'message' => 'Erreur lors du transfert du fichier'
            );

            return $tab_info_file;
        } else {

            $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $extension_upload = strtolower(  substr( strrchr($data_file["name"], '.')  ,1)  );

            if (in_array($extension_upload,$extensions_valides) ) {

                $tab = explode(".", $data_file["name"]);

                $fichier= $tab[0]."_".$id_projet;

                $nom = "Fichiers/logo/{$fichier}.{$extension_upload}";

                $resultat = move_uploaded_file($data_file["tmp_name"],$nom);

                if ($resultat) {

                    $tab_info_file = array(
                        'nom_logo' => $fichier.".".$extension_upload,
                        'message' => NULL
                    );

                    return $tab_info_file;

                } else {
                    $tab_info_file = array(
                        'nom_logo' => '',
                        'message' => 'Erreur lors du transfert du fichier'
                    );

                    return $tab_info_file;
                }
            } else {

                $tab_info_file = array(
                    'nom_logo' => '',
                    'message' => 'Le fichier doit avoir comme extension jpg,jpeg,gif,png'
                );

                return $tab_info_file;


            }
        }
    }
}