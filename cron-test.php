#!/usr/local/bin/php

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Test de Cron</title>
	</head>
	<body>
		<h2> Le test commence </h2>
		<?php
		/**
         * test script
         **/

		$path = dirname( __FILE__ );

		// https://forum.ovh.com/showthread.php/101095-taches-planifi%C3%A9s-ne-s-executent-plus/page2

		define('ROOT',dirname( __FILE__ ));

        var_dump(ROOT);


        include_once ROOT. '/Classes/Email.php';
        include_once ROOT. '/Classes/class.phpmailer.php';


		$line1 = $path ;
		$line2 = "ligne 2" ;
		$line3 = "ligne 3" ;

		date_default_timezone_set('CEST');
		$today = date("F j, Y, g:i a");


        $data = array (
            'email'=>'lmoisan@gmail.com',
            'sujet'=>'Intervention sur vos reserves',
            'txt-relance'=>'texte de relance'
        );

        $etat_email=Email::SendEmailautomatique($DB,$data);



		$fp = fopen( ROOT . '/cron-test.log', 'a' );
		fwrite( $fp, "\r\n -----------------TEST----------------------------------- \r\n" );
		fwrite( $fp, $today . "\r\n" );
		fwrite( $fp, $line1 . "\r\n" );
		fwrite( $fp, $line2 . "\r\n" );
		fwrite( $fp, $line3 . "\r\n" );
		fwrite( $fp, "Résultat envoi Mail : " . $etat_email . "\r\n" );
		fwrite( $fp, "\r\n -------------------------------------------------------- \r\n" );
		fclose($fp);
		?>

<h2> Le test est terminé </h2>


</body>
</html>