<?php require 'includes/includes.php';

require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

?>
<?php if (isset($_POST) && !empty($_POST['email']) ) {

    $utilisateur=Utilisateur::getUtilisateurByEmail($DB,$_POST['email']);


    //var_dump($utilisateur);

    // test de la présence de l'utilisateur
    //array_filter($utilisateur);

    if(empty($utilisateur)) {
        $_SESSION['erreur'] = "L'adresse e-mail ".$_POST['email']." n'est pas une adresse e-mail connue. Merci de vérifier votre adresse e-mail.";

    } else {

        $identifiant = $utilisateur[0]->u_identifiant;

        if ($utilisateur[0]->u_identifiant <> "") {

            $identifiant= $utilisateur[0]->u_identifiant;
        } else {
            $identifiant= $utilisateur[0]->u_email;

        }


        $mot_de_passe = Utilisateur::generer_mot_de_passe();
        $password = Auth::hashPassword($mot_de_passe);

        $data = array(
            'email' => $_POST['email'],
            'password' => $password
        );

        /*---- Mise à jour du mot de passe utilisateurs----*/
        $rep_update_password = $DB->insert("UPDATE gr_utilisateurs SET u_password=:password WHERE u_email=:email",$data);


        //=====Déclaration des messages au format texte et au format HTML.
        $message_html ="<html><head></head><body>";
        $message_html.="Madame,Monsieur,<br/><br/>Vous trouverez ci-dessous les informations vous permettant de vous connecter à la plate-forme de suivi des réserves.<br><br> ";
        $message_html.="Votre identifiant : ".$identifiant."<br>";
        $message_html.="Votre mot de passe : ".$mot_de_passe."<br><br>";
        $message_html.="Pour vous connecter à l'application : <a href=".URL_PROD."login.php>".URL_PROD."login.php</a><br><br>";
        $message_html.="Cordialement";
        $message_html.="</body></html>";

        $data_user= array(
            'sujet'=>utf8_decode("Suivi de vos réserves"),
            'email'=>$data['email'],
            'body'=>$message_html
        );

        //var_dump($data_user);

        /* Envoie d'un email */
        $sendemail = Email::sendEmailconnexion($DB,$data_user);


        //var_dump($mot_de_passe);
        $_SESSION['message']="Vous allez recevoir un email avec votre nouveau mot de passe";
        header('location:login.php');

    }
}?>
<?php require_once 'includes/header_login.php'?>
<?php require 'includes/footer_login.php'?>
<div class="container">

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <?php if (isset($_SESSION['message'])) { ?>
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
        <div class="col-md-6 text-center"> <h2>Réinitialiser votre mot de passe</h2> </div>
        <div class="col-md-3"></div>
    </div>


    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form action="forgetpassword.php" method="post">
                <fieldset class="form-group">
                    <label for="email">Adresse email :</label>
                    <input type="text" class="form-control" name="email" value="">
                </fieldset>

                <button class="btn btn-primary" type="submit">Réinitialiser le mot de passe</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>


</div>