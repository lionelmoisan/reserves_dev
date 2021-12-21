<?php 
/**
* Gestion des delais contractuels
*/
class Delais{
	
	/* GETTER DES DELAIS CONTRACTUELS */
	public static function getDelaisContractuels ($DB,$id_projet) {
	
    $sql_get_delais_contractuels = $DB->query('SELECT dd_nbr_jour_delai_livraison,dd_nbr_jour_delai_gpa FROM gr_date_delais WHERE dd_p_id='.$id_projet);
		
	return $sql_get_delais_contractuels[0];
		
	}
	
	
	/* SETTER DES DELAIS CONTRACTUELS*/
	public static function setDelaisContractuels($DB,$data) {
		
		$sql_insert_delais_contractuels = 'INSERT INTO gr_date_delais (dd_p_id,dd_nbr_jour_delai_livraison,dd_nbr_jour_delai_gpa) VALUES (:id_projet,:nbr_jour_livraison,:nbr_jour_gpa)';
                        
    $req=$DB->insert($sql_insert_delais_contractuels,$data);

        
       if($req){
		   return true; 
        }else{
		   return false;
        } 	
	}
	
	/* SETTER UPDATE DES DELAIS CONTRACTUELS*/
	public static function setUpdateDelaisContractuels($DB,$data) {
		$rep_update_delais_contractuels = $DB->insert("UPDATE gr_date_delais SET dd_nbr_jour_delai_livraison=:nbr_jour_livraison,dd_nbr_jour_delai_gpa=:nbr_jour_gpa WHERE dd_p_id=:id_projet",$data);
        
        return $rep_update_delais_contractuels;
	}
			
}

