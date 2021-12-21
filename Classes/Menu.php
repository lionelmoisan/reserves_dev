<?php 
/**
* Réserves
*/
class Menu{ 


    public static function GetMenu($DB,$id_role) {
        
        /* recherche le menu */    
    $sql_get_menu = $DB->tquery('SELECT m_description,m_icone,m_lien FROM gr_menu,gr_menu_role where m_id=mr_m_id AND mr_r_id='.$id_role);
        
        return $sql_get_menu;
        
    }

}