<?php 
/**
* Projet Utilisateur
*/
class Email{


    public static function sendEmailconnexion($DB,$data) {

        $mail= new PHPMailer();

        $mail->setFrom('contact@ektis-reserves.fr', 'Contact - Gestion de reserve');
        $mail->addAddress($data['email'], '');
        $mail->IsHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject  = utf8_encode($data['sujet']);
        $mail->Body     = $data['body'];

        $mail->send();

    }

    public static function SendEmailrelance($DB,$data) {

        $mail             = new PHPMailer();

        $mail->setFrom($data['email_expediteur'], '');
        $mail->addAddress($data['email'], '');
        $mail->IsHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject  = utf8_encode($data['sujet']);
        $mail->Body     = $data['txt-relance'];

        // environnement de developpement
        //$mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/ektis/Fichiers/".$data['email']."-liste-des-reserves.pdf");
        // environement de production
        $mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/Fichiers/".$data['email']."-liste-des-reserves.pdf");
        
        if(!$mail->send()) {
            //echo 'Message was not sent.';
            //echo 'Mailer error: ' . $mail->ErrorInfo;
            //return $mail->ErrorInfo;
        } else {
            //echo 'Message has been sent.';
            //return true;
        }
    }
    
    public static function SendEmailCreateGPA($DB,$data) {

        $mail             = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->setFrom('contact@ektis-reserves.fr', 'Contact - Gestion de reserve');
        $mail->addAddress($data['email'], '');
        $mail->IsHTML(true);
        $mail->Subject  = utf8_encode($data['sujet']);
        $mail->Body     = $data['txt-relance'];
        
        $mail->send();

    }

    public static function SendEmailautomatique($DB,$data) {

        $mail             = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->setFrom('contact@ektis-reserves.fr', 'Contact - Gestion de reserve');
        $mail->addAddress($data['email'], '');
        $mail->IsHTML(true);
        $mail->Subject  = utf8_encode($data['sujet']);
        $mail->Body     = $data['txt-relance'];

        $mail->send();
        
    }



    public static function SendEmailSignalementGPA($DB,$data,$data_image_1,$data_image_2) {

        $mail             = new PHPMailer();

        $mail->setFrom('contact@ektis-reserves.fr', 'Contact - Gestion de reserve');
        $mail->addAddress($data['email'], '');
        $mail->IsHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject  = utf8_encode($data['sujet']);
        $mail->Body     = $data['txt-relance'];

        if (isset($data_image_1)) {
            // environnement de developpement
            //$mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/ektis/".$data_image_1['URL']);
            // environnement de recette
            //$mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/ektis/lot2/".$data_image_1['URL']);
            // environnement de production
            $mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/".$data_image_1['URL']);
        }

        if (isset($data_image_2)) {
            // environnement de developpement
            //$mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/ektis/".$data_image_2['URL']);
            // environnement de recette
            //$mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/ektis/lot2/".$data_image_2['URL']);
            // environnement de production
            $mail->AddAttachment($_SERVER["DOCUMENT_ROOT"]."/".$data_image_2['URL']);
        }
        

        if(!$mail->send()) {
            //echo 'Message was not sent.';
            //echo 'Mailer error: ' . $mail->ErrorInfo;
            //return $mail->ErrorInfo;
        } else {
            //echo 'Message has been sent.';
            //return true;
        }
    }
    
}