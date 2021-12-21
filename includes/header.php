<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Ektis - Gestion des réserves</title>
    
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    
    <link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

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
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>


                <? if (!is_null($_SESSION['logo_projet'])) { ?>

                    <a class="navbar-brand" href="index.php"><img  alt="" src="Fichiers/logo/<?php echo $_SESSION['logo_projet']; ?>" height="60"></a>

                <?php } else { ?>


                <?php } ?>



            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              
            <?php $logadmin=auth::isadmin($DB,$_SESSION['id_user']);
            
            if ($logadmin) { ?>
                   <ul class="nav navbar-nav">
                <li><a href="lst_projets.php">Gestion des projets</a></li>
                       <li><a href="import_tmp.php">Importation</a></li>
              </ul>
                <? } ?>    
                
              <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bienvenue <?php echo utf8_decode(stripslashes($_SESSION['prenom']))." ".utf8_decode(stripslashes($_SESSION['nom'])); ?> - Vous êtes : <?php echo($_SESSION['description_role']);?><span class="caret"></span></a>

                    <ul class="dropdown-menu">
                    <li><a href="login.php?logout">Déconnexion</a></li>
                  </ul>
                </li>
                 
                 
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="nomduprojet">Projet : <?php echo stripslashes($_SESSION['nom_projet']) ?><span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <?php 
                    foreach($lst_projet_uti as $projet) {
                    ?>  
                    <li><a href="chgt_du_projet.php?id=<?php echo $projet['pu_p_id']  ?>"><?php echo stripslashes($projet['p_description']) ?></a></li>
                    <?php 
                    }?>
                    
                  </ul>
                </li>
                
              </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    
    
    <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <ul class="nav nav-interne" id="side-menu">
                        <li><a href="index.php" class="glyphicon glyphicon-home" title="Liste des réserves et GPA"></a></li>
                        <?php foreach($lst_menu as $menu) { 


                         //var_dump($menu);

                        if (pageencours ==$menu['m_lien']) {
                            $selection="nav-interne-select";
                        } else {
                            $selection="";
                        }
                        ?>


                        <li class="<?php echo $selection ?>"><a href="<?php echo $menu['m_lien'] ?>"><i class="<?php echo $menu['m_icone'] ?>" title="<?php echo $menu['m_description']?>"></i> <?php echo $menu['m_description'] ?></a></li>
                            

                        <?php 
                        }
                        ?>                                       
                    </ul>
                </div>
            </div>
        </div>
    </div>