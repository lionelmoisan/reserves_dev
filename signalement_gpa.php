<?php require 'includes/includes_back.php';

require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST)){


    if (!empty($_FILES['image_1']['name'])) {

        $info_upload=Reserve::trtuploadfile($_FILES['image_1'],"img_reserves");

        if (!is_null($info_upload['message'])) {
            $_SESSION['erreur']=$info_upload['message'];
            header('location:index.php');
            exit();
        } else {

            $data_image_1 = array(
                'description'=>"image 1",
                'URL'=>$info_upload['nom_image']
            );
        }

    }

    if (!empty($_FILES['image_2']['name'])) {

        $info_upload=Reserve::trtuploadfile($_FILES['image_2'],"img_reserves");

        if (!is_null($info_upload['message'])) {
            $_SESSION['erreur']=$info_upload['message'];
            header('location:index.php');
            exit();
        } else {

            $data_image_2 = array(
                'description'=>"image 2",
                'URL'=>$info_upload['nom_image']
            );
        }
    }

    $id_lot=$_POST['lot'];
    $description=Chaines::trt_insert_string($_POST['description']);
    $piece=Chaines::trt_insert_string($_POST['piece']);
    $type=utf8_encode('GPA à confirmer');

    $date_jour_small= date("Y-m-d");

    $date_jour_all = date("Y-m-d H:i:s");

    $data= array(
        'id_lot'=>$id_lot,
        'description'=>$description,
        'piece'=>$piece,
        'type'=>$type,
        'date_signalement'=>$date_jour_small,
        'date_creation'=>$date_jour_all,
        'date_modifier'=>$date_jour_all,
        'statut'=>8,
        'id_entreprise'=>NULL
    );

    $AjoutReserve=Reserve::AddReserve($DB,$data);

    $info_reserve=Reserve::LastIdreserve($DB);

    if (!is_null($data_image_1)) {

       $data=array_merge($data_image_1,array('id_reserve' => $info_reserve['r_id']));
        $add_res_image=Reserve::AddResImage($DB,$data);

    }

    if (!is_null($data_image_2)) {

        $data=array_merge($data_image_2,array('id_reserve' => $info_reserve['r_id']));
        $add_res_image=Reserve::AddResImage($DB,$data);

    }


    /*
     * Gestion des emails
     */

    $infos_acquereur=Utilisateur::getUtilisateurById($DB,$_SESSION["id_user"]);


    /*
    * Recherche de l'admin du projet
    */
    $data = array(
        'id_projet'=>$_SESSION['id_projet'],
        'role'=>8
    );

    $email_admin=ProjUti::getProUtiEmailbyprojet($DB,$data);


    /*
    * Recherche de tous les profils MOE pour le projet
    */
    $data = array(
        'id_projet'=>$_SESSION['id_projet'],
        'role'=>2
    );

    $lst_email_moe=ProjUti::getProUtiEmailbyprojet($DB,$data);

    /*
    * Recherche de tous les profils MOA pour le projet
    */
    $data = array(
        'id_projet'=>$_SESSION['id_projet'],
        'role'=>3
    );

    $lst_email_moa=ProjUti::getProUtiEmailbyprojet($DB,$data);


    /*
     * Infos lot
     */
    $info_lot=lot::getLotById($DB,$id_lot);



    /*Send email aux MOE - MOA et à l'ADMIN */
    $text_header_admin_MOE_MOA="Madame, Monsieur<br><br>";
    $text_header_admin_MOE_MOA.="Dans le cadre du projet - ".$_SESSION["nom_projet"]."<br><br>";
    $text_header_admin_MOE_MOA.="La demande de GPA décrite ci-dessous a été formulée ce jour par <br><br>";

    $text_infos_acquereur=utf8_decode($infos_acquereur[0]->u_nom)." ".utf8_decode($infos_acquereur[0]->u_prenom)."<br>";
    $text_infos_acquereur.=$infos_acquereur[0]->u_email."<br>";
    $text_infos_acquereur.=$infos_acquereur[0]->u_portable_1."<br><br>";


    $text_infos_lot = "Pour le lot ".$info_lot->l_numero_lot." : <br>";

    $description_gpa=$_POST['description'];
    
    $text_footer_admin_MOE_MOA="<br><br>Vous trouverez ci jointes les photos éventuellement fournies.";
    $text_footer_admin_MOE_MOA.="<br><br>La prise en charge de cette demande doit être confirmée ou infirmée par la Maîtrise d'ouvrage ou la Maîtrise d’œuvre en se connectant à la plate forme de gestion des réserves : <a href='http://ektis-reserves.fr/' target='_blank'>http://ektis-reserves.fr/</a><br><br>";
    $text_footer_admin_MOE_MOA.="Bien cordialement<br>";


    /* ENVOYER  email pour les MOE */
    foreach ($lst_email_moe as $email_moe) {

        $data = array (
            'email'=>$email_moe['pu_u_identifiant'],
            'sujet'=>utf8_decode($_SESSION["nom_projet"]." - lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
            'txt-relance'=>$text_header_admin_MOE_MOA.$text_infos_acquereur.$text_infos_lot.$description_gpa.$text_footer_admin_MOE_MOA,
            'URL'=>""
        );

        //var_dump($data);
        $etat_email=Email::SendEmailSignalementGPA($DB,$data,$data_image_1,$data_image_2);

    }

    /* ENVOYER  email pour les MOA */
    foreach ($lst_email_moa as $email_moa) {

        $data = array (
            'email'=>$email_moa['pu_u_identifiant'],
            'sujet'=>utf8_decode($_SESSION["nom_projet"]." - lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
            'txt-relance'=>$text_header_admin_MOE_MOA.$text_infos_acquereur.$text_infos_lot.$description_gpa.$text_footer_admin_MOE_MOA,
            'URL'=>""
        );

        //var_dump($data);
        $etat_email=Email::SendEmailSignalementGPA($DB,$data,$data_image_1,$data_image_2);

    }

    /* ENVOYER  email pour ADMIN */

    $data = array (
        'email'=>$email_admin[0]['pu_u_identifiant'],
        'sujet'=>utf8_decode($_SESSION["nom_projet"]." - lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
        'txt-relance'=>$text_header_admin_MOE_MOA.$text_infos_acquereur.$text_infos_lot.$description_gpa.$text_footer_admin_MOE_MOA,
        'URL'=>""
    );

    //var_dump($data);
    $etat_email=Email::SendEmailSignalementGPA($DB,$data,$data_image_1,$data_image_2);


    /* Envoyer email de confirmation à l'acquéreur*/

    $text_acquereur="Madame, Monsieur<br><br>";
    $text_acquereur.="Votre demande : '".$_POST['description']."' est envoyée au Maître d'ouvrage et au Maître d'oeuvre. Sa prise en charge vous sera confirmée ou infirmée par e-mail après analyse dans les jours qui viennent.<br><br>";

    $text_acquereur.="Sans réponse sous 2 semaines, nous vous conseillons de joindre directement votre correspondant pour en connaître les raisons.<br><br>";

    $text_acquereur.="En cas de demande urgente nous vous conseillons, en parallèle à l'enregistrement de la GPA sur la plate forme, de contacter votre correspondant.";

    $text_footer="<br><br>Vous pouvez accédez à la liste complète à jour, à l'historique complet de vos réserves et GPA par le lien : <a href='http://ektis-reserves.fr/' target='_blank'>http://ektis-reserves.fr/</a><br><br>";
    $text_footer.="Bien cordialement<br>";

    $data = array (
        'email'=>$infos_acquereur[0]->u_email,
        'sujet'=>utf8_decode($_SESSION["nom_projet"]." - lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
        'txt-relance'=>$text_acquereur.$text_footer,
        'URL'=>""
    );
    //var_dump($data);
    $etat_email=Email::SendEmailCreateGPA($DB,$data);

}

$_SESSION['message']="Une réserve de type GPA a été signalée";
header('location:index.php');