<?php require 'includes/includes_back.php'; ?>
<?php

/* Recherche les projets de l'utilisateur */
//var_dump($_SERVER["DOCUMENT_ROOT"]);

if (!empty($_SESSION["email"])) {
    $lst_projet_uti=ProjUti::getProUti($DB,$_SESSION["email"]);

} else {
    $lst_projet_uti=ProjUti::getProUti($DB,$_SESSION["identifiant"]);
}

$infos_projet=projet::getProjetByid($DB,$_SESSION["id_projet"]);


$_SESSION['module_signalement_GPA'] = $infos_projet->p_module_GPA;


$lst_lots=lot::getLots($DB,$_SESSION["id_projet"]);


$lst_lots_acq=lot::getLotsByacquereur($DB,$_SESSION["id_projet"],$_SESSION["id_user"]);

$data = array(
            'role'=>6,
            'id_projet'=>$_SESSION["id_projet"]
);

$lst_entreprises=Utilisateur::getEntreprise($DB,$data);

/* stockage en session de la description du rôle */
$tab_role=$DB->lstRole($_SESSION['role']);

$_SESSION['description_role'] = $tab_role[0]['r_description'];

?>

<?php require 'includes/header.php'?>

<?php 

switch ($_SESSION['role']) {
    case 1:
        require 'vue/form_reserve.php';
        break;
    case 2:
         require 'vue/form_reserve_moe.php';
        break;
    case 3:
         require 'vue/form_reserve_moa.php';
        break;
    case 4:
         require 'vue/form_reserve_acquereur.php';
        break;
    case 5:
        require 'vue/form_reserve_locataire.php';
        break;
    case 6:
        require 'vue/form_reserve_ent.php';
        break;
    case 8:
        require 'vue/form_reserve.php';
        break;
}

?>

<a href="mailto:contact@ektis-reserves.fr">Signaler un problème</a>


<?php require 'includes/footer.php'?>