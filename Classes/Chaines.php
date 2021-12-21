<?php
/**
 * Gestion des chaines de caractères
 */
class Chaines{

    public function replace_accents_maj($string){

        $lst_tab_carac_before= array( 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý','Œ','œ');

        $lst_tab_carac_afer= array( '&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;', '&Ccedil;', '&Egrave;','&Eacute;','&Ecirc;','&Euml;', '&Igrave;','&Iacute;','&Icirc;','&Iuml;', '&Ntilde;', '&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;', '&Ugrave;','&Uacute;','&Ucirc;','&Uuml;', '&Yacute;','&OElig;','&oelig;');

        return str_replace($lst_tab_carac_before,$lst_tab_carac_afer, $string);
    }


    public static function trt_insert_string($string){

        $chaine = self::replace_accents_maj($string);

        $chaine = addslashes($chaine);

        $chaine = utf8_encode($chaine);

        return $chaine;

    }


    public static function trt_select_string($string) {

        $chaine = stripslashes($string);

        $chaine = utf8_decode($chaine);

        return $chaine;

    }

}

