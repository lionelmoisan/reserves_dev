<?php require 'includes/includes_back.php';
/*
 * Chargement des données d'un projet en fct de son ID
 */
if(!empty($_GET['id'])){

	$id=intval($_GET['id']);

    $titre_rubrique = "Modifier le projet";

    $projet_update=projet::getProjetByid($DB,$id);

    if (empty($projet_update)){
        $_SESSION['erreur']="Le projet n'existe pas";
        header('location:lst_projets.php');
        exit();
    }
 /*
  * Nouveau Projet
  */
} else {
    $titre_rubrique="Ajouter un nouveau projet";   
	$projet_update="";
}

/*
 * Le formulaire a été posté et les champs sont différents de vide
 */

if(!empty($_POST)){

    $description=addslashes($_POST['description']);


    if (isset($_POST['module_GPA'])) {

        $module_GPA=1;
    } else {
        $module_GPA=0;

    }


    /*
     * Maj d'un projet
     */
    if (!empty($_GET['id'])) {
        /*
         * le logo est différent de vide
         */
        if (!empty($_FILES['logo']['name'])) {

            $info_upload=projet::trtuploadfile($_FILES['logo'],$_GET['id']);

            if (is_null($info_upload["message"])) {

                $data = array(
                    'id'=>$_GET['id'],
                    'p_description'=>$description,
                    'p_logo_nom'=>$info_upload['nom_logo'],
                    'module_gpa'=>$module_GPA
                );

                $update_projet=projet::upProjetByid($DB,$data);

                if ($update_projet){
                    $_SESSION['message']="Le projet a été mis à jour";
                    header('location:lst_projets.php');
                    exit();
                }

            } else {

                $_SESSION['message']=$info_upload['message'];
                header("location:aj_projet.php?id={$_GET['id']}");
                exit();
            }

         /* pas de fichier dans le post  */
        } else {

            $data = array(
                'id'=>$_GET['id'],
                'p_description'=>$description,
                'p_logo_nom'=>$_SESSION["logo_projet"],
                'module_gpa'=>$module_GPA
            );


            $update_projet=projet::upProjetByid($DB,$data);

            if ($update_projet){
                $_SESSION['message']="Le projet a été mis à jour";
                header('location:lst_projets.php');
                exit();
            }
        }
    /*
     * Nouveau Projet
     */
    } else {

        /*
         * Présence du logo
         */
        if (!empty($_FILES['logo']['name'])) {

            $info_upload=projet::trtuploadfile($_FILES['logo'],$_GET['id']);

            if (is_null($info_upload["message"])) {

                $data = array(
                    'p_description'=>$description,
                    'p_logo_nom'=>$info_upload['nom_logo']
                );

            } else {

                $_SESSION['message']=$info_upload['message'];
                header("location:aj_projet.php?id={$_GET['id']}");
                exit();
            }

            /* pas de fichier dans le post  */
        } else {

            $data = array(
                'p_description'=>$description,
                'p_logo_nom'=>NULL,
                'module_gpa'=>$module_GPA
            );

        }

        $update_projet=projet::insertProjet($DB,$data);


        if ($update_projet){

            /* CREATION DES DELAIS PAR DEFAUT */

            $sql_projet = $DB->query("SELECT LAST_INSERT_ID() as last_id_projet_insert");

            $data_delais = array(
                'id_projet' => $sql_projet[0]->last_id_projet_insert,
                'nbr_jour_livraison' => 0,
                'nbr_jour_gpa' => 0,
            );

            $insert_delais=Delais::setDelaisContractuels($DB,$data_delais);

            if ($insert_delais) {
                /*
                 *  AJOUT DU PROJET POUR L'UTILISATEUR
                */

                $data_proj_util = array(
                    'id_projet' => $sql_projet[0]->last_id_projet_insert,
                    'id_user' => $_SESSION['id_user'],
                    'pro_uti_identifiant' => $_SESSION['email'],
                    'role' => $_SESSION['role']
                );

                $insert_ProUti=ProjUti::insertProjUti($DB, $data_proj_util);

                if ($insert_ProUti) {
                    $_SESSION['message']="Le projet a été ajouté avec succès";
                    header('location:lst_projets.php');
                    exit();
                }

            }

        }

    }
}
?>

<?php include 'vue/form_projet.php'; ?>