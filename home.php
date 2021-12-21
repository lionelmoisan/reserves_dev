<?php

$success = null;

if (isset($_POST['email']))
{
	$to = 'contact@ektis-reserves.fr';

	$subject = 'Demande de contact';

	$from = 'contact@ektis-reserves.fr';
	$msg  = "nom : {$_POST['fname']}
prenom : {$_POST['lname']}
email : {$_POST['email']}
tel : {$_POST['phone']}
message :
{$_POST['msg']}";

	$success = mail($to, $subject, $msg);

}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Ektis Reserves</title>

	<link rel="shortcut icon" type="image/x-icon" href="home/css/images/favicon.ico" />
	<link href="https://fonts.googleapis.com/css?family=Ubuntu:300i,400,400i,500,700" rel="stylesheet">

	<!-- Vendor Styles -->
	<link rel="stylesheet" href="home/vendor/bootstrap/bootstrap.min.css" />

	<!-- App Styles -->
	<link rel="stylesheet" href="home/css/style.css" />

	<!-- Vendor JS -->
	<script src="home/vendor/jquery-1.12.4.min.js"></script>
	<script src="home/vendor/jquery.noty.packaged.min.js"></script>
	<script src="home/vendor/bootstrap/bootstrap.min.js"></script>

	<!-- App JS -->
	<script src="home/js/functions.js"></script>
