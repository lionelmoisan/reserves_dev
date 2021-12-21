<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ektis - Gestion des réserves</title>
    
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <!--
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    -->
    
    <link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">
    
    
    <!--
    <link href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css" rel="stylesheet">
     -->   
    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


    <!-- Custom CSS -->
    <link href="css/main.css" rel="stylesheet">
    

    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
                <a class="navbar-brand" href="index.php"><img alt="" src="img/logo_group_nacarat_v3.png"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              
               
        
            <?php $logadmin=auth::isadmin($DB,$_SESSION['id_user']);
            
            if ($logadmin) { ?>
                   <ul class="nav navbar-nav">
                <li><a href="lst_projets.php">Gestion des projets</a></li>
              </ul>
                <? } ?>    
                
            
              <ul class="nav navbar-nav navbar-right">
                  <li><a href="#">Bienvenue <?php echo utf8_decode(stripslashes($_SESSION['prenom']))." ".utf8_decode(stripslashes($_SESSION['nom'])); ?></a></li>
                  <li><a href="login.php?logout">Se déconnecter</a></li>
              </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    
    <div id="page-wrapper">
        <div class="row">
        <div class="col-md-6" id="nomduprojet">
            <h3>Projet : <?php echo stripslashes($_SESSION['nom_projet']) ?></h3>
        </div>
        
        <div class="col-md-6 text-right">
             <form action="choix_du_projet.php" method="post" id="choixprojet" class="form-inline">             
                <label>Changer de projet :</label>
                <select name="choixprojet" class="form-control">
                <?php 
                    foreach($lst_projet_uti as $projet) {
                ?>    
                        <option value="<?php echo $projet['pu_p_id']?>" <?php if($projet['pu_p_id']==$_SESSION['id_projet']) { print 'selected'; }?>><?php echo stripslashes($projet['p_description'])?></option>
                    <?        
                    }    
                ?>    
                </select>
                <button class="btn btn-primary" type="submit">Modifier</button>
            </form>

        </div>
        
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-interne" id="side-menu">
                        <li><a href="index.php" class="glyphicon glyphicon-home"></a></li>
                        <?php foreach($lst_menu as $menu) { ?>   

                        <li><a href="<?php echo $menu['m_lien'] ?>"><i class="<?php echo $menu['m_icone'] ?>"></i> <?php echo $menu['m_description'] ?></a></li>   

                        <?php 
                        }
                        ?>                                       
                    </ul>
                </div>
            </div>
        </div>
    </div>