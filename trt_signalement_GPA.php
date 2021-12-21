<?php require 'includes/includes_back.php';

require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

require_once 'Classes/Notifications.php';

/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST)) {

    $date_jour = date("Y-m-d H:i:s");

    $lst_entreprises=$_POST['entreprise_list'];

    $nbr_entreprise = count($lst_entreprises);

    $sql_get_id_reserve= $DB->tquery("SELECT r_id,r_id_lot,r_description,r_piece,r_type,r_date_signalement,r_date_creation,r_date_modifier,r_ls_id FROM gr_reserves WHERE r_id=".$_POST['id_reserve']);

    $reserve_sign_gpa=$sql_get_id_reserve[0];

    $sql_get_res_img=$DB->tquery("SELECT * FROM gr_res_images WHERE ri_r_id=".$_POST['id_reserve']);

    $reserve_image=$sql_get_res_img;

    $info_lot=lot::getLotById($DB,$reserve_sign_gpa["r_id_lot"]);


    if ($_POST['choix_action']=="confirmer") {

        for ($i = 0; $i < $nbr_entreprise; $i++) {

            if ($i == 0) {

                /* MAJ de la réserve avec id entreprise*/
                $data = array(
                    'id_reserve'=>$_POST['id_reserve'],
                    'id_entreprise'=>$lst_entreprises[0],
                    'date_modifier'=>$date_jour
                );

                //var_dump($data);

                $rep_update_reserve = $DB->insert("UPDATE gr_reserves SET r_type='GPA',r_ls_id=1,r_date_modifier=:date_modifier,r_id_entreprise=:id_entreprise WHERE r_id=:id_reserve",$data);

                $data_histo = array (
                    'r_id'=> $_POST['id_reserve'],
                    'ls_id'=> 1,
                    'u_id'=> $_SESSION['id_user'],
                    'date_modifier'=> $date_jour
                );

                $insert_histo_statut=Reserve::AddHistoStatut($DB,$data_histo);



            } else {

                $data= array(
                    'id_lot'=>$reserve_sign_gpa['r_id_lot'],
                    'description'=>$reserve_sign_gpa['r_description'],
                    'piece'=>$reserve_sign_gpa['r_piece'],
                    'type'=>'GPA',
                    'date_signalement'=>$reserve_sign_gpa['r_date_signalement'],
                    'date_creation'=>$reserve_sign_gpa['r_date_creation'],
                    'date_modifier'=>$date_jour,
                    'statut'=>1,
                    'id_entreprise'=>$lst_entreprises[$i],
                );

                //var_dump($data);

                $AjoutReserve=Reserve::AddReserve($DB,$data);

                $id_reserve=Reserve::LastIdreserve($DB);

                // Ajout des images pour la nouvelle réserve


                foreach ($reserve_image as $lst_reserve_image) {

                    $data_res_img = array (
                        'id_reserve'=> $id_reserve['r_id'],
                        'description'=> $lst_reserve_image['ri_description'],
                        'URL'=> $lst_reserve_image['ri_url']
                    );

                    $insert_reserve_image=Reserve::AddResImage($DB,$data_res_img);

                }

                $data_histo = array (
                    'r_id'=> $id_reserve['r_id'],
                    'ls_id'=> 1,
                    'u_id'=> $_SESSION['id_user'],
                    'date_modifier'=> $date_jour
                );

                $insert_histo_statut=Reserve::AddHistoStatut($DB,$data_histo);

                unset($data);

            }

        }

        // gestion des emails

        /*Send email aux entreprises */
        $text_header_ent="Madame, Monsieur<br><br>";
        $text_header_ent.="Dans le cadre du projet - ".$_SESSION["nom_projet"]."<br><br>";
        $text_header_ent.="Nous vous informons qu'il a été signalé ce jour la demande de garantie de parfait achèvement précisée ci dessous  : <br><br>";


        $text_header_acq="Madame, Monsieur<br><br>";
        $text_header_acq.="Dans le cadre du projet - ".$_SESSION["nom_projet"]."<br><br>";
        $text_header_acq.="Nous vous informons que votre demande de garantie de parfait achèvement reprise ci dessous a bien été enregistrée.<br>";

        $text_header_admin_moe_moa="Madame, Monsieur<br><br>";
        $text_header_admin_moe_moa.="Dans le cadre du projet - ".$_SESSION["nom_projet"]."<br><br>";
        $text_header_admin_moe_moa.="Nous vous informons que la demande de garantie de parfait achèvement précisée ci dessous à été validée ce jour : <br><br>";


        $text_footer_ent="<br><br>Nous vous prions de bien vouloir prendre contact avec les personnes concernées au plus vite de manière à convenir d'un RDV d'intervention.";

        $text_footer_admin_moe_moa="<br><br>Un mail a été envoyé aux entreprises concernées demandant de convenir au plus vite d'un RDV d'intervention.";

        $text_footer="<br><br>Vous pouvez accédez à la liste complète à jour, à l'historique complet de vos réserves et GPA par le lien : <a href='http://ektis-reserves.fr/' target='_blank'>http://ektis-reserves.fr/</a><br><br>";
        $text_footer.="Bien cordialement<br>";

        $infos_contact=Utilisateur::getUtilisateurById($DB,$info_lot->l_id_contact);

        $text_ent= "- GPA : ".$info_lot->l_numero_lot." - ".utf8_decode(stripslashes($reserve_sign_gpa['r_piece']))." - ".utf8_decode(stripslashes($reserve_sign_gpa['r_description']))." : personne à contacter pour intervention ".utf8_decode($infos_contact[0]->u_nom)." ".utf8_decode($infos_contact[0]->u_prenom)." ".$infos_contact[0]->u_portable_1." ".$infos_contact[0]->u_email;
        $text_acq= "- GPA : ".$info_lot->l_numero_lot." - ".utf8_decode(stripslashes($reserve_sign_gpa['r_piece']))." - ".utf8_decode(stripslashes($reserve_sign_gpa['r_description']));

        $text_admin_moe_moa= "- GPA : ".$info_lot->l_numero_lot." - ".utf8_decode(stripslashes($reserve_sign_gpa['r_piece']))." - ".utf8_decode(stripslashes($reserve_sign_gpa['r_description']));

        $num_image=0;

        foreach ($reserve_image as $lst_reserve_image) {

            if ($num_image == 0) {
                $data_image_1 = array(
                    'description'=> $lst_reserve_image['ri_description'],
                    'URL'=>$lst_reserve_image['ri_url']
                );
            } else {
                $data_image_2 = array(
                    'description'=> $lst_reserve_image['ri_description'],
                    'URL'=>$lst_reserve_image['ri_url']
                );
            }

            $num_image=$num_image+1;

        }

        for ($i = 0; $i < $nbr_entreprise; $i++) {

            $info_utilisateur=Utilisateur::getUtilisateurById($DB,$lst_entreprises[$i]);

            $data = array (
                'email'=>$info_utilisateur[0]->u_email,
                'sujet'=>utf8_decode($_SESSION["nom_projet"]."- lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
                'txt-relance'=>$text_header_ent.$text_ent.$text_footer_ent.$text_footer,
                'URL'=>"test9196.apps-1and1.net/ektis/lot2/"
            );

           //var_dump($data);
            $etat_email=Email::SendEmailSignalementGPA($DB,$data,$data_image_1,$data_image_2);
        }


        /*
         * Copie au MOE,MOA et Admin
         */

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

            foreach ($lst_email_moe as $email_moe) {

                $data = array (
                    'email'=>$email_moe['pu_u_identifiant'],
                    'sujet'=>utf8_decode($_SESSION["nom_projet"]."- lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
                    'txt-relance'=>$text_header_admin_moe_moa.$text_admin_moe_moa.$text_footer_admin_moe_moa.$text_footer,
                    'URL'=>""
                );
                //var_dump($data);
                $etat_email=Email::SendEmailCreateGPA($DB,$data);
            }

            foreach ($lst_email_moa as $email_moa) {

            $data = array (
                'email'=>$email_moa['pu_u_identifiant'],
                'sujet'=>utf8_decode($_SESSION["nom_projet"]."- lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
                'txt-relance'=>$text_header_admin_moe_moa.$text_admin_moe_moa.$text_footer_admin_moe_moa.$text_footer,
                'URL'=>""
            );
            //var_dump($data);
            $etat_email=Email::SendEmailCreateGPA($DB,$data);
            }


        /* ENVOYER  email pour ADMIN */

        $data = array (
            'email'=>$email_admin[0]['pu_u_identifiant'],
            'sujet'=>utf8_decode($_SESSION["nom_projet"]." - lot : ".$info_lot->l_numero_lot."  - Signalement d'une GPA"),
            'txt-relance'=>$text_header_admin_moe_moa.$text_admin_moe_moa.$text_footer_admin_moe_moa.$text_footer,
            'URL'=>""
        );

        //var_dump($data);
        $etat_email=Email::SendEmailCreateGPA($DB,$data);

        /*Send email acquéreur */
        $infos_acquereur=Utilisateur::getUtilisateurById($DB,$info_lot->l_id_acquereur);

        if (!($infos_acquereur[0]->u_email == '')) {

            $data = array (
                'email'=>$infos_acquereur[0]->u_email,
                'sujet'=>utf8_decode($_SESSION["nom_projet"]."- lot : ".$info_lot->l_numero_lot." - Enregistrement d'une GPA"),
                'txt-relance'=>$text_header_acq.$text_acq.$text_footer,
                'URL'=>"test9196.apps-1and1.net/ektis/lot2/"
            );

            //var_dump($data);
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

            //var_dump($data_notification);

            $result=Notifications::setNotifications($DB,$data_notification);


            if (!$result) {
                $_SESSION['erreur']="Un problème est survenu lors de la création d'une nouvelle GPA";
            }


            $_SESSION['message']="Une notification de la GPA est automatiquement envoyée par email aux entreprises concernées et un accusé de prise en compte de sa demande est envoyé à l'acquéreur par email.";

        } else {

            $_SESSION['message']="Une notification de la GPA est automatiquement envoyée par email aux entreprises concernées";

        }


    } elseif ($_POST['choix_action']=="refuser") {


        $data = array(
            'id_reserve'=>$_POST['id_reserve'],
            'date_modifier'=>$date_jour
        );

        //var_dump($data);

        $rep_update_reserve = $DB->insert("UPDATE gr_reserves SET r_type='GPA',r_ls_id=7,r_date_modifier=:date_modifier WHERE r_id=:id_reserve",$data);


        $data_histo = array (
            'r_id'=> $_POST['id_reserve'],
            'ls_id'=> 7,
            'u_id'=> $_SESSION['id_user'],
            'date_modifier'=> $date_jour
        );

        $insert_histo_statut=Reserve::AddHistoStatut($DB,$data_histo);


        /*Send email acquéreur */
        $infos_acquereur=Utilisateur::getUtilisateurById($DB,$info_lot->l_id_acquereur);


        $text_header_acq="Madame, Monsieur<br><br>";
        $text_header_acq.="Dans le cadre du projet - ".$_SESSION["nom_projet"]."<br><br>";
        $text_header_acq.="Pour la GPA suivante : ".utf8_decode($reserve_sign_gpa["r_description"])."<br><br>";
        $text_header_acq.="Nous vous informons que votre demande de garantie de parfait achèvement ne peut être prise en charge pour la raison suivante : <br><br>";

        $text_footer="<br><br>Pour de plus amples informations nous vous conseillons de contacter votre correspondant.<br>";
        $text_footer.="<br><br>Vous pouvez accédez à la liste complète à jour, à l'historique complet de vos réserves et GPA par le lien : <a href='http://ektis-reserves.fr/' target='_blank'>http://ektis-reserves.fr/</a><br><br>";
        $text_footer.="Bien cordialement<br>";


        if (!($infos_acquereur[0]->u_email == '')) {

            $data = array(
                'email' => $infos_acquereur[0]->u_email,
                'sujet' => utf8_decode($_SESSION["nom_projet"] . "- lot : " . $info_lot->l_numero_lot . " - Prise en charge de votre GPA infirmée"),
                'txt-relance' => $text_header_acq . $_POST['motif'] . $text_footer,
                'URL' => "test9196.apps-1and1.net/ektis/lot2/"
            );

            //var_dump($data);
            $etat_email=Email::SendEmailCreateGPA($DB,$data);


            /*
             *  Ajout d'une notification
             */
            $today = date("Y-m-d");

            $data_notification= array(
                'projet_id'=>$_SESSION['id_projet'],
                'date_notification'=>$today,
                'sujet'=>'Refus GPA',
                'id_acquereur'=>$info_lot->l_id_acquereur

            );

            //var_dump($data_notification);

            $result=Notifications::setNotifications($DB,$data_notification);

            /*
             * ajout du texte de refus comme commentaire
             */

            $motif_commentaire="Refus de prise en charge de GPA : ".$_POST['motif'];

            $remarque=addslashes($motif_commentaire);

            $data = array(
                'r_id'=>$_POST['id_reserve'],
                'u_id'=>$_SESSION['id_user'],
                'r_date'=>$date_jour,
                'r_remarque'=>$remarque
            );

            //var_dump($data);
            /* ajouter une remarque dans une réserve */
            $inser_remarque_reserve=Reserve::AddRemarqueReserve($DB,$data);

        }

    }

}
header('location:index.php');