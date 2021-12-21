<?php require 'includes/includes_back.php';

require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

require_once 'Classes/Notifications.php';

/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST)){ 
    
    $id_lot=$_POST['lot'];
    $description=Chaines::trt_insert_string($_POST['description']);
    $piece=Chaines::trt_insert_string($_POST['piece']);
    $type=$_POST['type'];


    // gestion des images associées à la réserve

    if (!empty($_FILES['image_1']['name'])) {

        $info_upload=Reserve::trtuploadfile($_FILES['image_1'],"img_reserves");

        if (!is_null($info_upload['message'])) {
            $_SESSION['erreur']=$info_upload['message'];
            header('location:index.php');
            exit();
        } else {

            $data_image_1 = array(
                'description'=> "image 1",
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
                'description'=> "image 2",
                'URL'=>$info_upload['nom_image']
            );
        }
    }


    $info_lot=lot::getLotById($DB,$id_lot);
    
    //print_r($info_lot);
    if (empty($_POST['datesignalement'])) {

        $date_signalement=Db::convertDate($info_lot->l_date_livraison);
           
    } else {
        $date_signalement=Db::convertDate($_POST['datesignalement']);
    }

    $lst_entreprises=$_POST['entreprise_list'];
        
    $date_jour = date("Y-m-d H:i:s");

    $nbr_entreprise = count($lst_entreprises);
    
    //echo "nbr entreprise".$nbr_entreprise;

    for ($i = 0; $i < $nbr_entreprise; $i++) {
              
        $data= array(
        'id_lot'=>$id_lot,
        'description'=>$description,
        'piece'=>$piece,
        'type'=>$type,
        'date_signalement'=>$date_signalement,
        'date_creation'=>$date_jour,
        'date_modifier'=>$date_jour,
        'statut'=>1,
        'id_entreprise'=>$lst_entreprises[$i],
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

        unset($data);
    }

    if ($type=='GPA') {


        /*Send email aux entreprises */
        $text_header_ent="Madame, Monsieur<br><br>";
        $text_header_ent.="Dans le cadre du projet - ".$_SESSION["nom_projet"]."<br><br>";
        $text_header_ent.="Nous vous informons qu'il a été signalé ce jour la demande de garantie de parfait achèvement précisée ci dessous  : <br><br>";


        $text_header_acq="Madame, Monsieur<br><br>";
        $text_header_acq.="Dans le cadre du projet - ".$_SESSION["nom_projet"]."<br><br>";
        $text_header_acq.="Nous vous informons que votre demande de garantie de parfait achèvement reprise ci dessous a bien été enregistrée.<br>";


        $text_footer_ent="<br><br>Nous vous prions de bien vouloir prendre contact avec les personnes concernées au plus vite de manière à convenir d'un RDV d'intervention.";

        $text_footer="<br><br>Vous pouvez accédez à la liste complète à jour, à l'historique complet de vos réserves et GPA par le lien : <a href='http://ektis-reserves.fr/' target='_blank'>http://ektis-reserves.fr/</a><br><br>";
        $text_footer.="Bien cordialement<br>";

        $infos_contact=Utilisateur::getUtilisateurById($DB,$info_lot->l_id_contact);
        
        $text_ent= "- GPA : ".$info_lot->l_numero_lot." - ".Chaines::trt_select_string($piece)." - ".Chaines::trt_select_string($description)." : personne à contacter pour intervention ".Chaines::trt_select_string($infos_contact[0]->u_nom)." ".Chaines::trt_select_string($infos_contact[0]->u_prenom)." ".$infos_contact[0]->u_portable_1." ".$infos_contact[0]->u_email;
        $text_acq= "- GPA : ".$info_lot->l_numero_lot." - ".Chaines::trt_select_string($piece)." - ".Chaines::trt_select_string($description);

        for ($i = 0; $i < $nbr_entreprise; $i++) {

            $info_utilisateur=Utilisateur::getUtilisateurById($DB,$lst_entreprises[$i]);

            $data = array (
                'email'=>$info_utilisateur[0]->u_email,
                'sujet'=>utf8_decode($_SESSION["nom_projet"]."- lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
                'txt-relance'=>$text_header_ent.$text_ent.$text_footer_ent.$text_footer,
                'URL'=>""
            );

            $etat_email=Email::SendEmailSignalementGPA($DB,$data,$data_image_1,$data_image_2);

        }

        /*
         * Copie au MOE si demande de GPA effectuée par l'administrateur Principal ou l'administrateur
         */
        if (($_SESSION['role']==1) || ($_SESSION['role']==8)) {

            /*
             * Recherche de tous les profils MOE pour le projet
             */
            $data = array(
                'id_projet'=>$_SESSION['id_projet'],
                'role'=>2
            );

            $lst_email_moe=ProjUti::getProUtiEmailbyprojet($DB,$data);


            foreach ($lst_email_moe as $email_moe) {

                $data = array (
                    'email'=>$email_moe['pu_u_identifiant'],
                    'sujet'=>utf8_decode($_SESSION["nom_projet"]."- lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
                    'txt-relance'=>$text_header_ent.$text_ent.$text_footer_ent.$text_footer,
                    'URL'=>"test9196.apps-1and1.net/ektis/lot2/"
                );

                $etat_email=Email::SendEmailSignalementGPA($DB,$data,$data_image_1,$data_image_2);

            }

        }
        
        /*Send email acquéreur */
        $infos_acquereur=Utilisateur::getUtilisateurById($DB,$info_lot->l_id_acquereur);

        if (!($infos_acquereur[0]->u_email == '')) {

            $data = array (
                'email'=>$infos_acquereur[0]->u_email,
                'sujet'=>utf8_decode($_SESSION["nom_projet"]."- lot : ".$info_lot->l_numero_lot." - Enregistrement d'une GPA"),
                'txt-relance'=>$text_header_acq.$text_acq.$text_footer,
                'URL'=>"test9196.apps-1and1.net/ektis/lot2/"
            );

            $etat_email=Email::SendEmailCreateGPA($DB,$data);


            /*
             * Ajout d'une ligne dans la table de notification
             */

            $today = date("Y-m-d");

            $data_notification= array(
                'projet_id'=>$_SESSION['id_projet'],
                'date_notification'=>$today,
                'sujet'=>'Nouvelle GPA',
                'id_acquereur'=>$info_lot->l_id_acquereur

            );

            $result=Notifications::setNotifications($DB,$data_notification);


            if (!$result) {
                $_SESSION['erreur']="Un problème est survenu lors de la création d'une nouvelle GPA";
            }
            

            $_SESSION['message']="Une notification de la GPA est automatiquement envoyée par email aux entreprises concernées et un accusé de prise en compte de sa demande est envoyé à l'acquéreur par email.";

        } else {

            $_SESSION['message']="Une notification de la GPA est automatiquement envoyée par email aux entreprises concernées";

        }


    } else {

        $_SESSION['message']="Une nouvelle réserve a été créée";
    }
    header('location:index.php');
}