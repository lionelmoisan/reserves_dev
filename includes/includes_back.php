<?php 
require 'config.php'; 
require_once 'Classes/Db.php';
require_once 'Classes/auth.php';
require_once 'Classes/ProUti.php';
require_once 'Classes/Utilisateur.php';
require_once 'Classes/lot.php';
require_once 'Classes/Reserve.php';
require_once 'Classes/Menu.php';
require_once 'Classes/Date.php';
require_once 'Classes/Delais.php';
require_once 'Classes/Statistiques.php';
require_once 'Classes/Role.php';
require_once 'Classes/projet.php';
require_once 'Classes/Chaines.php';


$DB = new Db();

session_start();

// Sécurité pour accéder aux pages
if(!Auth::jesuisloge($DB)){
	header('location:login.php');
	$_SESSION['erreur'] = "Espace réservé aux administrateurs";
	exit();
}
 
/* Recherche le menu à afficher en fonction du role de l'utilisateur */
$lst_menu=Menu::GetMenu($DB,$_SESSION['role']);

/* Recherche les projets de l'utilisateur */
$lst_projet_uti=ProjUti::getProUti($DB,$_SESSION['email']);

/* Nom de la page en coours */
$url = $_SERVER['PHP_SELF']; 
      $reg = '#^(.+[\\\/])*([^\\\/]+)$#';
      define('pageencours', preg_replace($reg, '$2', $url)); 

?>