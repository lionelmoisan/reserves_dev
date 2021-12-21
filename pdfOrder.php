<?php
echo "dsds";
exit();
ob_start();

?>
<style type="text/css">
.header{
    display: block;
    height:70px;
    background: #ddd url(img/logo_group_nacarat_V3.png) 5px 5px no-repeat;
    padding:10px 10px 10px 200px;
}
.header p{
    margin:0;
    color:#000;
}
.footer p{
    margin:0;
    font-size:10px;
    color:#999;
}
.footer hr{
    color:#999;
}
h4{
    text-align: right;
}
h1{
    text-transform: uppercase;
    font-size:18px;
    text-align: center;
    color:#444;
    margin:40px;
}
.client{
    margin-left: 400px;
    padding:10px;
    border:1px dotted #999;
}
.client p{
    margin:0;
}

table{
    width:100%;
}
table thead th{
    width:11%;
    background: #000;
    color:#FFF;
    padding:5px;
    text-align: center;
}
table thead th.large{
    width:8%;
    text-align: left;
}

table tbody tr td{
    padding:8px 5px;
    border:1px solid #999;
    text-align: center;
}
table tbody tr td.large{
    text-align: left;
}
table tr.total{
       background: #000;
    color:#FFF;
}

</style>

<page footer="date;pagination" backtop="120px" backbottom="100px">
    <page_header>
       <div class="header">
             <p>Le CLOS de la Mouette - Liste des réserves au 01/05/2016</p> 
       </div>
    </page_header>
    <page_footer>
        <div class="footer">
        </div>
    </page_footer>
    <h4>Paris le , 1er mai 2016</h4>

        <table>
            <thead>
                <tr>                    
                    <th class="large">Réf du lot</th>
                    <th class="large">Description</th>
                    <th class="large">Pièce ou local</th>
                    <th class="large">Entreprise</th>
                    <th class="large">Type</th>
                    <th class="large">Date délai</th>
                    <th class="large">Contact</th>
                    <th class="large">Status</th>
                    <th class="large">Nom du signataire</th>
                    <th class="large">Dater et signer</th>
                </tr>
            </thead>
            <tbody>
        
            <tr>
              <td>A11-01</td>
              <td>Pare douche non pose</td>
              <td>Salle de bain</td>
              <td>Peinture du nord</td>
              <td>Livraison</td>
               <td>15/04/2016</td>
               <td>
                    Durand<br/>
                    01 02 03 04 05<br/>
                    durand@gmail.com
               </td>
               <td>Quitus acquéreur</td>
               <td></td>
               <td></td>
            </tr>

                        <tr>
              <td>A11-01</td>
              <td>Pare douche non pose</td>
              <td>Salle de bain</td>
              <td>Peinture du nord</td>
              <td>Livraison</td>
               <td>15/04/2016</td>
               <td>
                    Durand<br/>
                    01 02 03 04 05<br/>
                    durand@gmail.com
               </td>
               <td>Quitus acquéreur</td>
               <td></td>
               <td></td>
            </tr>


            <tr>
              <td>A11-01</td>
              <td>Pare douche non pose</td>
              <td>Salle de bain</td>
              <td>Peinture du nord</td>
              <td>Livraison</td>
               <td>15/04/2016</td>
               <td>
                    Durand<br/>
                    01 02 03 04 05<br/>
                    durand@gmail.com
               </td>
               <td>Quitus acquéreur</td>
               <td></td>
               <td></td>
            </tr>


            <tr>
              <td>A11-02</td>
              <td>Pare douche non pose</td>
              <td>Salle de bain</td>
              <td>Peinture du nord</td>
              <td>Livraison</td>
               <td>15/04/2016</td>
               <td>
                    Durand<br/>
                    01 02 03 04 05<br/>
                    durand@gmail.com
               </td>
               <td>Quitus acquéreur</td>
               <td></td>
               <td></td>
            </tr>


            <tr>
              <td>A11-02</td>
              <td>Pare douche non pose</td>
              <td>Salle de bain</td>
              <td>Peinture du nord</td>
              <td>GPA</td>
               <td>15/04/2016</td>
               <td>
                    Durand<br/>
                    01 02 03 04 05<br/>
                    durand@gmail.com
               </td>
               <td>Non levé</td>
               <td></td>
               <td></td>
            </tr>


            <tr>
              <td>A11-02</td>
              <td>Retouche sur porte</td>
              <td>Chambre</td>
              <td>Menuisier du Ternois</td>
              <td>Livraison</td>
               <td>15/04/2016</td>
               <td>
                    Durand<br/>
                    01 02 03 04 05<br/>
                    durand@gmail.com
               </td>
               <td>Quitus acquéreur</td>
               <td></td>
               <td></td>
            </tr>


            <tr>
              <td>A11-01</td>
              <td>Pare douche non pose</td>
              <td>Salle de bain</td>
              <td>Peinture du nord</td>
              <td>Livraison</td>
               <td>15/04/2016</td>
               <td>
                    Durand<br/>
                    01 02 03 04 05<br/>
                    durand@gmail.com
               </td>
               <td>Quitus acquéreur</td>
               <td></td>
               <td></td>
            </tr>


            <tr>
              <td>A11-01</td>
              <td>Pare douche non pose</td>
              <td>Salle de bain</td>
              <td>Peinture du nord</td>
              <td>Livraison</td>
               <td>15/04/2016</td>
               <td>
                    Durand<br/>
                    01 02 03 04 05<br/>
                    durand@gmail.com
               </td>
               <td>Quitus acquéreur</td>
               <td></td>
               <td></td>
            </tr>

        
            </tbody>
        </table>

</page>
<?php
$content= ob_get_clean();
require_once('html2pdf/html2pdf.class.php');
try{
    $pdf = new HTML2PDF('L','A4','fr');
    $pdf->pdf->SetDisplayMode('fullpage');

    $pdf->pdf->SetTitle('Mes réserves...');
    $pdf->pdf->SetAuthor('etkis');

    $pdf->pdf->SetProtection(array('print'));

    $pdf->writeHTML($content);
    $pdf->Output('liste des reserves.pdf','D');
}catch(HTML2PDF_exception $e){
    echo $e->getMessage();
    exit;
}