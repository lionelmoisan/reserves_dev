<?php require 'includes/includes_back.php';

/*---- Tous les champs sont renseignées ----*/
if(!empty($_POST)){

    $role=$_POST['droit'];

    $prenom=utf8_encode(addslashes($_POST['prenom']));
    $nom=utf8_encode(addslashes($_POST['nom']));

    $entreprise=utf8_encode(addslashes($_POST['entreprise']));

    $identifiant=addslashes($_POST['identifiant']);
    $email=addslashes($_POST['email']);
    $password=$_POST['password'];

    $adresse=utf8_encode(addslashes($_POST['adresse']));
    $ville=utf8_encode(addslashes($_POST['ville']));
    $code_postal=addslashes($_POST['cp']);

    $portable_1=addslashes($_POST['portable_1']);
    $telephone=addslashes($_POST['telephone']);
    $portable_2=addslashes($_POST['portable_2']);

    $id_lot=$_POST['id_lot'];

    /* ---- TEST email et identifiant unique ---- */
    if (!empty($email)) {
        $email_unique = $DB->uniqueEmail($email);
        if (!empty($identifiant)) {
            $identifiant_unique = $DB->uniqueIdentifiant($identifiant);
        } else  {
            $identifiant_unique =  0;
        }
    } else {
        $email_unique=0;
        $identifiant_unique = $DB->uniqueIdentifiant($identifiant);
        if (!empty($identifiant)) {
            $identifiant_unique = $DB->uniqueIdentifiant($identifiant);
        } else  {
            $identifiant_unique =  0;
        }
    }
/*
    var_dump($email_unique);
    var_dump($identifiant_unique);
*/

    
    if (($email_unique != 0) || ($identifiant_unique != 0)) {
        $_SESSION['erreur']='Email ou identifiant déjà utilisée par un membre';
        header("location:aj_lot.php?id=".$id_lot);
        exit();
    } else {
        /* ---Gestion token ---- */
        $token = sha1(uniqid(rand()));    
            
        /* --- Génération de mot de passe + sécurisation --- */  
        /*  EN DEV LE MOT DE PASSE ES TEST
        $mot_de_passe=Utilisateur::generer_mot_de_passe();
        */ 
        $mot_de_passe="test";
        $password=Auth::hashPassword($mot_de_passe);
        
        $data = array (
            'identifiant'=>$identifiant,
            'prenom'=>$prenom,
            'nom'=>$nom,
            'entreprise'=>$entreprise,
            'email'=>$email,
            'password'=>$password,
            'adresse'=>$adresse,
            'ville'=>$ville,
            'cp'=>$code_postal,
            'portable_1'=>$portable_1,
            'telephone'=>$telephone,
            'portable_2'=>$portable_2,
            'token'=>$token,
            'actif'=>1,
            'role'=>$role
        );

        $sql_insert_uti = 'INSERT INTO gr_utilisateurs (u_identifiant,u_prenom,u_nom,u_entreprise,u_email,u_password,
                u_adresse,u_ville,u_cp,u_portable_1,u_telephone,u_portable_2,u_token,u_actif,u_role) VALUES (:identifiant,:prenom,:nom,:entreprise,:email,:password,:adresse,:ville,:cp,:portable_1,:telephone,:portable_2,:token,:actif,:role)';
               
                $req=$DB->insert($sql_insert_uti,$data);
               
                if($req){
                    
                    $sql_utilisateur= $DB->query("SELECT LAST_INSERT_ID() as last_id_user_insert",$tableau);
                    
                    //print_r($sql_utilisateur);
    
                    /* ----  Ajout des correspondances  ---- */     
                    ProjUti::insertProjUti($DB,$sql_utilisateur[0]->last_id_user_insert,$_SESSION["id_projet"]);
                    
                    /* ---- Envoie email pour vérifier adresse email --- */
                    //Utilisateur::VerifyEmail($data);
                    
                    header("location:aj_lot.php?id=".$id_lot);
                    $_SESSION['message']="L'utilisateur a été ajouté avec succès";
                    exit();
                
                }
        }
}
?>