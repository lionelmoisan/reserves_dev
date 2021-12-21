<?php require 'includes/includes.php';

require_once 'Classes/Utilisateur.php';
require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

if(!empty($_GET) && isset($_GET['token']) && isset($_GET['email'])){
    
    $token = $_GET['token'];
    $email = $_GET['email'];
    
    $data = array(
        'email'=>$email,
        'token'=>$token
    );

    
   $sql= $DB->query("SELECT u_id,u_identifiant,u_email,u_token,u_actif from gr_utilisateurs where u_email=:email and u_token=:token",$data);

    if (!empty($sql)){ 
    
        if ($sql[0]->u_actif==1) {
             $_SESSION['erreur']="La confirmation de votre adresse email a déjà été prise en compte. <br><br>Pour recevoir un nouveau mot de passe, vous devez cliquer sur le lien 'Mot de passe oublié ?' présent sur la <a href=\"http://ektis-reserves.fr/login.php\">page de connexion.</a>";
        } else {

            // Génértion d'un mot de passe et hash

            $mot_de_passe=Utilisateur::generer_mot_de_passe();
            $password=Auth::hashPassword($mot_de_passe);

            $data_insert = array(
                'email'=>$email,
                'password'=>$password,
                'actif'=>'1'
                );

            $sql_update= $DB->insert("UPDATE gr_utilisateurs set u_actif=:actif,u_password=:password where u_email=:email",$data_insert);

            $_SESSION['message']="Merci d'avoir confirmé votre adresse mail.<br><br> Vous allez recevoir un nouveau mail vous informant de vos identifiants et mot de passe.";

            //var_dump($sql);

            /*
             * test de l'identifiant
             */
            if ($sql[0]->u_identifiant !=""){

                $identifiant =$sql[0]->u_identifiant;

            } else {
                $identifiant=$data['email'];
            }

            //=====Déclaration des messages au format texte et au format HTML.
            $message_html ="<html><head></head><body>";
            $message_html.="Madame,Monsieur,<br/><br/>Vous trouverez ci-dessous les informations vous permettant de vous connecter à la plate-forme de suivi des réserves.<br><br> ";
            $message_html.="Votre identifiant : ".$identifiant."<br>";
            $message_html.="Votre mot de passe : ".$mot_de_passe."<br><br>";
            $message_html.="Pour vous connecter à l'application : <a href=".URL_PROD."login.php>".URL_PROD."login.php</a><br><br>";
            $message_html.="Cordialement";
            $message_html.="</body></html>";


            $data_user= array(
                'sujet'=>utf8_decode($_SESSION['nom_projet']." - Suivi de vos réserves"),
                'email'=>$email,
                'body'=>$message_html
            );

            /* Envoie d'un email */
            $sendemail = Email::sendEmailconnexion($DB,$data_user);

        }
        
    } else {
        $_SESSION['erreur']="L'utilisateur n'existe pas";
    }
    
} else {
        header('location:login.php');
}

?>

<?php require 'includes/header_login.php'?>

<div class="container">

<div class="col-md-12">
    <br />
<div class="row">
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info" role="alert"><?php echo $_SESSION['message'];?></div>
    <?php unset($_SESSION['message']) ?>
    <?php endif ?>  	

    <?php if (isset($_SESSION['erreur'])): ?>
        <span class="label label-danger"></span>
        <div class="alert alert-danger" role="alert">
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <span class="sr-only">Error:</span><?php echo $_SESSION['erreur'];?>
        </div>
        <?php unset($_SESSION['erreur']) ?>
    <?php endif ?>  
</div>    
 
</div>