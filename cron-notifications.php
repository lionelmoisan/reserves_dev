#!/usr/local/bin/php

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Test de Cron</title>
</head>
<body>
<h2> Le test commence </h2>
<?php
$path = dirname( __FILE__ );

define('ROOT',dirname( __FILE__ ));

include_once ROOT. '/config.php';
include_once ROOT. '/Classes/Db.php';
include_once ROOT. '/Classes/auth.php';
include_once ROOT. '/Classes/ProUti.php';
include_once ROOT. '/Classes/projet.php';
include_once ROOT. '/Classes/Utilisateur.php';

include_once ROOT. '/Classes/lot.php';
include_once ROOT. '/Classes/Reserve.php';

include_once ROOT. '/Classes/Email.php';
include_once ROOT. '/Classes/class.phpmailer.php';

$DB = new Db();

/*
 *  Notifications des lévées de réserves
 */

$lst_projet=projet::getAllProjets($DB);


//var_dump($lst_projet);

foreach ($lst_projet as $projet) {

    if ($projet->p_id <> 1) {

       $lst_lots=lot::getLots($DB,$projet->p_id);

        foreach ($lst_lots as $lot) {

            $data=array(
                'id_lot'=> $lot['l_id']
            );

            $lst_reserve=Reserve::GetReserveAsChange($DB,$data);

            unset($detail_reserve);

            foreach ($lst_reserve as $reserve) {

                $detail_reserve.=utf8_decode(stripslashes($lot['l_numero_lot']))." -- ".utf8_decode(stripslashes($reserve['r_description']))."<br>";

            }

            if (isset($detail_reserve)) {

                $infos_acquereur=Utilisateur::getUtilisateurById($DB,$lot['l_id_acquereur']);

                //var_dump($infos_acquereur[0]->u_email);

                if (!($infos_acquereur[0]->u_email == '')) {

                    $text_header = "Madame, Monsieur " . utf8_decode(stripslashes($infos_acquereur[0]->u_nom)) . "<br><br>";
                    $text_header .= "Dans le cadre du projet - " . $projet->p_description . "<br><br>";
                    $text_header .= "Nous vous informons que les réserves suivantes ont fait l'objet d'une intervention ce jour : <br><br>";

                    $text_footer = "<br><br>Vous pouvez accédez à la liste complète à jour, à l'historique complet de vos réserves et GPA par le lien : <a href='http://ektis-reserves.fr/login.php' target='_blank'>http://ektis-reserves.fr/login</a><br><br>";
                    $text_footer .= "Bien cordialement<br>";

                    $data = array(
                        'email' => $infos_acquereur[0]->u_email,
                        'sujet' => utf8_decode($projet->p_description . " - Intervention sur vos réserves"),
                        'txt-relance' => $text_header . $detail_reserve . $text_footer
                    );

                    $etat_email = Email::SendEmailautomatique($DB, $data);

                    /*
                     * Enregistrement des infos en BDD
                     */
                    $datedujour = date("Y-m-d");

                    $data_histo = array(
                        'id_projet' => $projet->p_id,
                        'datedujour' => $datedujour,
                        'sujet' => 'Réserves levées',
                        'id_acquereur' => $lot['l_id_acquereur'],
                    );

                    //var_dump($data_histo);

                    $sql_insert_notifications = 'INSERT INTO gr_notifications(n_p_id,n_date,n_sujet,n_id_acquereur) VALUES (:id_projet,:datedujour,:sujet,:id_acquereur)';


                    $today = date("F j, Y, g:i a");
                    $line1 = $path;
                    $line2 .= $infos_acquereur[0]->u_email . " - ";


                    $fp = fopen(ROOT . '/cron-notification.log', 'a');
                    fwrite($fp, "\r\n -----------------TEST----------------------------------- \r\n");
                    fwrite($fp, $today . "\r\n");
                    fwrite($fp, $line1 . "\r\n");
                    fwrite($fp, $line2 . "\r\n");
                    fwrite($fp, "Résultat envoi Mail : " . $etat_email . "\r\n");
                    fwrite($fp, "\r\n -------------------------------------------------------- \r\n");
                    fclose($fp);

                    $req = $DB->insert($sql_insert_notifications, $data_histo);

                }
                
                
            }

        }

    }
}?>
<h2> Le test est terminé </h2>

</body>
</html>