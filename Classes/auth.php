<?php 

/**
* Auth
*/
class Auth{
	
	public static function islog($db){
		if(isset($_SESSION['nom']) && isset($_SESSION['user']['email']) &&  isset($_SESSION['user']['password'])){

			$data =array(
				'email'=>$_SESSION['user']['email'],
				'password'=>$_SESSION['user']['password']
				);

			$sql = 'SELECT * FROM users WHERE email=:email AND password=:password limit 1';
			$req = $db->tquery($sql,$data);

			if(!empty($req)){
				return true;
			}
		}
		return false;
	}
	
	public static function jesuisloge($db) {
		if(isset($_SESSION['nom'])) {
				return true; 
		}
		return false; 
	}


	public static function hashPassword($pass){

        $pass_hache=sha1($pass);
        return $pass_hache;
	}

	public static function isadmin($db,$id_user){
		
        $data =array(
            'id_user'=>$id_user
        );
        
        $sql = "SELECT u_role FROM gr_utilisateurs WHERE u_id=:id_user";
        $req = $db->tquery($sql,$data);
      
        if ($req[0]['u_role']==1){
            return true;
        } else  {
            return false;
        }
	}
}