</head>
<body>
<?php if ( ! is_null($success) && $success) { ?>
<script type="text/javascript">
	var n = noty({ text: 'Votre message a été envoyé correctement.', timeout: 3000 });
</script>
<?php } ?>
<div class="wrapper">
	<header class="header">
		<div class="container">
			<a href="#" class="logo">Ektis Reserves</a>

			<div class="header-inner">
				<a target="_blank" href="http://ektis-reserves.fr/login.php">
					<i class="ico-user"></i>

					<span>Connexion</span>
				</a>
			</div><!-- /.header-inner -->
		</div><!-- /.container -->
	</header><!-- /.header -->
	<div class="intro">
		<div class="intro-image" style="background-image: url(home/css/images/temp/intro.jpg);">

		</div><!-- /.intro-image -->
	</div><!-- /.intro -->

	<section class="section section-default">
		<div class="section-head">
			<div class="container">
				<h1 class="section-title">Le principe</h1>

				<h3 class="section-subtitle">Partage en ligne des listes de GPA <em>( Garantie de parfait achèvement )</em> et réserves</h3>
			</div><!-- /.container -->
		</div><!-- /.section-head -->

		<div class="section-body section-body-rotate">
			<div class="section-body-inner">
				<div class="container container-reduced">
					<div class="chart-container">
						<div class="chart">
							<div class="chart-body">
								<img src="home/css/images/circle.png" alt="" width="376" height="375">
							</div><!-- /.chart-body -->
						
							<div class="chart-content">
								<div class="chart-content-inner">
									<i class="ico-list"></i>
						
									<p>Partage en ligne des liste de GPA et réserves</p>
								</div><!-- /.chart-content-inner -->
							</div><!-- /.chart-content -->
						
							<div class="chart-item chart-item-top">
								<div class="chart-item-icon">
									<i class="ico-worker"></i>
								</div><!-- /.chart-item-icon -->
						
								<span class="chart-item-caption">Entreprises</span>
							</div><!-- /.chart-item -->
							
							<div class="chart-item chart-item-left">
								<span class="chart-item-caption">Maître d'ouvrage Maître d'œuvre AMOA</span>
						
								<div class="chart-item-icon">
									<i class="ico-male"></i>
								</div><!-- /.chart-item-icon -->
							</div><!-- /.chart-item -->
							
							<div class="chart-item chart-item-right">
								<div class="chart-item-icon">
									<i class="ico-female"></i>
								</div><!-- /.chart-item-icon -->
						
								<span class="chart-item-caption">Acquéreur <br />Syndic de copropriété Bailleur</span>
							</div><!-- /.chart-item -->
						</div><!-- /.chart -->
					</div><!-- /.chart-container -->
				</div><!-- /.container -->
			</div><!-- /.section-body-inner -->
		</div><!-- /.section-body -->
	</section><!-- /.section section-default -->

	<section class="section-default">
		<div class="container">
			<div class="section-head">
				<h1 class="section-title section-title-alt">Les fonctionnalités</h1><!-- /.section-title -->
			</div><!-- /.section-head -->

			<div class="section-body section-points">
				<div class="boxes">
					<div class="row">
						<div class="col-md-4">
							<div class="box box-teal">
								<div class="box-head">
									<h3>Maître d'ouvrage Maître d'œuvre AMOA</h3>

									<div class="box-icon">
										<i class="ico-male-white"></i>
									</div><!-- /.box-icon -->

									<p>&gt; Gagner EN TEMPS et en efficacité</p>

									<div class="box-actions">
										<a href="#" class="box-trigger">En savoir +</a>
									</div><!-- /.box-actions -->
								</div><!-- /.box-head -->

								<div class="box-body">
									<div class="features">
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-icon">
													<i class="ico-chart"></i>
												</div><!-- /.feature-icon -->
											
												<p><span>Consultation des stats d'avancement</span> <br />de levée de réserve</p>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
										
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-actions">
													<span class="btn btn-default"> Relancer toutes les entreprises</span>
													<!-- <a target="_blank" href="http://ektis-reserves.fr/login.php" class="btn btn-default"> Relancer toutes les entreprises</a> -->
												</div><!-- /.feature-actions -->
											
												<p><span>Relance des entreprises avec envoi</span> des listes à jour par entreprise en 1 clic</p>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
										
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-icon">
													<i class="ico-pipe"></i>
												</div><!-- /.feature-icon -->
											
												<p><span>Enregistrement des GPA</span> <br />avec envoi automatique</p>
											
												<ul>
													<li><em>&gt; Accusé de prise en charge à l'acquéreur</em></li>
													
													<li><em>&gt; Demande d'intervention à l'entreprise concernée</em></li>
												</ul>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
									</div><!-- /.features -->
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col-sm-4 -->

						<div class="col-md-4">
							<div class="box box-blue">
								<div class="box-head">
									<h3>Acquéreur <br class="hidden-xs" />Syndic <br class="hidden-xs" />Bailleur</h3>

									<div class="box-icon">
										<i class="ico-female-white"></i>
									</div><!-- /.box-icon -->

									<p>&gt; MIEUX COMMUNIQUER</p>

									<div class="box-actions">
										<a href="#" class="box-trigger">En savoir +</a>
									</div><!-- /.box-actions -->
								</div><!-- /.box-head -->

								<div class="box-body">
									<div class="features">
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-icon">
													<i class="ico-mail"></i>
												</div><!-- /.feature-icon -->
											
												<p><span>Réception d'un mail d'info</span> <br />à chaque fois qu'une réserve ou une GPA est levée</p>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
										
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-icon">
													<i class="ico-mail-info"></i>
												</div><!-- /.feature-icon -->
											
												<p><span>Réception par mail</span> <br />d'un accusé de prise en compte d'une demande GPA</p>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
										
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-icon">
													<i class="ico-access"></i>
												</div><!-- /.feature-icon -->
											
												<p><span>Accès en ligne</span> <br />à tout moment à la liste à jour</p>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
									</div><!-- /.features -->
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col-sm-4 -->

						<div class="col-md-4">
							<div class="box box-green">
								<div class="box-head">
									<h3>Entreprises</h3>

									<div class="box-icon gutter-bottom">
										<i class="ico-worker-white"></i>
									</div><!-- /.box-icon -->

									<p>&gt; Leur donner <br class="hidden-xs" />la bonne info <br class="hidden-xs" />pour + d'efficacité</p>

									<div class="box-actions">
										<a href="#" class="box-trigger">En savoir +</a>
									</div><!-- /.box-actions -->
								</div><!-- /.box-head -->

								<div class="box-body">
									<div class="features">
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-icon">
													<i class="ico-access-green"></i>
												</div><!-- /.feature-icon -->
											
												<p><span>Accès en ligne à tout moment à sa liste à jour et aux</span> coordonnées des contacts pour prise de RDV d'intervention</p>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
										
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-icon">
													<i class="ico-pdf"></i>
												</div><!-- /.feature-icon -->
											
												<p><span>À chaque relance réception de sa liste</span> de réserves et GPA restantes en format PDF</p>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
										
										<div class="feature">
											<div class="feature-inner">
												<div class="feature-message">
													<span class="feature-message-inner">
														<span>J'ai pris RDV le 03nov à 15h</span>
													</span>
													
													<span class="feature-message-inner">
														<span>Très bien, je serai présent pour vérifier</span>
													</span>
												</div><!-- /.feature-message -->
											
												<p><span>Messagerie en ligne entre MOE /MOA et entreprises</span> permettant de faire des commentaires par réserve et GPA et d'en conserver l'historique</p>
											</div><!-- /.feature-inner -->
										</div><!-- /.feature -->
									</div><!-- /.features -->
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div><!-- /.col-sm-4 -->
					</div><!-- /.row -->
				</div><!-- /.boxes -->
			</div><!-- /.section-body -->
		</div><!-- /.container -->
	</section><!-- /.section-default -->

	<section class="section section-form">
		<div class="container">
			<div class="section-head">
				<h2 class="section-title">Plus d’informations ?</h2>
				
				<h3>Laissez nous un message</h3>
			</div><!-- /.section-head -->

			<div class="section-body">
				<div class="row">
					<div class="col-lg-10 col-lg-offset-1">
						<div class="form">
							<form action="#" method="post">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<input type="text" class="form-control" id="fname" name="fname" placeholder="Nom*" required>
										</div><!-- /.form-group -->
										
										<div class="form-group">
											<input type="text" class="form-control" id="lname" name="lname" placeholder="Prénom*" required>
										</div><!-- /.form-group -->
										
										<div class="form-group">
											<input type="email" class="form-control" id="email" name="email" placeholder="Email*" required>
										</div><!-- /.form-group -->
										
										<div class="form-group">
											<input type="tel" class="form-control" id="phone" name="phone" placeholder="Téléphone (+33)">
										</div><!-- /.form-group -->
									</div><!-- /.col-sm-6 -->
									
									<div class="col-sm-6">
										<div class="form-group">
											<textarea name="msg" cols="30" rows="10" class="textarea form-control" id="" placeholder="Message*" required></textarea>
										</div><!-- /.form-group -->

										<div class="form-actions">
											<input type="submit" value="Envoyer mon message" class="btn form-btn">
										</div><!-- /.form-actions -->
									</div><!-- /.col-sm-6 -->
								</div><!-- /.row -->

								<p class="form-note">*Champs obligatoires</p><!-- /.form-note -->
							</form>
						</div><!-- /.form -->
					</div><!-- /.col-lg-10 col-lg-offset-1 -->
				</div><!-- /.row -->
			</div><!-- /.section-body -->
		</div><!-- /.container -->
	</section><!-- /.section section-form -->
	<footer class="footer">
		<div class="container">
			<p class="copyright">Découvrez Ektis AMO / MOE / OPC sur <a class="ektis" target="_blank" href="http://www.ektis.fr">ektis.fr</a> - <a href="mentionslegales.html" target="_blank">Mentions légales</a></p><!-- /.copyright -->
		</div><!-- /.container -->
	</footer><!-- /.footer -->
</div><!-- /.wrapper -->
</body>
</html>

