<?php 
/**
* Gestion de la base de données
*/
class Db{
	
	private $host=HOST;
	private $name=DBNAME;
	private $user=USER;
	private $pass=PWD;

	private $connexion;

	function __construct($host=null,$name=null,$user=null,$pass=null){
		
		if($host != null){
			$this->host = $host;
			$this->name = $name;
			$this->user = $user;
			$this->pass = $pass;
		}

		try{

			$this->connexion = new PDO('mysql:host='.$this->host.';dbname='.$this->name,
				$this->user,$this->pass,array(
					1002 =>'SET NAMES UTF8',
					PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
					));
			$this->connexion->exec('SET NAMES utf8');
//PDO::MYSQL_ATTR_INIT_COMMAND 
		}catch (PDOException $e){
			//echo 'Erreur : Impossible de se connecter  à la BD !';die();
			echo $e->getMessage();
		}
	}

	/* requete */

	public function query($sql , $data=array()){
		$req = $this->connexion->prepare($sql);
		$req->execute($data);
		return $req->fetchAll(PDO::FETCH_OBJ); 
	}

	public function tquery($sql , $data=array()){
		$req = $this->connexion->prepare($sql);
		$req->execute($data);
		return $req->fetchAll(PDO::FETCH_ASSOC); 
	}


	public function insert($sql , $data=array()){
        $req = $this->connexion->prepare($sql);
		$nb=$req->execute($data);
        return $nb;
	}

	public function uniqueEmail($email){
		$req = $this->connexion->prepare('SELECT count(*) as nbre from gr_utilisateurs WHERE u_email=:email limit 1');
		$req->execute(array('email'=>$email));

		$reponse = $req->fetchAll(PDO::FETCH_ASSOC);
        
		return $reponse[0]['nbre'];

	}
    
    	public function uniqueIdentifiant($identifiant){
		$req = $this->connexion->prepare('SELECT count(*) as nbre from gr_utilisateurs WHERE u_identifiant=:identifiant limit 1');
		$req->execute(array('identifiant'=>$identifiant));

		$reponse = $req->fetchAll(PDO::FETCH_ASSOC);
		return $reponse[0]['nbre'];

	}
    
    
    public function descRole($id){
        $req_des_roles = $this->connexion->prepare('SELECT r_description from gr_roles where r_id=:id limit 1'); 
        $req_des_roles->execute(array('id'=>$id));
        
        $reponse = $req_des_roles->fetch(PDO::FETCH_ASSOC);
        return $reponse['r_description'];
    }

    public function lstRoleOrAdmin(){
        $req_lst_role = $this->connexion->prepare('SELECT r_id, r_description from gr_roles where r_id <> 1 ORDER BY r_description');
        $req_lst_role->execute();
        
        $reponse = $req_lst_role->fetchAll(PDO::FETCH_ASSOC);
        return $reponse;
    }
    
    
    public function lstRole($id){
        $req_lst_role = $this->connexion->prepare('SELECT r_id, r_description from gr_roles where r_id='.$id);
        $req_lst_role->execute();
        
        $reponse = $req_lst_role->fetchAll(PDO::FETCH_ASSOC);
        return $reponse;
    }
    
    
    
    
    public function lstprojets(){
        $req_lst_projet = $this->connexion->prepare('SELECT p_id, p_description from gr_projets ORDER BY p_description ASC');
        $req_lst_projet->execute();
        
        $reponse = $req_lst_projet->fetchAll(PDO::FETCH_ASSOC);
        return $reponse;
    
    }
    
    public static function convertDate($date) {
        
        $date_tmp = str_replace('/', '-', $date);
        $date_convert=date('Y-m-d', strtotime($date_tmp));
        
        return $date_convert;
        
    }
    
    
    public static function DecodeDate($date) {
        
        $date_tmp = str_replace('-', '/', $date);
        
        
        $date_convert=date('d/m/Y', strtotime($date_tmp));
        
        return $date_convert;
        
    }
    
    
    public static function convertDateWithHeure($date) {
        
        $date_tmp = str_replace('/', '-', $date);
        $date_convert=date('Y-m-d h:m:s', strtotime($date_tmp));
        
        return $date_convert;
        
    }
    
    
    public static function DecodeDateWithHeure($date) {

		
		$date_tmp = str_replace('-', '/', $date);
        $date_convert=date('d/m/Y H:i:s', strtotime($date_tmp));

        return $date_convert;
        
    }
    
    
}