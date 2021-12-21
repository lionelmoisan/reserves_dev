<?php 
/**
* Projet Utilisateur
*/
class Utilisateur{

    public static function getIdUti($DB,$data=array()) {
        
        $identifiant=$data['identifiant'];
        $email=$data['email'];
        $password=$data['password'];
    
        $sql_get_uti_id= $DB->tquery("SELECT u_id from gr_utilisateurs where (u_identifiant=:identifiant && u_password=:password) or (u_email=:email && u_password=:password)",array('identifiant'=>$identifiant,'email'=>$email,'password'=>$password)); 
        
        return $sql_get_uti_id[0]['u_id'];
    }

    public static function getAcquereur($DB,$data=array()) {
           
        $sql_get_uti_acquereur=$DB->tquery("SELECT u_id,u_prenom,u_nom,u_email from gr_utilisateurs,gr_proj_uti where u_role=:role and pu_p_id=:id_projet and u_id=pu_u_id ORDER BY u_nom",$data);
        
        return $sql_get_uti_acquereur;
    
    }

    public static function getLocataire($DB,$data=array()) {

        $sql_get_uti_acquereur=$DB->tquery("SELECT u_id,u_prenom,u_nom,u_email from gr_utilisateurs,gr_proj_uti where u_role=:role and pu_p_id=:id_projet and u_id=pu_u_id ORDER BY u_nom",$data);

        return $sql_get_uti_acquereur;
    
    }
    
    public static function getEntreprise($DB,$data=array()) {
           
        $sql_get_uti_entreprise=$DB->tquery("SELECT u_id,u_prenom,u_nom,u_entreprise,u_email from gr_utilisateurs,gr_proj_uti where u_role=:role and pu_p_id=:id_projet and u_id=pu_u_id ORDER BY u_nom",$data);
        
        return $sql_get_uti_entreprise;
    
    }
    
    public static function getContact($DB,$data=array()) {
           
        $sql_get_contact=$DB->tquery("SELECT u_id,u_prenom,u_nom,u_email,u_societe from gr_utilisateurs,gr_proj_uti WHERE pu_p_id=:id_projet and u_id=pu_u_id AND u_role <> 1 AND u_role <> 4 AND u_role <> 5 AND u_role <> 6 ORDER BY u_nom",$data);
        
        return $sql_get_contact;
    
    }
    
    public static function getAllUtiOrAdmin($DB,$data=array()) {
    
    /* recherche la liste des users*/    
    $sql_get_all_utilisateurs = $DB->tquery('SELECT u_id,u_identifiant,u_prenom,u_nom,u_societe,u_entreprise,u_email,u_password,u_adresse,u_ville,u_cp,u_portable_1,u_telephone,u_portable_2,u_actif,u_role FROM gr_utilisateurs,gr_proj_uti WHERE pu_p_id=:id_projet and u_id=pu_u_id AND u_role <> 1 ORDER BY u_nom, u_prenom, u_entreprise',$data);
        
        return $sql_get_all_utilisateurs;
        
    }
    
    public static function getAllUtiWithAdmin($DB,$data=array()) {
    
    /* recherche la liste des users*/    
    $sql_get_all_utilisateurs = $DB->tquery('SELECT u_id,u_identifiant,u_prenom,u_nom,u_societe,u_entreprise,u_email,u_password,u_adresse,u_ville,u_cp,u_portable_1,u_telephone,u_portable_2,u_actif,u_role FROM gr_utilisateurs,gr_proj_uti WHERE pu_p_id=:id_projet and u_id=pu_u_id ORDER BY u_nom, u_prenom, u_entreprise',$data);
        
        return $sql_get_all_utilisateurs;
        
    }
    
    public static function getAllUtiforAdmin($DB) {
    
        /* recherche la liste des users*/    
    $sql_get_all_uti_for_admin = $DB->tquery('SELECT u_id,u_identifiant,u_prenom,u_nom,u_email,u_password,u_adresse,u_ville,u_cp,u_portable_1,u_telephone,u_portable_2,u_actif,u_role FROM gr_utilisateurs  WHERE u_role in (1,2,3,6) ORDER BY u_nom');
        
        return $sql_get_all_uti_for_admin;

    }
     
    public static function getUtilisateurById($DB,$id) {
        
        $sql_utilisateur_by_id= $DB->query("SELECT u_identifiant,u_prenom,u_nom,u_societe,u_entreprise,u_email,u_password,u_adresse,u_ville,u_cp,u_portable_1,u_telephone,u_portable_2,u_token,u_actif,u_role from gr_utilisateurs where u_id=".$id);
            
        return $sql_utilisateur_by_id;
        
    }

    public static function getUtilisateurByEmail($DB,$email) {


        $sql_utilisateur_by_email= $DB->query("SELECT u_identifiant,u_prenom,u_nom,u_entreprise,u_email,u_password,u_adresse,u_ville,u_cp,u_portable_1,u_telephone,u_portable_2,u_token,u_role from gr_utilisateurs where u_email='".$email."'");


        return $sql_utilisateur_by_email;


    }
    
    public static function getUtilisateurProjets($DB,$id) {
    
        $sql_get_utilisateur_projets = $DB->tquery("select p_id, p_description from gr_projets,gr_proj_uti where pu_p_id = p_id and pu_u_id=".$id);
        
        return $sql_get_utilisateur_projets;
        
    }

    public static function generer_mot_de_passe($nb_caractere = 8) {
        $mot_de_passe = "";
       
        $chaine = "abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ023456789+@!$%?&";
        $longeur_chaine = strlen($chaine);
       
        for($i = 1; $i <= $nb_caractere; $i++)
        {
            $place_aleatoire = mt_rand(0,($longeur_chaine-1));
            $mot_de_passe .= $chaine[$place_aleatoire];
        }

        return $mot_de_passe;   
    }
    
    /* En standby */
    public static function VerifyUserIsdelete($DB,$id_user) {
                
        $sql_rch_uti_is_lots=$DB->query("SELECT l_id FROM gr_lots WHERE l_id_acquereur=".$id_user." OR l_id_locataire=".$id_user." OR l_id_contact=".$id_user );
        
        if(!empty($sql_rch_uti_is_lots)){
				$in_lot='1';
        } else {    
                $in_lot='0';
        }
        
        $sql_rch_uti_is_reserve=$DB->query("SELECT r_id FROM gr_reserves WHERE r_id_entreprise=".$id_user);
        
        if(!empty($sql_rch_uti_is_lots)){
				$in_reserve='1';
        } else {    
                $in_reserve='0';
        }

    }


    public static function AddUtilisateur($DB,$data) {

        $sql_insert_uti = 'INSERT INTO gr_utilisateurs(u_identifiant,u_prenom,u_nom,u_societe,u_entreprise,u_email,u_password,u_adresse,u_ville,u_cp,u_portable_1,u_telephone,u_portable_2,u_token,u_actif,u_role) VALUES (:identifiant,:prenom,:nom,:societe,:entreprise,:email,:password,:adresse,:ville,:cp,:portable_1,:telephone,:portable_2,:token,:actif,:role)';

        $req= $DB->insert($sql_insert_uti,$data);

        return $req;
}


    public static function getUserByIdentifiantActif($DB,$data) {

        $sql_get_uti=$DB->tquery("SELECT pu_p_id,pu_u_id FROM gr_proj_uti,gr_utilisateurs WHERE pu_u_identifiant=:identifiant_user AND pu_u_id !=:id_projet AND pu_u_id=u_id AND u_actif=1",$data);

        return $sql_get_uti[0];

    }
    
}