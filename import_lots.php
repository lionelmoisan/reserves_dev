<?php require 'includes/includes_back.php';

require 'Classes/Import.php';

if (!empty($_FILES['Fichier']['name'])) {

    $file = new SplFileObject($_FILES['Fichier']['tmp_name'], 'r');

    $file->setFlags(SplFileObject::READ_CSV);

    $file->setCsvControl(';');

    $lst_lot = array();

    $i = 0;
    foreach ($file as $row) {

        $lst_lot[$i]['nom_du_projet'] = Chaines::trt_insert_string($row[0]);
        $lst_lot[$i]['numero_lot'] = Chaines::trt_insert_string($row[1]);
        $lst_lot[$i]['date_livraison'] = $row[2];
        $lst_lot[$i]['date_reception'] = $row[3];
        $lst_lot[$i]['identifiant_acquereur'] = $row[4];
        $lst_lot[$i]['nom_locataire'] = $row[5];
        $lst_lot[$i]['contact_rdv'] = Chaines::trt_insert_string($row[6]);
        $lst_lot[$i]['autre_contact'] = $row[7];

        $i++;
    }


    /*
     * Calcul du nombre de ligne du fichier csv
     */
    $nbr_lignes = sizeof($lst_lot);

    // Parcours du tableau en ne prenant pas en compte la 1er ligne
    for ($numligne = 1; $numligne < $nbr_lignes; $numligne++) {

        $numlignereel = $numligne + 1;

        /*
        * test de la présence du champ nom du projet,numéro de lot et date de livraison
        */

        if (!(empty($lst_lot[$numligne]['nom_du_projet']) || empty($lst_lot[$numligne]['numero_lot']) || empty($lst_lot[$numligne]['date_livraison']) || empty($lst_lot[$numligne]['contact_rdv']))) {

            /*
             *  test de présence du projet
             */
            $data = array(
                'name' => addslashes($lst_lot[$numligne]['nom_du_projet'])
            );
            $projet = Import::getProjetByName($DB, $data);

            if (is_null($projet[0]->p_id)) {

                $msg_erreur .= "Le projet présent à la ligne " . $numlignereel . " n'existe pas dans la plateforme.<br>";
            }


            if ($lst_lot[$numligne]['contact_rdv'] == 'autre') {

                if (empty($lst_lot[$numligne]['autre_contact'])) {

                    $msg_erreur = "Le champ 'Autre contact' est obligatoire à la ligne " . $numlignereel . "<br>";

                }
            }

            if (!empty($lst_lot[$numligne]['identifiant_acquereur'])) {

                /*
                 *  recherche de l'utilisateur dans la BDD en fonction de son email et du nom du projet
                 */
                $data_acquereur = array(
                    'id_projet' => $projet[0]->p_id,
                    'identifiant_user' => $lst_lot[$numligne]['identifiant_acquereur'],
                    'role' => 4
                );
                $acquereur = Import::getUserByIdentifiantProjetRole($DB, $data_acquereur);


                if (is_null($acquereur['u_id'])) {

                    $msg_erreur .= "L'acquéreur présent à la ligne " . $numlignereel . " n'existe pas dans la liste des utilisateurs du projet.<br>";
                }

            }

            if (!empty($lst_lot[$numligne]['nom_locataire'])) {

                /*
                 *  recherche de l'utilisateur dans la BDD en fonction de son nom et du nom du projet
                 */

                $data_locataire = array(
                    'id_projet' => $projet[0]->p_id,
                    'nom_user' => $lst_lot[$numligne]['nom_locataire'],
                    'role' => 5
                );

                $locataire = Import::getUserByNameProjetRole($DB, $data_locataire);


                if (is_null($locataire['u_id'])) {

                    $msg_erreur .= "Le locataire présent à la ligne " . $numlignereel . " n'existe pas dans la liste des utilisateurs du projet.<br>";
                }

            }

            if (!empty($lst_lot[$numligne]['contact_rdv'])) {

                if (($lst_lot[$numligne]['contact_rdv'] != 'loc') && ($lst_lot[$numligne]['contact_rdv'] != 'acq') && ($lst_lot[$numligne]['contact_rdv'] != 'autre')) {

                    $msg_erreur .= "Le champ Contact pour prise de RDV présent à la ligne " . $numlignereel . " doit être renseigné avec l'une des valeurs suivantes : loc - acq - autre.<br>";

                } elseif ($lst_lot[$numligne]['contact_rdv'] == 'autre') {

                    if (!empty($lst_lot[$numligne]['contact_rdv'])) {

                        $data_locataire = array(
                            'id_projet' => $projet[0]->p_id,
                            'email_user' => $lst_lot[$numligne]['autre_contact'],
                        );

                        $autre_contact = Import::getUserByEmailProjet($DB, $data_locataire);


                        if (is_null($autre_contact['u_id'])) {

                            $msg_erreur .= "L'autre contact présent à la ligne " . $numlignereel . " n'existe pas dans la plateforme.<br>";
                        }

                    } else {


                        $msg_erreur .= "Le champ 'autre contact' est obligatoire à la ligne " . $numlignereel . "<br>";

                    }

                }

            }

        } else {

            $msg_erreur .= "Les champs 'Nom du projet','N° du lot', 'Date de livraison' et 'Contact pour prise de RDV' sont obligatoires à la ligne " . $numlignereel . "<br>";
        }
    }

    //var_dump($msg_erreur);

    $_SESSION['msg_err_lot']=$msg_erreur;


    /* Enregistrement des lots */
    if (empty($msg_erreur)) {

        for ($numligne = 1; $numligne < $nbr_lignes; $numligne++) {

            $data = array(
                'name' => addslashes($lst_lot[$numligne]['nom_du_projet'])
            );
            $projet = Import::getProjetByName($DB, $data);


            /*
            *  recherche de l'acquereur dans la BDD en fonction de son nom et du nom du projet
            */
            $data_acquereur = array(
                'id_projet' => $projet[0]->p_id,
                'identifiant_user' => $lst_lot[$numligne]['identifiant_acquereur'],
                'role' => 4
            );
            $acquereur = Import::getUserByIdentifiantProjetRole($DB, $data_acquereur);


            /*
            *  recherche de du locataire dans la BDD en fonction de son nom et du nom du projet
            */
            $data_locataire = array(
                'id_projet' => $projet[0]->p_id,
                'nom_user' => $lst_lot[$numligne]['nom_locataire'],
                'role' => 5
            );

            $locataire = Import::getUserByNameProjetRole($DB, $data_locataire);


            switch ($lst_lot[$numligne]['contact_rdv']) {
                case "acq";
                    $id_autre_contact = $acquereur['u_id'];

                    break;
                case "loc";
                    $id_autre_contact = $locataire['u_id'];

                    break;
                case "autre";

                    $data_autre_contact = array(
                        'id_projet' => $projet[0]->p_id,
                        'email_user' => $lst_lot[$numligne]['autre_contact'],
                    );

                    $autre_contact = Import::getUserByEmailProjet($DB, $data_autre_contact);

                    $id_autre_contact = $autre_contact['u_id'];

                    break;
            }

            $date_livraison = Db::convertDate($lst_lot[$numligne]['date_livraison']);

            if (!empty($lst_lot[$numligne]['date_reception'])) {
                $date_reception = Db::convertDate($lst_lot[$numligne]['date_reception']);
            } else {
                $date_reception = NULL;

            }


            $data = array(
                'numero_lot' => $lst_lot[$numligne]['numero_lot'],
                'date_livraison' => $date_livraison,
                'date_reception' => $date_reception,
                'id_acquereur' => $acquereur['u_id'],
                'id_locataire' => $locataire['u_id'],
                'choix_contact' => $lst_lot[$numligne]['contact_rdv'],
                'id_contact' => $id_autre_contact,
                'id_projet' => $projet[0]->p_id
            );


            $sql_insert_lot = 'INSERT INTO gr_lots (l_numero_lot,l_date_livraison,l_date_reception,l_id_acquereur,l_id_locataire,l_choix_contact,l_id_contact,l_id_projet) VALUES (:numero_lot,:date_livraison,:date_reception,:id_acquereur,:id_locataire,:choix_contact,:id_contact,:id_projet)';

            $req = $DB->insert($sql_insert_lot, $data);

            if ($req) {
                $etat_enregistrement .= "";

            } else {

                $etat_enregistrement .= "erreur ligne" . $numligne . "<br>";
            }


        }
        $_SESSION['msg_err_lot']='OK';

    }

    header('location:import_tmp.php');


}