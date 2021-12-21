<?php require 'includes/includes_back.php';

if(!empty($_POST)){ 

    
    //print_r($_POST['utilisateur_list']);
    
    /* ----  Ajout des utilisateurs dans le projet  ---- */
    if(!empty($_POST['utilisateur_list'])) {
                    foreach($_POST['utilisateur_list'] as $id_utilisateur_selected) {
                        
                        //print_r($id_utilisateur_selected);
                        ProjUti::insertProjUti($DB,$id_utilisateur_selected,$_SESSION["id_projet"]);
                        $_SESSION['message']="Un utilisateur a été ajouté à la liste";
                        header('location:lst_utilisateurs.php');
                        
                    }
    
    } else {
        $_SESSION['erreur']="Aucun utlisateur sélectionné";
    }    
}

$lst_uti_not_in_proj = array();
/* ----  Recherche la liste des utilisateurs non affectés au projet ---- */
$data= array(
    'id_projet'=>$_SESSION['id_projet'],
    'id_user'=>$_SESSION['id_user']
    );

$lst_utilisateurs=Utilisateur::getAllUtiforAdmin($DB,$data); 

foreach ($lst_utilisateurs as $uti_not_projet)
{
    
    $sql_rch_uti_in_projet = "SELECT pu_id,pu_p_id,pu_u_id FROM gr_proj_uti WHERE pu_u_id=".$uti_not_projet['u_id']." AND pu_p_id=".$_SESSION['id_projet'].""; 
    
    $req_rch_uti_in_projet = $DB->tquery($sql_rch_uti_in_projet);
    
    if (empty($req_rch_uti_in_projet)){
        
        $identifiant=$uti_not_projet['u_identifiant'];
        $prenom=$uti_not_projet['u_prenom'];
        $nom=$uti_not_projet['u_nom'];
        $entreprise=$uti_not_projet['u_entreprise'];
        $email=$uti_not_projet['u_email'];
        $adresse=$uti_not_projet['u_adresse'];
        $cp=$uti_not_projet['u_cp'];
        $ville=$uti_not_projet['u_ville'];
        $portable_1=$uti_not_projet['u_portable_1'];
        $telephone=$uti_not_projet['u_telephone'];
        $portable_2=$uti_not_projet['u_portable_2'];
        $description_role=$DB->descRole($uti_not_projet['u_role']);


        $lst_uti_not_in_proj[]= array (
            'u_id'=>$uti_not_projet['u_id'],
            'u_identifiant'=>$identifiant,
            'u_prenom'=>$prenom,
            'u_nom'=>$nom,
            'u_entreprise'=>$entreprise,
            'u_email'=>$email,
            'u_adresse'=>$adresse,
            'u_cp'=>$cp,
            'u_ville'=>$ville,
            'u_portable_1'=>$portable_1,
            'u_telephone'=>$telephone,
            'u_portable_2'=>$portable_2,
            'u_role'=>$description_role
        );
        
    }
}

?>

<? require 'vue/form_uti_existant.php'; ?>