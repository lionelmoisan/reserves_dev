<?php 

/**
* Lot
*/
class lot{

    public static function getLots($DB,$id_projet) {

        /* recherche la liste des lots*/    
        $lst_lots = $DB->tquery("SELECT l_id,l_numero_lot,DATE_FORMAT(l_date_livraison, '%d/%m/%Y') AS l_date_livraison,DATE_FORMAT(l_date_reception, '%d/%m/%Y') AS l_date_reception,l_id_acquereur,l_id_locataire,l_id_contact FROM gr_lots where l_id_projet=".$id_projet." ORDER BY l_numero_lot");
    
        return $lst_lots;
    }

    public static function getLotById($DB,$id) {
        
        /* recherche d'un lot  */    
$lot = $DB->query("SELECT l_id,l_numero_lot,DATE_FORMAT(l_date_livraison, '%d/%m/%Y') AS l_date_livraison,DATE_FORMAT(l_date_reception, '%d/%m/%Y') AS l_date_reception,l_id_acquereur,l_id_locataire,l_choix_contact,l_id_contact FROM gr_lots where l_id=".$id."");

        return $lot[0];
    }
            
    
    public static function Majlot($DB,$data=array()) {
        /*---- Mise à jour des données d'un lot----*/
        $rep_update_lot = $DB->insert("UPDATE gr_lots SET l_numero_lot=:numero_lot,l_date_livraison=:date_livraison,l_date_reception=:date_reception,l_id_acquereur=:id_acquereur,l_id_locataire=:id_locataire,l_choix_contact=:choix_contact,l_id_contact=:id_contact WHERE l_id=:id",$data);
        
        return $rep_update_lot;

    }


    public static function Getlotdatefirst($DB,$id_projet) {

        $lot = $DB->tquery("SELECT date_format(l_date_livraison,'%Y-%m-%d') as date_livraison FROM gr_lots WHERE l_id_projet=".$id_projet." ORDER BY l_date_livraison ASC LIMIT 1");

        return $lot;

    }



    public static function getLotsByacquereur($DB,$id_projet,$id_acquereur) {

        /* recherche la liste des lots*/
        $lst_lots = $DB->tquery("SELECT l_id,l_numero_lot,DATE_FORMAT(l_date_livraison, '%d/%m/%Y') AS l_date_livraison,DATE_FORMAT(l_date_reception, '%d/%m/%Y') AS l_date_reception,l_id_acquereur,l_id_locataire,l_id_contact FROM gr_lots where l_id_projet=".$id_projet." AND l_id_acquereur=".$id_acquereur." ORDER BY l_numero_lot");

        return $lst_lots;
    }
}