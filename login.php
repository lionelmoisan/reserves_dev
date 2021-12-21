<?php require 'includes/includes.php'; ?>
<?php if (isset($_GET['logout'])) {
    session_unset();
    $_SESSION['message'] = "Vous êtes maintenant déconnecté.  A bientôt!";
}

if (isset($_POST) && !empty($_POST['password']) && !empty ($_POST['identifiant'])) {
        
    $identifiant=addslashes($_POST['identifiant']);
    $password = Auth::hashPassword($_POST['password']);
    
    $data = array(
            'identifiant'=>$identifiant,
            'password'=>$password
	);
            
	$sql_rch_uti = 'SELECT u_id,u_identifiant,u_prenom,u_nom,u_email,u_password,u_actif,u_role FROM gr_utilisateurs WHERE ((u_email=:identifiant and u_password=:password) OR (u_identifiant=:identifiant and u_password=:password)) limit 1';
	$req_rch_uti = $DB->tquery($sql_rch_uti,$data);
	
		if (!empty($req_rch_uti)){	

            if ($req_rch_uti[0]['u_role']<> 5) {
            
                if ($req_rch_uti[0]['u_actif'] == 1){
                        $_SESSION['id_user'] = $req_rch_uti[0]['u_id']; 
                        $_SESSION['prenom'] = $req_rch_uti[0]['u_prenom']; 
                        $_SESSION['nom'] = $req_rch_uti[0]['u_nom'];
						$_SESSION['email'] = $req_rch_uti[0]['u_email'];
						$_SESSION['identifiant'] = $req_rch_uti[0]['u_identifiant'];

                        $_SESSION['message'] = "Bienvenu, vous êtes connecté"; 
                        header('location:choix_du_projet.php');
                } else {
                    $_SESSION['message'] = "Compte non actif - Contacter votre responsable"; 
                }
            } else {
                 $_SESSION['message'] = "Pour l'instant, l'application n'est pas disponible pour les locataires"; 
            }   
                
        } else {
			$_SESSION['erreur'] = "Votre identifiant ou mot de passe incorrect"; 
		}
}
    
?>

<?php require_once 'includes/header_login.php'?>
<div class="container">
	<div class="row">
		<div class="col-md-3"></div> 
		<div class="col-md-6"> 
		<?php if(isset($_SESSION['message'])) { ?>
		<div class="alert alert-info" role="alert"><?php echo $_SESSION['message'];?></div>
		<?php unset($_SESSION['message']) ?>
		<?php } ?>  	

		<?php if (isset($_SESSION['erreur'])) { ?>
			<span class="label label-danger"></span>
			<div class="alert alert-danger" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only">Error:</span><?php echo $_SESSION['erreur'];?>
			</div>
			<?php unset($_SESSION['erreur']) ?>
		<?php } ?>  
		</div>
		<div class="col-md-3"></div>
	</div> 
	
	<div class="row">
		<div class="col-md-3"></div> 
		<div class="col-md-6 text-center"> <h2>Connexion</h2> </div>
		<div class="col-md-3"></div> 
	</div>


	<div class="row">  
		<div class="col-md-4"></div>
    	<div class="col-md-4"> 
			<form action="login.php" method="post">
        		<fieldset class="form-group">
            		<label for="email">Votre identifiant ou votre email :</label>
            		<input type="text" class="form-control" name="identifiant" value="">  
        		</fieldset>
          		<fieldset class="form-group">
            		<label for="password">Votre mot de passe :</label>
					<input type="password" class="form-control" name="password">
          		</fieldset>

				<fieldset class="form-group text-right">
				<a href="forgetpassword.php">Mot de passe oublié ?</a>
				</fieldset>



        		<button class="btn btn-primary" type="submit">Se connecter</button>
        	</form> 
    	</div>
		<div class="col-md-4"></div>
	</div>  
   </div>
    
     
<?php require 'includes/footer_login.php'?>