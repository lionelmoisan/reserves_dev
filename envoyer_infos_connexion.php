<?php require 'includes/includes_back.php';

if(!empty($_GET['id'])){

    $id=intval($_GET['id']);
    $utilisateur=Utilisateur::getUtilisateurById($DB,$id);
    
    $email=$utilisateur[0]->u_email;
    $identifiant=$utilisateur[0]->u_identifiant;
    
    /* --- Génération de mot de passe + sécurisation --- */  
    /*  EN DEV LE MOT DE PASSE ES TEST
    $mot_de_passe="DC84sc57";
    */
    $mot_de_passe=Utilisateur::generer_mot_de_passe();
    $password=Auth::hashPassword($mot_de_passe);
    
    $data = array(
                'id'=>$id,  
                'password'=>$password
                );
    
     /*---- Mise à jour du mot de passe utilisateurs----*/
    $rep_update_password = $DB->insert("UPDATE gr_utilisateurs SET u_password=:password WHERE u_id=:id",$data);

    $sujet = $_SESSION['nom_projet']." - Suivi de vos réserves";

    $data_user= array(
        'sujet'=>$sujet,
        'u_email'=>$email,
        'u_identifiant'=>$identifiant,
        'u_token'=>$utilisateur[0]->u_token,
        'u_password'=>$mot_de_passe,
        'nom_projet'=>$_SESSION['nom_projet'],
        'URL'=>'http://ektis-reserves.fr/login.php'
    );


    /* Envoie d'un email */
    if (!empty($data_user['u_email'])) {
        $sendemail = Utilisateur::sendInfoConnexion($DB,$data_user);

        if ($sendemail) {
            $_SESSION['message'] = " Un Email a été envoyé à l'utilisateur avec les informations de connexion";
        } else {
            $_SESSION['erreur'] = "Un problème est survenu lors de l'envoi d'email !.";
        }


    } else {
        $_SESSION['erreur'] = "L'intervenant ne possède pas d'email !.";
    }

    header('location:lst_utilisateurs.php');

}
?>