<?php require 'includes/includes.php'; ?>
<?php
    /* Recherche les projets de l'utilisateur */
    if (!empty($_SESSION["email"])) {
        $lst_projet_uti=ProjUti::getProUti($DB,$_SESSION["email"]);
    } else {
        $lst_projet_uti=ProjUti::getProUti($DB,$_SESSION["identifiant"]);
    }

    $nb_projet = count($lst_projet_uti);

    // SI N'EXISTE PAS DE PROJET 
    if (empty($lst_projet_uti)) { 
        header('location:index.php');
    }

    if ($nb_projet==1) {

        /* Recherche les projets de l'utilisateur */
        if (!empty($_SESSION["email"])) {
            $projet_uti=ProjUti::getProUtiRole($DB,$lst_projet_uti[0]['pu_p_id'],$_SESSION["email"]);
        } else {
            $projet_uti=ProjUti::getProUtiRole($DB,$lst_projet_uti[0]['pu_p_id'],$_SESSION["identifiant"]);
        }

        $_SESSION["id_user"]=$projet_uti["pu_u_id"];

        $_SESSION["role"]=$projet_uti['pu_u_role'];

        $_SESSION["id_projet"]=$lst_projet_uti[0]['pu_p_id'];
        $_SESSION["nom_projet"]= $lst_projet_uti[0]['p_description'];
        $_SESSION["logo_projet"]= $lst_projet_uti[0]['p_logo_nom'];


        header('location:index.php');
    }

if(isset($_POST) && !empty($_POST['choixprojet'])) {

    $nom_projet=Projet::getNomProjet($DB,$_POST['choixprojet']);

    $id_projet=intval($_POST['choixprojet']);
    
    /* Recherche les projets de l'utilisateur */
    if (!empty($_SESSION["email"])) {
        $projet_uti=ProjUti::getProUtiRole($DB,$id_projet,$_SESSION["email"]);
    } else {
        $projet_uti=ProjUti::getProUtiRole($DB,$id_projet,$_SESSION["identifiant"]);
    }

    $_SESSION["id_user"]=$projet_uti["pu_u_id"];
    $_SESSION["role"]=$projet_uti['pu_u_role'];
    
    $_SESSION["id_projet"]=$_POST['choixprojet'];
    $_SESSION["nom_projet"]=$nom_projet->p_description;

    $_SESSION["logo_projet"]=$nom_projet->p_logo_nom;

    header('location:index.php');
}

?>

<?php require_once 'includes/header_login.php'?>

<div class="container">
    <div class="row">
		<div class="col-md-3"></div> 
		<div class="col-md-6"> 
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
		</div>
		<div class="col-md-3"></div>
	</div> 
	
	
	<div class="row">
		<div class="col-md-3"></div> 
		<div class="col-md-6 text-center"><h2>Choix du projet</h2></div>
		<div class="col-md-3"></div> 
	</div>


    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <form action="choix_du_projet.php" method="post" id="choixprojet">
            <select name="choixprojet" class="form-control">
            <?php foreach($lst_projet_uti as $projet) { ?>    
                <option value="<?php echo $projet['pu_p_id']?>"><?php echo stripslashes($projet['p_description'])?></option>  
            <?}?>    
            </select>
                <br/>
            <button class="btn btn-primary" type="submit">Se connecter</button>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
    
<?php require 'includes/footer_login.php'?>