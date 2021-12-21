<?php require 'includes/includes_back.php';?>
<?php require_once 'Classes/MessageAuto.php';
    
    $lst_histo_message_auto=MessageAuto::gethistomessage($DB);

//print_r($lst_histo_message_auto);
    
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
  <h2 class="text-center">Historiques des notifications envoyés aux acquéreurs</h2>  
  
    
    <table class="table table-condensed">
    <thead>
      <tr>
        <th>Date</th>
		 <th>Nom et prénom de l'acquéreur</th>
          <th>Email</th>
      </tr>
      
      <?php foreach($lst_histo_message_auto as $message_auto) { ?>
          <tr>
              <td><?php echo $message_auto['hma_date']?></td>
              <td><?php echo utf8_decode($message_auto['hma_infos'])?></td>
              <td><?php echo $message_auto['hma_email']?></td>
              
          </tr>
      
      
      <?php } ?>
      
      
    </thead>
    
  </table>
    
    
    
</div>

<?php require 'includes/footer_rub.php';?>

