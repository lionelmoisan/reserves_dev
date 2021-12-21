<?php
/**
 * Created by PhpStorm.
 * User: lionelmoisan
 * Date: 23/01/2017
 * Time: 19:19
 */

require 'includes/includes_back.php';

require 'Classes/Import.php';

if (!empty($_FILES['Fichier']['name'])) {

    $file = new SplFileObject($_FILES['Fichier']['tmp_name'], 'r');

    $file->setFlags(SplFileObject::READ_CSV);

    $file->setCsvControl(';');

    $lst_reserve = array();

    $i = 0;

    unset($_SESSION['msg_err_reserve']);

    foreach ($file as $row) {

        $lst_reserve[$i]['nom_du_projet'] = Chaines::trt_insert_string($row[0]);
        $lst_reserve[$i]['numero_lot'] = Chaines::trt_insert_string($row[1]);
        $lst_reserve[$i]['description'] = Chaines::trt_insert_string($row[2]);
        $lst_reserve[$i]['piece'] = Chaines::trt_insert_string($row[3]);
        $lst_reserve[$i]['type'] = $row[4];
        $lst_reserve[$i]['date_signalement'] = $row[5];
        $lst_reserve[$i]['email_entreprise'] = Chaines::trt_insert_string($row[6]);
        $lst_reserve[$i]['statut'] = Chaines::trt_insert_string($row[7]);
        $lst_reserve[$i]['date_quitus'] = utf8_encode($row[8]);

        $i++;
    }

    //var_dump($lst_reserve);

    $nbr_lignes = sizeof($lst_reserve);

    // Parcours du tableau en ne prenant pas en compte la 1er ligne
    for ($numligne = 1; $numligne < $nbr_lignes; $numligne++) {

        $numlignereel = $numligne + 1;

        /*
        * test de la présence du champ nom du projet,numéro de lot, description, type, nom entreprise
        */

        //var_dump($lst_reserve[$numligne]);

        if (!(empty($lst_reserve[$numligne]['nom_du_projet']) || empty($lst_reserve[$numligne]['numero_lot'])
            || empty($lst_reserve[$numligne]['description']) || empty($lst_reserve[$numligne]['piece']) || empty($lst_reserve[$numligne]['type'])
            || empty($lst_reserve[$numligne]['email_entreprise']) || empty($lst_reserve[$numligne]['statut']))
        ) {


            /*
             *  test de présence du projet
             */
            $data = array(
                'name' => $lst_reserve[$numligne]['nom_du_projet']
            );
            $projet = Import::getProjetByName($DB, $data);

            if (is_null($projet[0]->p_id)) {

                $msg_erreur .= "Le projet présent à la ligne " . $numlignereel . " n'existe pas dans la plateforme.<br>";
            }

            /*
             *  test de présence du lot
             */
            $data = array(
                'numero_lot' => $lst_reserve[$numligne]['numero_lot'],
                'id_projet' => $projet[0]->p_id
            );
            $lot = Import::getlotByName($DB, $data);


            if (is_null($lot[0]->l_id)) {

                $msg_erreur .= "Le lot présent à la ligne " . $numlignereel . " n'existe pas dans le liste des lots du projet.<br>";
            }

            if ($lst_reserve[$numligne]['type'] == 'GPA') {

                if (empty($lst_reserve[$numligne]['date_signalement'])) {
                    $msg_erreur .= "La date de signalement est obligatoire à la ligne " . $numlignereel . "<br>";
                }
            }


            /*
            *  recherche de l'entreprise dans la BDD en fonction de son nom et du nom du projet
            */
            $data_entreprise = array(
                'id_projet' => $projet[0]->p_id,
                'email_entreprise' => $lst_reserve[$numligne]['email_entreprise'],
                'role' => 6
            );

            $entreprise = Import::getEntrepriseByEmailProjetRole($DB, $data_entreprise);

            if (is_null($entreprise['u_id'])) {

                $msg_erreur .= "L'entreprise présent à la ligne " . $numlignereel . " n'existe pas dans la liste des utilisateurs du projet.<br>";
            }


            /* recherche id statut  */
            $data_statut = array(
                'nom_statut' => $lst_reserve[$numligne]['statut']
            );


            $statut = import::getStatutByName($DB, $data_statut);

            if (is_null($statut['ls_id'])) {

                $msg_erreur .= "Le statut présent à la ligne " . $numlignereel . " n'existe pas dans la liste des status.<br>";

            } else {

                /*
                if ($statut['ls_id']!=1) {

                    if (empty($lst_reserve[$numligne]['date_quitus'])) {

                        $msg_erreur .= "La date de quitus présent à la ligne " . $numlignereel . " doit être renseignée.<br>";
                    }

                } else {


                    if(!empty($lst_reserve[$numligne]['date_quitus'])) {
                        $msg_erreur .= "La date de quitus présent à la ligne " . $numlignereel . " doit être vide pour les réserves non-levée.<br>";

                    }
                }*/

            }

        } else {
            $msg_erreur .= "Les champs 'Nom du projet','N° du lot','Description','pièce' 'type', 'Nom de l'entreprise' et 'statut' sont obligatoires à la ligne " . $numlignereel . "<br>";
        }

    }

    //var_dump($msg_erreur);

    $_SESSION['msg_err_reserve']=$msg_erreur;

    /* Enregistrement des réserve */
    if (empty($msg_erreur)) {

        $date_jour = date("Y-m-d H:i:s");

        for ($numligne = 1; $numligne < $nbr_lignes; $numligne++) {

            /*
            * récuperer id projet
            */

            $data = array(
                'name' => addslashes($lst_reserve[$numligne]['nom_du_projet'])
            );
            $projet = Import::getProjetByName($DB, $data);

            /*
            * récuperer id lot
            */
            $data = array(
                'numero_lot' => $lst_reserve[$numligne]['numero_lot'],
                'id_projet' => $projet[0]->p_id
            );
            $lot = Import::getlotByName($DB, $data);

            /*
            * récuperer id entreprise
            */
            $data_entreprise = array(
                'id_projet' => $projet[0]->p_id,
                'email_entreprise' => $lst_reserve[$numligne]['email_entreprise'],
                'role' => 6
            );

            $entreprise = Import::getEntrepriseByEmailProjetRole($DB, $data_entreprise);


            if (empty($lst_reserve[$numligne]['date_signalement'])) {

                $info_lot = lot::getLotById($DB, $lot[0]->l_id);

                $date_signalement = Db::convertDate($info_lot->l_date_livraison);

            } else {
                $date_signalement = Db::convertDate($lst_reserve[$numligne]['date_signalement']);

            }

            /* recherche id statut  */
            $data_statut = array(
                'nom_statut' => $lst_reserve[$numligne]['statut']
            );


            $statut = import::getStatutByName($DB, $data_statut);


            $data = array(
                'id_lot' => $lot[0]->l_id,
                'description' => $lst_reserve[$numligne]['description'],
                'piece' => $lst_reserve[$numligne]['piece'],
                'type' => $lst_reserve[$numligne]['type'],
                'date_signalement' => $date_signalement,
                'date_creation' => $date_jour,
                'date_modifier' => $date_jour,
                'statut' => $statut['ls_id'],
                'id_entreprise' => $entreprise['u_id'],
            );

            //var_dump($data);

            $AjoutReserve = Reserve::AddReserve($DB, $data);

            $info_reserve=Reserve::LastIdreserve($DB);


            if (empty($lst_reserve[$numligne]['date_quitus'])) {

                $date_quitus = $date_jour;

            } else {
                $date_quitus = Db::convertDate($lst_reserve[$numligne]['date_quitus']);
            }

            $data_histo = array (
                'r_id'=> $info_reserve['r_id'],
                'ls_id'=> $statut['ls_id'],
                'u_id'=> $_SESSION['id_user'],
                'date_modifier'=> $date_quitus
            );

            //var_dump($data_histo);

            $insert_histo_statut=Reserve::AddHistoStatut($DB,$data_histo);


            $data = array(
                'id_statut'=>$statut['ls_id'],
                'id_reserve'=>$info_reserve['r_id'],
                'date_modifier'=>$date_quitus
            );

            $update_statut_reserve=Reserve::UpdateStatutReserve($DB,$data);


        }

        $_SESSION['msg_err_reserve']='OK';

    }

    header('location:import_tmp.php');

}
?>
