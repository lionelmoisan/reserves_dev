<?php require 'includes/includes_back.php';

$data_choix_contact = array (
    'acq'=>'acquéreur',
    'loc'=>'locataire',
    'autre'=>'autre'
);


/*---- Liste des droits sans l'administrateur ----*/

$role_acquereur=$DB->lstRole(4);

$role_locataire=$DB->lstRole(5);

$role_autre_intervenant=$DB->lstRole(7);


/* ---- Recherche des utilisateurs de type acquéreur ----*/
$data = array(
            'role'=>4,
            'id_projet'=>$_SESSION["id_projet"]
        );

$lst_acquereurs=Utilisateur::getAcquereur($DB,$data);

/* ---- Recherche des utilisateurs de type locataire ----*/
$data = array(
            'role'=>5,
            'id_projet'=>$_SESSION["id_projet"]
        );

$lst_locataires=Utilisateur::getLocataire($DB,$data);



/* ---- Recherche des utilisateurs MOE,MOA,ADMIN autres contacts ---*/
$data = array(
            'id_projet'=>$_SESSION["id_projet"]
        );

$lst_contacts=Utilisateur::getContact($DB,$data);


if(!empty($_GET)){

    $id=intval($_GET['id']);
    
    $titre_rubrique = "Modifier le lot";
    
    $lot=lot::getLotById($DB,$id);

    /* -- Recherche les informations de l'acquéreur--*/
    $info_acquereur=Utilisateur::getUtilisateurById($DB,$lot->l_id_acquereur);
    
    /* -- Recherche les informations du locataire--*/
    $info_locataire=Utilisateur::getUtilisateurById($DB,$lot->l_id_locataire);

    
    /* -- Recherche les informations de l'acquéreur--*/
    $info_contact=Utilisateur::getUtilisateurById($DB,$lot->l_id_contact);

    //var_dump($lot->l_choix_contact);

    if ($lot->l_choix_contact=='acq') {
        $afficher='autre_display_none';

    } elseif ($lot->l_choix_contact=='loc') {
        $afficher='autre_display_none';

    } elseif ($lot->l_choix_contact=='autre') {
        $afficher='autre_display_block';

    } elseif (is_null($lot->l_choix_contact)) {
        $afficher='autre_display_none';
    }
    
    // LIMIT LES MODIFICATION POUR LE MOE
    if ($_SESSION['role']==2) {
        $modif_champ_numero_lot="disabled";
        $modif_champ_date_livraison="disabled";
        $modif_champ="disabled";
    } else {
        $modif_champ_lot="data-validation='letternumeric' data-validation-allowing=' -_'";
        $modif_champ_date_livraison="data-validation='date' data-validation-format='dd/mm/yyyy' ";
        $modif_champ="";
    }
    
} else {
 
   $titre_rubrique = "Ajouter un nouveau lot";

    $afficher='autre_display_none';
}

/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST)){

    $numero_lot=$_POST['numero_lot'];

    $date_livraison=$_POST['date_livraison'];

    if ($_SESSION['role']==2) {

        $numero_lot=$lot->l_numero_lot;
        $date_livraison=$lot->l_date_livraison;
    }

    if (!empty($numero_lot) && !empty($date_livraison)){

        $contact=$_POST['contact_radio'];

        $numero_lot=Chaines::trt_insert_string($numero_lot);
    
        $date_livraison=Db::convertDate($date_livraison);
        
        /* test de la date de réception */
        if (!empty($_POST['date_reception'])){
            $date_reception=Db::convertDate($_POST['date_reception']);
        } else {
            $date_reception=NULL;
        }

        // Traitement pour le contact de prise de RDV

        if (!($_POST['contact_radio'] === NULL)) {

            if ($contact=='acq') {
                if ($_SESSION['role']==2) {
                    $id_contact=$lot->l_id_acquereur;

                } else {
                    $id_contact=$_POST['lstacquereur'];

                }
                $choix_contact = 'acq';

            } elseif ($contact=='loc') {
                $id_contact=$_POST['lstlocataire'];
                $choix_contact = 'loc';
            } elseif ($contact=='autre') {
                $id_contact=$_POST['lstcontact'];
                $choix_contact = 'autre';
            }
    }

        $id_projet=$_SESSION['id_projet'];
        $id_acquereur=$_POST['lstacquereur'];
        $id_locataire=$_POST['lstlocataire'];
        //$id_contact=$_POST['lstcontact'];
              
        /*---- Traitement dans le cas d'une mise à jour d'un lot ----*/
            if (!empty($_GET['id'])) {    
                $id_lot=intval($_GET['id']);

                if ($_SESSION['role']==2) {

                    $date_reception=$lot->l_date_reception;
                    $id_acquereur=$lot->l_id_acquereur;
                }

            $data = array(
                    'id'=>$id_lot,
                    'numero_lot'=>$numero_lot,
                    'date_livraison'=>$date_livraison,
                    'date_reception'=>$date_reception,
                    'id_acquereur'=>$id_acquereur,
                    'id_locataire'=>$id_locataire,
                    'choix_contact'=>$choix_contact,   
                    'id_contact'=>$id_contact
            );


                $rep_update_lot=Lot::Majlot($DB,$data );
                
                if ($rep_update_lot){
                     $_SESSION['message']="Les informations du lot ont été mises à jour";
                        header('location:lst_lots.php');
                        exit();
                }
            
            }
        
        $data = array(
                    'numero_lot'=>$numero_lot,
                    'date_livraison'=>$date_livraison,
                    'date_reception'=>$date_reception,
                    'id_acquereur'=>$id_acquereur,
                    'id_locataire'=>$id_locataire,
                    'choix_contact'=>$choix_contact,
                    'id_contact'=>$id_contact,
                    'id_projet'=>$id_projet
        );


         $sql_insert_lot = 'INSERT INTO gr_lots (l_numero_lot,l_date_livraison,l_date_reception,l_id_acquereur,l_id_locataire,l_choix_contact,l_id_contact,l_id_projet) VALUES (:numero_lot,:date_livraison,:date_reception,:id_acquereur,:id_locataire,:choix_contact,:id_contact,:id_projet)';


        $req= $DB->insert($sql_insert_lot,$data);
        
        if($req){
            $_SESSION['message']="Le lot a été créé avec succès";
                header('location:lst_lots.php');
                exit();
        } else {
            $_SESSION['erreur'] ="Un problème est survenu lors de l'enregistrement du lot";   
            
        }
     
        
    } else {
        if (empty($_POST['numero_lot'])) {
                $erreur_numero_lot="has-error";
                $_SESSION['erreur']= 'Veuillez corriger les érreurs .';
        }
        if (empty($_POST['date_livraison'])) {
                $erreur_date_livraison ="has-error";
                $_SESSION['erreur']= 'Veuillez corriger les érreurs .';
        }

    }
}
?>
<? require 'vue/form_lot.php'; ?>