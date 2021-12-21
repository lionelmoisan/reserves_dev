<?php 
/**
* Gestion de la base de données
*/
class Date{
	
	public static function CalculDateNbrjours($DB,$data) {
		
		$delais=Delais::getDelaisContractuels($DB,$data['id_projet']);
		
		if ($data['type']=="livraison") {
	
			$dateDepartTimestamp= strtotime($data['date']);
						
			$dateFin = date("d-m-Y",strtotime('+'.$delais->dd_nbr_jour_delai_livraison.' days',$dateDepartTimestamp)); 
		
			$date_auformat = str_replace('-', '/', $dateFin);
				
			
		} else {
			
			$dateDepartTimestamp= strtotime($data['date']);
						
			$dateFin = date("d-m-Y",strtotime('+'.$delais->dd_nbr_jour_delai_gpa.' days',$dateDepartTimestamp)); 
		
			$date_auformat = str_replace('-', '/', $dateFin);
			
		}
		
		return $date_auformat;
		
	}

}

