<?php require 'includes/includes_back.php';

require_once 'Classes/Import.php';


if (isset($_GET['msg_supp_donnees'])) {
    $msg_donnnees="Erreur";
}

require 'vue/form_import.php';