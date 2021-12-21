<?php require 'includes/includes.php'; ?>
<?php

if(isset($_GET) && !empty($_GET['id'])) {
    
    $id_projet=intval($_GET['id']);
       
    $nom_projet=Projet::getNomProjet($DB,$id_projet);

    if (!empty($_SESSION["email"])) {
        $role_utilisateur=ProjUti::getProUtiRole($DB,$id_projet,$_SESSION["email"]);
    } else {
        $role_utilisateur=ProjUti::getProUtiRole($DB,$id_projet,$_SESSION["identifiant"]);
    }


    $_SESSION["id_user"]=$role_utilisateur["pu_u_id"];
    $_SESSION["id_projet"]=$id_projet;
    $_SESSION["nom_projet"]=$nom_projet->p_description;

    $_SESSION["logo_projet"]=$nom_projet->p_logo_nom;
    
    $_SESSION['role']=$role_utilisateur["pu_u_role"];


    header('location:index.php');
}

?>