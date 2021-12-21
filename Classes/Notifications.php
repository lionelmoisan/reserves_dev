<?php
/**
 * Created by PhpStorm.
 * User: lionelmoisan
 * Date: 22/10/2016
 * Time: 11:03
 */

class Notifications {

    public static function setNotifications($DB,$data) {

        $sql_insert_notification = 'INSERT INTO gr_notifications (n_p_id,n_date,n_sujet,n_id_acquereur) VALUES (:projet_id,:date_notification,:sujet,:id_acquereur)';

        $req_insert_notification = $DB->insert($sql_insert_notification,$data);


        if(!empty($req_insert_notification)){
            return true;
        } else {
            return false;
        }
    }
}