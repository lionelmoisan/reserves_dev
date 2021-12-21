<?php 
/**
* Projet Utilisateur
*/
class Pdf{
          
          
    public static function Createpdfrelance($email,$content) {
          
        require_once('html2pdf/html2pdf.class.php');
        try{
            $pdf = new HTML2PDF('P','A4','fr');
            $pdf->pdf->SetDisplayMode('fullpage');
            
            $pdf->writeHTML($content);
            $pdf->pdf->Output('./Fichiers/'.$email.'-liste-des-reserves.pdf','F');
        }catch(HTML2PDF_exception $e){
            echo $e->getMessage();
            exit;
        }
        
    }
}