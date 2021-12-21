<?php require 'includes/includes_back.php';
require_once 'Classes/Pdf.php';
require_once 'Classes/Email.php';

require_once 'Classes/class.phpmailer.php';
require_once 'Classes/Notifications.php';

?>
<?php

$text_footer="<br><br>Si vous avez des commentaires à transmettre au Maître d’œuvre ou au Maître d'Ouvrage concernant certaines réserves, nous vous invitons à les formuler 
            par l'intermédiaire de la messagerie disponible depuis le lien ci dessous.<br>";
$text_footer.="<br>Vous pouvez accédez à la liste complète à jour, à l'historique complet de vos réserves et GPA et à la messagerie par le lien : <a href='http://ektis-reserves.fr/login.php' target='_blank'>http://ektis-reserves.fr/login.php</a><br><br>";
$text_footer.="Bien cordialement<br>";


/*---- RECHERCHE LES ENTREPRISES AVEC DES RESERVES POUR LE PROJET EN COURS ----*/
$lst_entreprise_projet = $DB->tquery("SELECT  distinct r_id_entreprise,u_nom,u_entreprise,u_email FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot RIGHT JOIN gr_utilisateurs ON r_id_entreprise=u_id WHERE l_id_projet=".$_SESSION['id_projet']." AND r_ls_id=1 AND u_email<>'' ");

if(!empty($_POST)){  
		
	if ((!empty($_POST['lst_entre']) && (!empty($_POST['relance'])))) {

    $nbr_entreprise = count($_POST['lst_entre']);

        for ($i = 0; $i < $nbr_entreprise; $i++) {

            $Uti=Utilisateur::getUtilisateurById($DB,$_POST['lst_entre'][$i]);

            $lst_reserve_ent = $DB->tquery('SELECT r_id,r_description,r_piece,r_type,l_numero_lot,u_nom,u_email,u_portable_1,u_portable_2,u_telephone,ls_description,l_numero_lot
          FROM gr_reserves INNER JOIN gr_lots ON l_id = r_id_lot LEFT JOIN gr_utilisateurs ON l_id_contact=u_id
          LEFT JOIN gr_lst_statut ON ls_id = r_ls_id 
          WHERE l_id_projet='.$_SESSION['id_projet'].' AND r_id_entreprise='.$_POST['lst_entre'][$i].' AND r_ls_id=1 
          ORDER BY l_numero_lot ASC, r_type DESC, r_piece ASC , r_description ASC  ');

            foreach ($lst_reserve_ent as $reserve) {
                $lst_reserve .="<tr class='lignecolor'><td>".$reserve['l_numero_lot']."</td><td class='lignereserve'>".stripslashes($reserve['r_description'])."</td><td class='lignereserve'>".stripslashes($reserve['r_piece'])."</td><td class='lignereserve'>".$reserve['r_type']."</td><td class='lignereserve'>".utf8_decode(stripslashes($reserve['u_nom']))."<br>".$reserve['u_portable_1']."</td></tr>";
            }

            $content ="<style type=\"text/css\"><!--table{
    width:  100%;
    margin: 0;
    padding: 0;
}

th {
    text-align: center;
    background-color: #638DBB;
    color: #FFFFFF;
}

td {
    text-align: left;
}

td.col1 {
    text-align: center;
}

.lignecolor {
    background-color: #e3e3e3;
}

.lignereserve {
    padding-left: 10px;

}

-->
</style>
<page orientation=\"paysage\" style=\"font-size: 14px\">


 <table cellspacing=\"0\" style=\"width: 100%; text-align: left; font-size: 11pt;\">
        <tr>
            <td style=\"width:1%;\"></td>
            <td style=\"width:14%; \">Entreprise :</td>
            <td style=\"width:36%\">".utf8_decode($Uti[0]->u_entreprise)." - ".utf8_decode($Uti[0]->u_prenom)." ".utf8_decode($Uti[0]->u_nom)."</td>
        </tr>
       
        <tr>
            <td style=\"width:1%;\"></td>
            <td style=\"width:14%; \">Email :</td>
            <td style=\"width:36%\">".$Uti[0]->u_email."</td>
        </tr>
        <tr>
            <td style=\"width:1%;\"></td>
            <td style=\"width:14%; \">Tel principal :</td>
            <td style=\"width:36%\">".$Uti[0]->u_portable_1."</td>
        </tr>
    </table>
<br>
<br>
<span style=\"font-size: 16px; font-weight: bold\">Liste des réserves à lever au ".date('d/m/Y')." pour le projet - ".$_SESSION["nom_projet"]."</span><br>
<br>
<br>
 <table style=\"width: 100%; border: solid 1px #FFFFFF;\">
<col style=\"width: 12%\" class=\"col1\">
    <col style=\"width: 22%\">
    <col style=\"width: 22%\">
    <col style=\"width: 22%\">
    <col style=\"width: 22%\">

            <thead>
            <tr>
                <th>Ref lot</th>    
                <th>Description</th>
                <th>Piece ou Local</th>
                <th>Type</th>
                <th>Contact pour RDV</th>
                
            </tr>
            </thead>".utf8_decode($lst_reserve)."</table></page>";
            
            //$content_encode=utf8_decode($content);

            $createpdf=Pdf::Createpdfrelance($Uti[0]->u_email,$content);

            $email = $Uti[0]->u_email;

            $data = array (
            'email'=>$email,
            'email_expediteur'=>$_SESSION['email'],
            'sujet'=>utf8_decode($_SESSION["nom_projet"]." - Il vous reste des réserves à lever !!"),
            'txt-relance'=>nl2br($_POST['relance']).$text_footer,
            );
            
            $etat_email=Email::SendEmailrelance($DB,$data);

            $lst_reserve="";


            /*
             * Ajout d'une ligne dans la table de notification
             */

            $today = date("Y-m-d");

            $data_notification= array(
                'projet_id'=>$_SESSION['id_projet'],
                'date_notification'=>$today,
                'sujet'=>'Relance Entreprise',
                'id_acquereur'=>$_POST['lst_entre'][$i]

            );

            $result=Notifications::setNotifications($DB,$data_notification);


            
        }
         $_SESSION['message']="Les entreprises ont été relancées par email";   
        
    } else {
     $_SESSION['erreur']="Tous les champs n'ont pas été renseignés";   
    }
    
	
} else {
	
}

