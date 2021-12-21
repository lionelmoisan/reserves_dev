<?php require 'includes/includes_back.php';

if (!empty($_GET['id'])) {
    
    $id=intval($_GET['id']);
    
    $sql_utilisateur= $DB->query("SELECT u_identifiant,u_prenom,u_nom,u_email,u_password,u_adresse,u_ville,u_cp,u_portable_1,u_telephone,u_portable_2,u_role from gr_utilisateurs where u_id=:id ORDER BY u_nom",array('id'=>$id));
    
    if (!empty($sql_utilisateur)){
        
        $utilisateur=$sql_utilisateur[0];
        //var_dump($utilisateur);
            
        $code_html=utf8_decode(stripslashes($utilisateur->u_prenom))."  ".utf8_decode(stripslashes($utilisateur->u_nom))."
        <br>".utf8_decode(stripslashes($utilisateur->u_adresse))." - ".stripslashes($utilisateur->u_cp)." ".utf8_decode(stripslashes($utilisateur->u_ville))."
        <br>".stripslashes($utilisateur->u_portable_1)."
        <br>".stripslashes($utilisateur->u_portable_2)."
        <br>".stripslashes($utilisateur->u_telephone)."
        <br><a href=mailto:'".$utilisateur->u_email."'>".$utilisateur->u_email."</a>";
    
        echo $code_html;

        
    } else {
        
    }
    
}
?>