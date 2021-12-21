<?php require 'includes/includes_back.php';

require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

$lst_roles=Role::lstRole($DB,$_SESSION['role']);

/*---- Traitement des infos utilisateurs après validation ----*/
if(!empty($_POST)) {

    $id = intval($_GET['id']);

    $sql_utilisateur=Utilisateur::getUtilisateurById($DB,$id);

    $utilisateur=$sql_utilisateur[0];

    // Traitement des données du formulaire
    $prenom=Chaines::trt_insert_string($_POST['prenom']);
    $nom=Chaines::trt_insert_string($_POST['nom']);

    $societe = Chaines::trt_insert_string($_POST['societe']);

    $entreprise=Chaines::trt_insert_string($_POST['entreprise']);

    $email=addslashes($_POST['email']);
    $identifiant=addslashes($_POST['identifiant']);

    $adresse=Chaines::trt_insert_string($_POST['adresse']);
    $ville=Chaines::trt_insert_string($_POST['ville']);
    $code_postal=addslashes($_POST['cp']);

    $portable_1=addslashes($_POST['portable_1']);
    $telephone=addslashes($_POST['telephone']);
    $portable_2=addslashes($_POST['portable_2']);

    $role=$_POST['role'];

    if (empty($email)) {
        $identifiant_valeur = $identifiant;
    } else {
        $identifiant_valeur = $email;
    }



    if (($sql_utilisateur[0]->u_email  != $email) ||  ($sql_utilisateur[0]->u_identifiant !=$identifiant)) {
        

        if (empty($email) && empty($identifiant)) {

            $presence_uti = null;

        } else {
            $presence_uti = ProjUti::getProUtiRole($DB,$_SESSION['id_projet'],$identifiant_valeur);
        }

        var_dump($presence_uti);

        if (!is_null($presence_uti)) {
            $_SESSION['erreur'] ="L'utilisateur '".$identifiant_valeur."' existe déjà dans le projet";
            header("location:up_utilisateur.php?id=$id");
            exit();

        } else {

            // ATTENTION JE REFAIT LA MEME OPERATION

            // Prise en compte des champs disabled pour un MOA et MOE
            if (($utilisateur->u_role == 2) || ($utilisateur->u_role == 3) || ($utilisateur->u_role == 8)) {
                $role = $utilisateur->u_role;
            }

            // Prise en compte des champs disabled pour un acquéreur
            if ($utilisateur->u_role == 4) {
                $identifiant = $identifiant;
                $role = $utilisateur->u_role;
            }

            // Prise en compte des champs disabled pour un locataire
            if ($utilisateur->u_role == 5) {
                $identifiant = $identifiant;
                $role = $utilisateur->u_role;
            }

            // Prise en compte des champs disabled pour un entreprise
            if ($utilisateur->u_role == 6) {
                $role = $utilisateur->u_role;
            }

            // Prise en compte des champs disabled pour un autre intervenant
            if ($utilisateur->u_role == 7) {
                $role = $utilisateur->u_role;
                $identifiant = $identifiant;
            }

            $data = array(
                'id' => $id,
                'identifiant' => $identifiant,
                'prenom' => $prenom,
                'nom' => $nom,
                'societe' => $societe,
                'entreprise' => $entreprise,
                'email' => $email,
                'adresse' => $adresse,
                'ville' => $ville,
                'cp' => $code_postal,
                'portable_1' => $portable_1,
                'telephone' => $telephone,
                'portable_2' => $portable_2,
                'role' => $role
            );

            /*---- Mise à jour des données utilisateurs----*/
            $rep_update_uti = $DB->insert("UPDATE gr_utilisateurs SET u_identifiant=:identifiant,u_prenom=:prenom,u_nom=:nom,u_societe=:societe,u_entreprise=:entreprise,u_email=:email,u_adresse=:adresse,u_ville=:ville,u_cp=:cp,u_portable_1=:portable_1,u_telephone=:telephone,u_portable_2=:portable_2,u_role=:role,u_actif=0 WHERE u_id=:id", $data);

            if ($rep_update_uti) {

                /** MISE à JOUR DES INFORMATIONS DANS LA TABLE DE CORRESPONDANCE  */

                $data_proj_util = array (
                    'id' => $id,
                    'pro_uti_identifiant'=>$identifiant_valeur,
                    'role'=>$role
                );

                $rep_up_proj_uti = $DB->insert("UPDATE gr_proj_uti SET pu_u_identifiant=:pro_uti_identifiant,pu_u_role=:role WHERE pu_u_id=:id", $data_proj_util);

                if ($rep_up_proj_uti) {

                    /**
                     * SEND CHECK EMAIL
                     */

                    /*
                    * =====Déclaration des messages au format texte et au format HTML.
                    */
                    $message_html ="<html><head></head><body>";
                    $message_html.= "Madame,Monsieur<br/><br/>Nous avons mis en place une plate forme sur internet vous permettant de consulter vos réserves et demandes de garantie pour le projet '".$_SESSION['nom_projet']."'<br><br>";
                    $message_html.= "Afin de confirmer que votre adresse mail est correcte nous vous prions de bien vouloir cliquer sur le lien ci dessous. Vous recevrez un nouveau mail vous informant de vos identifiant et mot de passe.<br><br>";
                    $message_html.= "<a href='".URL_PROD."activate.php?token=".$utilisateur->u_token."&email=".$email."'>";
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

                    if ($data_email['email']) {
                        $send_email = Email::sendEmailconnexion($DB, $data_email);
                        $_SESSION['message'] = "La modification a été enregistrée avec succès.";
                    } else {

                        $_SESSION['message'] = "La modification a été enregistrée avec succès ";
                        $_SESSION['erreur'] = "Aucun email n'a été envoyé à l'intervenant, car l'email est non renseigné";
;
                    }
                    
                    
                    header('location:lst_utilisateurs.php');
                    exit();

                }
            }


        }

    } else {
        
        // Prise en compte des champs disabled pour un MOA et MOE
        if (($utilisateur->u_role == 2) || ($utilisateur->u_role == 3) || ($utilisateur->u_role == 8)) {
            $role = $utilisateur->u_role;
        }

        // Prise en compte des champs disabled pour un acquéreur
        if ($utilisateur->u_role == 4) {
            $identifiant = $utilisateur->u_identifiant;
            $role = $utilisateur->u_role;
        }

        // Prise en compte des champs disabled pour un locataire
        if ($utilisateur->u_role == 5) {
            $identifiant = $utilisateur->u_identifiant;
            $role = $utilisateur->u_role;
        }

        // Prise en compte des champs disabled pour un entreprise
        if ($utilisateur->u_role == 6) {
            $role = $utilisateur->u_role;
        }

        // Prise en compte des champs disabled pour un autre intervenant
        if ($utilisateur->u_role == 7) {
            $role = $utilisateur->u_role;
            $identifiant = $utilisateur->u_identifiant;
        }

        $data = array(
            'id' => $id,
            'identifiant' => $identifiant,
            'prenom' => $prenom,
            'nom' => $nom,
            'societe' => $societe,
            'entreprise' => $entreprise,
            'email' => $email,
            'adresse' => $adresse,
            'ville' => $ville,
            'cp' => $code_postal,
            'portable_1' => $portable_1,
            'telephone' => $telephone,
            'portable_2' => $portable_2,
            'role' => $role
        );

        /*---- Mise à jour des données utilisateurs----*/
        $rep_update_uti = $DB->insert("UPDATE gr_utilisateurs SET u_identifiant=:identifiant,u_prenom=:prenom,u_nom=:nom,u_societe=:societe,u_entreprise=:entreprise,u_email=:email,u_adresse=:adresse,u_ville=:ville,u_cp=:cp,u_portable_1=:portable_1,u_telephone=:telephone,u_portable_2=:portable_2,u_role=:role WHERE u_id=:id", $data);

        if ($rep_update_uti) {

            /** MISE à JOUR DES INFORMATIONS DANS LA TABLE DE CORRESPONDANCE  */

            $data_proj_util = array (
                'id' => $id,
                'pro_uti_identifiant'=>$identifiant_valeur,
                'role'=>$role
            );

            $rep_up_proj_uti = $DB->insert("UPDATE gr_proj_uti SET pu_u_identifiant=:pro_uti_identifiant,pu_u_role=:role WHERE pu_u_id=:id", $data_proj_util);

            if ($rep_up_proj_uti) {

                $_SESSION['message'] = "La modification a été enregistrée avec succès.";
                header('location:lst_utilisateurs.php');
                exit();

            }
        }

    }
}