?>

<?php require 'includes/header.php';?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info" role="alert"><?php echo $_SESSION['message'];?></div>
    <?php unset($_SESSION['message']) ?>
<?php endif ?>   
    
<?php if (isset($_SESSION['erreur'])): ?>
    <span class="label label-danger"></span>
        <div class="alert alert-danger" role="alert">
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <span class="sr-only">Error:</span><?php echo $_SESSION['erreur'];?>
        </div>
    <?php unset($_SESSION['erreur']) ?>
<?php endif ?> 



<div class="container">
  <h2 class="text-center">Relance des entreprises</h2> 
	<form id="AddUtilisateur" action="relance_entreprise.php" class="form-horizontal" method="post" id="signup">
       <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
            <label for="message"><h4>Relancer les entreprises : </h4></label><br>
            
            <div id="checkboxes" class="list scroll-lst-entreprise">
		      <ul>
			  <?php foreach($lst_entreprise_projet as $entreprise) { ?>
			  	<li><input id="lst_entre" type="checkbox" name="lst_entre[]" value="<?php echo $entreprise['r_id_entreprise'] ?>"/>&nbsp;<?php echo utf8_decode(stripslashes($entreprise['u_entreprise']))?></li>
			  
			<?php } ?>
			  
			</ul>
		  	</div>
       <em>Liste des entreprises dont l'email a été renseigné</em>
        
       </div>
       <div class="col-xs-12 col-sm-8 col-md-8 col-lg-7">
			<label for="message"><h4>Texte de la relance :</h4></label><br>
           <textarea name="relance" id="relance" rows="10" cols="70" class="form-control">Madame, Monsieur

Dans le cadre du projet - <?php echo $_SESSION["nom_projet"]?>


Nous vous rappelons qu'il vous reste des réserves à lever.
Vous trouverez ci jointe une liste à jour au format PDF.

Nous vous remercions par avance de bien vouloir prendre rdv avec les personnes concernées pour intervention au plus vite.</textarea>
           <p><?php echo $text_footer?></php></p>
       </div>
       <div class="col-lg-1"></div>
    </div>
    
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="form-group col-lg-10 text-right">        
			  <div class="col-sm-offset-6 col-sm-6 col-lg-6 text-right">
				<a class="btn btn-default" href="index.php" role="button">Annuler</a>
				  <button type="submit" class="btn btn-primary">Envoyer</button>
			  </div>
        </div>
        <div class="col-lg-1"></div>
    </div>
       </form>
    
</div>

<?php require 'includes/footer_rub.php';?>
