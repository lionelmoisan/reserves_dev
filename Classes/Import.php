<?php
/**
 * Projet Import
 */
class Import{
    

    public static function uploadfileImport ($data_file) {

        if ($data_file["error"] > 0) {
            $tab_info_file = array(
                'nom_logo' => '',
                'message' => 'Erreur lors du transfert du fichier'
            );

            return $tab_info_file;
        } else {

            $extensions_valides = array( 'csv');
            $extension_upload = strtolower(  substr( strrchr($data_file["name"], '.')  ,1)  );

            if (in_array($extension_upload,$extensions_valides) ) {

                $tab = explode(".", $data_file["name"]);

                $fichier= $tab[0];

                $nom = "Fichiers/CSV/{$fichier}.{$extension_upload}";

                $resultat = move_uploaded_file($data_file["tmp_name"],$nom);

                if ($resultat) {

                    $tab_info_file = array(
                        'nom_fichier' => $fichier.".".$extension_upload,
                        'message' => NULL
                    );

                    return $tab_info_file;

                } else {
                    $tab_info_file = array(
                        'nom_fichier' => '',
                        'message' => 'Erreur lors du transfert du fichier'
                    );

                    return $tab_info_file;
                }

            } else {
                $tab_info_file = array(
                    'nom_fichier' => '',
                    'message' => 'Le fichier doit avoir comme extension CSV'
                );

                return $tab_info_file;
            }

        }

        

    }

    public static function getProjetByName($DB,$data) {

        $sql_projet= $DB->query("SELECT p_id FROM gr_projets where p_description=:name",$data);

        return $sql_projet;

    }


    public static function getRoleByName($DB,$data){

        $sql_role= $DB->query("SELECT r_id FROM gr_roles where r_description=:role",$data);

        return $sql_role;

    }


    public static function getlotByName($DB,$data){

        $sql_lot= $DB->query("SELECT l_id FROM gr_lots where l_numero_lot=:numero_lot AND l_id_projet=:id_projet",$data);

        return $sql_lot;

    }


    public static function getUserByNameProjetRole($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT u_id FROM gr_utilisateurs,gr_proj_uti WHERE u_role=:role AND pu_p_id=:id_projet AND u_nom=:nom_user AND u_id=pu_u_id ORDER BY u_nom",$data);

        return $sql_get_uti[0];

    }



    public static function getUserByEmailProjetRole($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT u_id FROM gr_utilisateurs,gr_proj_uti WHERE u_role=:role AND pu_p_id=:id_projet AND u_email=:email_user AND u_id=pu_u_id ORDER BY u_nom",$data);

        return $sql_get_uti[0];

    }

    public static function getUserByIdentifiantProjetRole($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT u_id,u_password,u_actif FROM gr_utilisateurs,gr_proj_uti WHERE u_role=:role AND pu_p_id=:id_projet AND u_identifiant=:identifiant_user AND u_id=pu_u_id ORDER BY u_nom",$data);

        return $sql_get_uti[0];

    }

    public static function getUserByIdentifiant($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT pu_p_id,pu_u_id FROM gr_proj_uti WHERE pu_u_identifiant=:identifiant_user AND pu_u_id !=:id_projet",$data);

        return $sql_get_uti[0];

    }




    public static function getEntrepriseByEmailProjetRole($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT u_id FROM gr_utilisateurs,gr_proj_uti WHERE u_role=:role AND pu_p_id=:id_projet AND u_email=:email_entreprise AND u_id=pu_u_id ORDER BY u_nom",$data);

        return $sql_get_uti[0];

    }
    
    
    

    public static function getUserByNameProjet($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT u_id FROM gr_utilisateurs,gr_proj_uti WHERE pu_p_id=:id_projet AND u_nom=:nom_user AND u_id=pu_u_id ORDER BY u_nom",$data);

        return $sql_get_uti[0];

    }

    public static function getUserByEmailProjet($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT u_id FROM gr_utilisateurs,gr_proj_uti WHERE pu_p_id=:id_projet AND u_email=:email_user AND u_id=pu_u_id ORDER BY u_nom",$data);

        return $sql_get_uti[0];

    }


    public static function getStatutByName($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT ls_id FROM gr_lst_statut WHERE ls_description=:nom_statut",$data);

        return $sql_get_uti[0];

    }

}