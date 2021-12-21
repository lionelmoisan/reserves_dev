<?php require 'includes/includes_back.php';

require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

if(!empty($_GET['id'])) {
    $id = intval($_GET['id']);

    $utilisateur = Utilisateur::getUtilisateurById($DB, $id);

    $token = $utilisateur[0]->u_token;
    $email = $utilisateur[0]->u_email;
    
    $data = array(
        'id'=>$id,
        'actif'=>'0'
    );

    /*---- Mise à jour du mot de passe utilisateurs----*/
    $rep_update_password = $DB->insert("UPDATE gr_utilisateurs SET u_actif=:actif WHERE u_id=:id",$data);

    /*
     * =====Déclaration des messages au format texte et au format HTML.
     */
    $message_html ="<html><head></head><body>";
    $message_html.= "Madame,Monsieur<br/><br/>Nous avons mis en place une plate forme sur internet vous permettant de consulter vos réserves et demandes de garantie pour le projet '".$_SESSION['nom_projet']."'<br><br>";
    $message_html.= "Afin de confirmer que votre adresse mail est correcte nous vous prions de bien vouloir cliquer sur le lien ci dessous. Vous recevrez un nouveau mail vous informant de vos identifiant et mot de passe.<br><br>";
    $message_html.= "<a href='".URL_PROD."activate.php?token=".$token."&email=".$email."'>";
    $message_html.= "Cliquer pour confirmer votre adresse email</a><br><br>";
    $message_html.='Cordialement';
    $message_html.='</body></html>';

    /**
     * SEND CHECK EMAIL
     */
    $data_email = array(
        'email' => $email,
        'sujet' => utf8_decode($_SESSION['nom_projet'] . ' - Confirmation de votre adresse mail'),
        'body' => $message_html
    );

    //var_dump($data_email);

    if (isset($data_email['email'])) {
        $send_email = Email::sendEmailconnexion($DB, $data_email);
    }

    $_SESSION['message']="Un e-mail de connexion a été envoyé à ".$email;

    header('location:lst_utilisateurs.php');
    exit();
}