<?php require 'includes/includes_back.php';

require 'Classes/Import.php';

/**
 * Created by PhpStorm.
 * User: lionelmoisan
 * Date: 22/10/2016
 * Time: 17:15
 */


if (!empty($_FILES['Fichier']['name'])){


    $file = new SplFileObject($_FILES["Fichier"]["tmp_name"], 'r');

    $file->setFlags(SplFileObject::READ_CSV);

    $file->setCsvControl(';');

    $lst_uti = array();


    $i=0;
    foreach ($file as $row) {

        $lst_uti[$i]['nom_du_projet']= Chaines::trt_insert_string($row[0]);
        $lst_uti[$i]['role']= $row[1];
        $lst_uti[$i]['prenom']= Chaines::trt_insert_string($row[2]);
        $lst_uti[$i]['nom']= Chaines::trt_insert_string($row[3]);
        $lst_uti[$i]['nom_entreprise']= Chaines::trt_insert_string($row[4]);

        $lst_uti[$i]['societe']= Chaines::trt_insert_string($row[5]);
        $lst_uti[$i]['identifiant']= $row[6];
        $lst_uti[$i]['email']= $row[7];
        $lst_uti[$i]['adresse']= Chaines::trt_insert_string($row[8]);

        $lst_uti[$i]['ville']= Chaines::trt_insert_string($row[9]);
        $lst_uti[$i]['cp']= $row[10];
        $lst_uti[$i]['telephone_principal']= $row[11];
        $lst_uti[$i]['telephone_fixe']= $row[12];
        $lst_uti[$i]['telephone_secondaire']= $row[13];

        $i++;
    }



    /*
     * Calcul du nombre de ligne du fichier csv
     */
    $nbr_lignes = sizeof($lst_uti);

    // Parcours du tableau en ne prenant pas en compte la 1er ligne
    for($numligne=1; $numligne<$nbr_lignes; $numligne++)
    {

        $numlignereel=$numligne+1;

        /*
         * test de la présence du champ rôle, Nom du projet et N° de téléphone principal
         */

        if (!(empty($lst_uti[$numligne]['role']) || empty($lst_uti[$numligne]['nom_du_projet']) || empty($lst_uti[$numligne]['telephone_principal']) )){

            switch ($lst_uti[$numligne]['role']) {
                case "Administrateur";
                case "Maître d'oeuvre";
                case "Maître d'ouvrage";
                    if (empty($lst_uti[$numligne]['nom']) || empty($lst_uti[$numligne]['societe']) || empty($lst_uti[$numligne]['email'])  ) {
                        $msg_erreur.="Les champs 'Nom','Société','Email' sont obligatoires à la ligne ".$numlignereel."<br>";
                    }
                    break;

                case "Acquéreur":
                    if (empty($lst_uti[$numligne]['nom']) || empty($lst_uti[$numligne]['identifiant']) ) {
                        $msg_erreur.="Les champs 'Nom' et 'identifiant'  sont obligatoires à la ligne ".$numlignereel."<br>";
                    }
                    break;

                case "Locataire":

                    if (empty($lst_uti[$numligne]['nom'])) {
                        $msg_erreur.="Le champ 'Nom' est obligatoire à la ligne ".$numlignereel."<br>";
                    }
                    break;

                case "Autre intervenant":

                    if (empty($lst_uti[$numligne]['nom'])) {
                        $msg_erreur.="Les champs 'Nom' et 'Email' sont obligatoires à la ligne ".$numlignereel."<br>";
                    }

                    break;
                case "Entreprise":

                    if (empty($lst_uti[$numligne]['nom_entreprise']) || empty($lst_uti[$numligne]['email']) ) {

                        $msg_erreur.="Les champs 'Nom entreprise' et 'email' sont obligatoires à la ligne ".$numlignereel."<br>";
                    }
                    break;
            }

        } else {

            $numlignereel=$numligne+1;

            $msg_erreur.="Un des champs suivants : 'Nom du projet','Rôle' et 'N° de téléphone Principal' est absent à la ligne ".$numlignereel."<br>";

        }

    }



    $_SESSION['msg_err_users']=$msg_erreur;


    if (empty($msg_erreur)){

    /*
     *  test des informations présentes en BDD
     */

    $lst_uti_valid = array();


    for($numligne=1; $numligne<$nbr_lignes; $numligne++) {

        $numlignereel=$numligne+1;
        
        /*
        * Recherche de l'id du projet pour chaque ligne
        */
        $data = array(
            'name'=>addslashes($lst_uti[$numligne]['nom_du_projet'])
        );
        $projet=Import::getProjetByName($DB,$data);


        if (is_null($projet[0]->p_id)) {

            $msg_erreur.="Le projet présent à la ligne ".$numlignereel." n'existe pas dans l'application. Il faut peut-être le créer !<br>";
        }

        /*
        * Vérification des Rôles pour chaque ligne
        */

        $data = array(
            'role'=>$lst_uti[$numligne]['role']
        );
        $role=Import::getRoleByName($DB,$data);

        if (is_null($role[0]->r_id)) {

            $msg_erreur.="Le role présent à la ligne ".$numlignereel." n'existe pas dans l'application. Une petite d'erreur dans l'orthographe de celui-ci !<br>";

        }

    }

    if (empty($msg_erreur)){

        
        /*
         * Chargement des données dans la BDD
         */
        for($numligne=1; $numligne<$nbr_lignes; $numligne++) {

            $data = array(
                'name'=>addslashes($lst_uti[$numligne]['nom_du_projet'])
            );
            $projet=Import::getProjetByName($DB,$data);

            
            $data = array(
                'role'=>$lst_uti[$numligne]['role']
            );
            $role=Import::getRoleByName($DB,$data);
            

            /*
            * Recherche d'utilisateur existant
            */

            if ($role[0]->r_id != 5 ) {

                if (empty($lst_uti[$numligne]['identifiant'])) {
                    $data_utilisateur = array(
                        'id_projet' => $projet[0]->p_id,
                        'identifiant_user' => $lst_uti[$numligne]['email']
                    );

                } else {
                    $data_utilisateur = array(
                        'id_projet' => $projet[0]->p_id,
                        'identifiant_user' => $lst_uti[$numligne]['identifiant']
                    );

                }

                $projet_utilisateur = Import::getUserByIdentifiant($DB, $data_utilisateur);


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

            $token = sha1(uniqid(rand()));

            $data = array (
                'identifiant'=>$lst_uti[$numligne]['identifiant'],
                'prenom'=>$lst_uti[$numligne]['prenom'],
                'nom'=>$lst_uti[$numligne]['nom'],
                'societe'=>$lst_uti[$numligne]['societe'],
                'entreprise'=>$lst_uti[$numligne]['nom_entreprise'],
                'email'=>$lst_uti[$numligne]['email'],
                'password'=>$password,
                'adresse'=>$lst_uti[$numligne]['adresse'],
                'ville'=>$lst_uti[$numligne]['ville'],
                'cp'=>$lst_uti[$numligne]['cp'],
                'portable_1'=>$lst_uti[$numligne]['telephone_principal'],
                'telephone'=>$lst_uti[$numligne]['telephone_fixe'],
                'portable_2'=>$lst_uti[$numligne]['telephone_secondaire'],
                'token'=>$token,
                'actif'=>$actif,
                'role'=>intval($role[0]->r_id)
            );


            // Pour retrouver les projets d'un utilisateur en fct de son identifiant ou de son email
            if (empty($lst_uti[$numligne]['email'])) {
                $pro_uti_identifiant=$lst_uti[$numligne]['identifiant'];
            } else {
                $pro_uti_identifiant=$lst_uti[$numligne]['email'];
            }


            $ajout_utilisateur=Utilisateur::AddUtilisateur($DB,$data);

            // MISE A JOUR DE LA TABLE DE CORRESPONDANCE UTILISATEURS -> PROJETS
            if($ajout_utilisateur) {

                $sql_utilisateur = $DB->query("SELECT LAST_INSERT_ID() as last_id_user_insert", $tableau);

                $data_proj_util = array (
                    'id_projet'=>$projet[0]->p_id,
                    'id_user'=>$sql_utilisateur[0]->last_id_user_insert,
                    'pro_uti_identifiant'=>$pro_uti_identifiant,
                    'role'=>intval($role[0]->r_id)
                );

                ProjUti::insertProjUti($DB,$data_proj_util);

                $msg_erreur="OK";
            } else {
                $msg_erreur="Erreur lors de l'enregistrement des utilisateurs";
            }

            // Mise à zero de la variable
            unset($data);
            

        }

        $_SESSION['msg_err_users']='OK';

        } else {

        $_SESSION['msg_err_users']=$msg_erreur;
    }



    }

    header('location:import_tmp.php');


}?>