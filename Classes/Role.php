<?php

/**
 * Class role
 */
class Role
{

    public static function lstRole($DB,$role) {

        switch ($role) {
            case 1:
                $sql="SELECT r_id, r_description,r_trigramme from gr_roles ORDER BY r_description";
                break;
            case 2:
                $sql="SELECT r_id, r_description,r_trigramme from gr_roles where r_id = 5 OR r_id = 7  ORDER BY r_description";
                break;
            case 8:
                $sql="SELECT r_id, r_description,r_trigramme from gr_roles where r_id <> 1 ORDER BY r_description";
                break;


        }

        $sql_get_role= $DB->tquery($sql);

        return $sql_get_role;
    }


    public static function getRoleDes($DB,$id) {

        $sql_get_role_des= $DB->tquery("SELECT r_description from gr_roles WHERE  r_id=".$id);

        return $sql_get_role_des[0];

    }
    
}