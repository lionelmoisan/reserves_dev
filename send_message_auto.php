<?php require 'includes/includes_back.php';
require_once 'Classes/Email.php';?>

<?php

$jourmoinsun = strtotime("-1 day");
$jourmoinsun_format=date('Y/m/d H:i', $jourmoinsun);

$rch_acque_res_update = $DB->tquery("SELECT DISTINCT l_id_acquereur FROM gr_reserves,gr_lots 
LEFT JOIN gr_utilisateurs ON l_id_acquereur=u_id
WHERE r_id_lot=l_id 
AND r_date_modifier between '".$jourmoinsun_format."' and NOW()
AND l_id_acquereur <>0");

foreach ($rch_acque_res_update as $lst_res_acque_update){
    
    //print_r($lst_res_acque_update);
    
$rch_res_acque_update = $DB->tquery("SELECT r_id,r_description,l_numero_lot,l_id_acquereur,r_date_modifier,u_email,u_prenom,u_nom FROM gr_reserves,gr_lots 
LEFT JOIN gr_utilisateurs ON l_id_acquereur=u_id
WHERE r_id_lot=l_id 
AND r_date_modifier between '".$jourmoinsun_format."' and NOW()
AND l_id_acquereur=".$lst_res_acque_update['l_id_acquereur']);
    
    $html_lst_reserve="";

    foreach ($rch_res_acque_update as $lst_res){
    
        //print_r($lst_res);
        
        $html_lst_reserve.=$lst_res['l_numero_lot']."-".$lst_res['r_description']."<br>";
        $email=$lst_res['u_email'];
    }
    
    $le_sujet='Gestion de réserve - MAJ de vos réserves';
        
    $data=array(
        'email'=>$email,
        'html_lst_reserve'=>utf8_decode($html_lst_reserve),
        'sujet'=>$le_sujet,
        'URL'=>'http://localhost:8888/ektis/lot1-1/login.php'
    );
    
    /*---- Envoyer email ---*/
    //$email=Email::SendEmailResUpdate($DB,$data);
    
    if (email){
        echo "Les emails ont été envoyés<br>";    
        /* ----Enregister en base l'historique des email envoyés ----*/
        $datedujour=date("Y-m-d H:i:s");
        
        $data_histo = array (
            'date'=>$datedujour,
            'infos'=>$lst_res['u_prenom'].$lst_res['u_nom'],
            'email'=>$lst_res['u_email'],
        );    
        $sql_insert_histo_mes_auto = 'INSERT INTO gr_histo_mes_auto (hma_date,hma_infos,hma_email) VALUES (:date,:infos,:email)';        
    
        $req= $DB->insert($sql_insert_histo_mes_auto,$data_histo);
        
    } else {
        echo "Problème lors de l'envoi des emails<br>";    
    }
} 

?>