/*---- Chargement des infos utilisateurs ----*/
if(!empty($_GET['id'])) {

    $id = intval($_GET['id']);

    $titre_rubrique = "Modifier l'intervenant";

    $sql_utilisateur=Utilisateur::getUtilisateurById($DB,$id);


    if (!empty($sql_utilisateur)) {

        $utilisateur=$sql_utilisateur[0];

    } else {
        $_SESSION['erreur']="L'utilisateur n'existe pas";
        header('location:lst_utilisateurs.php');
        exit();
    }
}

// AUTORISE L'ADMIN PRINCIPAL A MODIFIER TOUS LES CHAMPS
if ($_SESSION['role']==1) {
    $modif_champ="";
} else {
    $modif_champ="readonly";
}


switch ($utilisateur->u_role) {
    // ADMIN_PRINCIPAL
    case 1:
        require 'vue/uti/form_uti_admin_p.php';
        break;
    // MOE
    case 2:
        require 'vue/uti/form_uti_moe.php';
        break;
    // MOA
    case 3:
        require 'vue/uti/form_uti_moa.php';
        break;
    // ACQ
    case 4:
        require 'vue/uti/form_uti_acq.php';
        break;
    // LOC
    case 5:
        require 'vue/uti/form_uti_loc.php';
        break;
    // ENT
    case 6:
        require 'vue/uti/form_uti_ent.php';
        break;
    // AUTRE
    case 7:
        require 'vue/uti/form_uti_autre.php';
        break;
    // ADMIN
    case 8:
        require 'vue/uti/form_uti_admin.php';
        break;

}