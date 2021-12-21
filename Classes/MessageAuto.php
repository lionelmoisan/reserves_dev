<?php 
/**
* Projet Utilisateur
*/
class MessageAuto {

    public static function getnotification($DB,$cPage,$notparpage,$id_projet,$role) {
        
        if ($role == '4') {
            $sql_get_histo_mes_auto= $DB->tquery("SELECT n_date,n_sujet,n_id_acquereur FROM gr_notifications WHERE n_p_id=".$id_projet." AND n_id_acquereur=".$_SESSION['id_user']." ORDER BY n_date DESC LIMIT ".(($cPage-1)*$notparpage).",$notparpage");

        } else {
            $sql_get_histo_mes_auto= $DB->tquery("SELECT n_date,n_sujet,n_id_acquereur FROM gr_notifications WHERE n_p_id=".$id_projet." ORDER BY n_date DESC LIMIT ".(($cPage-1)*$notparpage).",$notparpage");
        }

        return $sql_get_histo_mes_auto;
    }

    public static function getnbrnotification($DB,$id_projet,$role) {

        if ($role == '4') {
            $sql_get_nbr_notification = $DB->tquery("SELECT COUNT(n_id) as nbr FROM gr_notifications WHERE n_p_id=".$id_projet." AND n_id_acquereur=".$_SESSION['id_user']);
        } else {
            $sql_get_nbr_notification = $DB->tquery("SELECT COUNT(n_id) as nbr FROM gr_notifications WHERE n_p_id=".$id_projet);

        }
        return $sql_get_nbr_notification[0]["nbr"];

    }
}