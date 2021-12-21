<?php require 'includes/includes_back.php';

require_once 'Classes/Email.php';
require_once 'Classes/class.phpmailer.php';

require 'Classes/Import.php';

$lst_roles=Role::lstRole($DB,$_SESSION['role']);

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
/*---- Traitement des infos utilisateurs après validation ----*/
if(!empty($_POST)){
    // Traitement des données du formulaire

    $prenom=Chaines::trt_insert_string($_POST['prenom']);
    $nom=Chaines::trt_insert_string($_POST['nom']);

    $societe=Chaines::trt_insert_string($_POST['societe']);

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

    // Test de présence d'un utilisateur dans le projet
    // Test du mode d'identification : Email / identifiant
    if (empty($email)) {
        $identifiant_valeur = $identifiant;
    } else {
        $identifiant_valeur = $email;
    }

    /*
     * ne pas tester la présence si email et identifiant sont vide
     */
    if (empty($email) && empty($identifiant)) {

        $presence_uti = null;

    } else {
        $presence_uti = ProjUti::getProUtiRole($DB,$_SESSION['id_projet'],$identifiant_valeur);

    }


    if (!is_null($presence_uti)) {
        $_SESSION['erreur'] ="L'utilisateur '".$identifiant_valeur."' existe déjà dans le projet";
        header('location:aj_utilisateur.php');
        exit();

    } else {

        /*---- Création d'un nouveau utilisateur ----*/

        /*
        * Recherche d'utilisateur existant dans d'autres projets
        */

        if ($role != 5 ) {
            
            if (empty($email)) {
                $data_utilisateur = array(
                    'id_projet' => $_SESSION['id_projet'],
                    'identifiant_user' => $identifiant
                );

            } else {
                $data_utilisateur = array(
                    'id_projet' => $_SESSION['id_projet'],
                    'identifiant_user' => $email
                );
            }

            $projet_utilisateur= Utilisateur::getUserByIdentifiantActif($DB, $data_utilisateur);


          if (!is_null($projet_utilisateur['pu_u_id'])) {

              $utilisateur=Utilisateur::getUtilisateurById($DB,$projet_utilisateur['pu_u_id']);
              

              if ($utilisateur[0]->u_actif==1){

                  $password= $utilisateur[0]->u_password;
                  $actif=1;

              } else {
                  $password=NULL;
                  $actif=0;

              }

          } else {
              $password=NULL;
              $actif=0;
          }

        } else {
            $password=NULL;
            $actif=0;

        }

        /* ---Gestion token ---- */
        $token = sha1(uniqid(rand()));

        $data = array (
            'identifiant'=>$identifiant,
            'prenom'=>$prenom,
            'nom'=>$nom,
            'societe'=>$societe,
            'entreprise'=>$entreprise,
            'email'=>$email,
            'password'=>$password,
            'adresse'=>$adresse,
            'ville'=>$ville,
            'cp'=>$code_postal,
            'portable_1'=>$portable_1,
            'telephone'=>$telephone,
            'portable_2'=>$portable_2,
            'token'=>$token,
            'actif'=>$actif,
            'role'=>$role
        );

        // Pour retrouver les projets d'un utilisateur en fct de son identifiant ou de son email
        if (empty($email)) {
            $pro_uti_identifiant=$identifiant;
        } else {
            $pro_uti_identifiant=$email;
        }

        $ajout_utilisateur=Utilisateur::AddUtilisateur($DB,$data);

        // MISE A JOUR DE LA TABLE DE CORRESPONDANCE UTILISATEURS -> PROJETS
        if($ajout_utilisateur) {

            $sql_utilisateur = $DB->query("SELECT LAST_INSERT_ID() as last_id_user_insert", $tableau);

            $data_proj_util = array (
                'id_projet'=>$_SESSION['id_projet'],
                'id_user'=>$sql_utilisateur[0]->last_id_user_insert,
                'pro_uti_identifiant'=>$pro_uti_identifiant,
                'role'=>$role
            );

            ProjUti::insertProjUti($DB,$data_proj_util);

            /* Si compte utilisateur déjà actif */
            /* Envoie email différent que lors de la création d'un compte */
            if ($actif==1) {

                /*
                * =====Déclaration des messages au format texte et au format HTML.
                */
                $message_html ="<html><head></head><body>";
                $message_html.= "Madame,Monsieur<br/><br/>En vous connectant à la plate forme Ektis-réserves avec votre identifiant et votre mot de passe habituel, vous avez dorénavant accès au projet '".$_SESSION['nom_projet']."'<br><br>";
                $message_html.='Cordialement';
                $message_html.='</body></html>';

                /**
                 * SEND CHECK EMAIL
                 */
                $data_email = array(
                    'email' => $email,
                    'sujet' => utf8_decode($_SESSION['nom_projet'] . ' - Information'),
                    'body' => $message_html
                );

                if (isset($data_email['email'])) {
                    $send_email = Email::sendEmailconnexion($DB, $data_email);
                }

                $_SESSION['message']="Le nouvel intervenant a bien été créé";


            } else {


            /**
             * SEND CHECK EMAIL
             */

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

            if (isset($data_email['email'])) {
                $send_email = Email::sendEmailconnexion($DB, $data_email);
            }


            switch ($role) {
                // ADMIN_PRINCIPAL
                case 1:
                case 2:
                case 3:
                case 6:
                case 8:
                $_SESSION['message']="Le nouvel intervenant a bien été créé. Un email de connexion lui est automatiquement envoyé.";
                    break;
                // ACQ
                case 4:
                    $_SESSION['message']="Le nouvel intervenant a bien été créé.Si vous avez renseigné l'adresse email, un email de connexion lui est automatiquement envoyé.";
                    break;
                // LOC
                case 5:
                case 7:
                $_SESSION['message']="Le nouvel intervenant a bien été créé";
                    break;
            }

            }

            header('location:lst_utilisateurs.php');
            exit();

        } else {
            $_SESSION['erreur'] ="Un problème est survenu lors de l'enregistrement d'un utilisateur";
        }
    }
}
if(empty($_GET['id'])) {
    require 'vue/form_uti_ajout.php';
}
?>

