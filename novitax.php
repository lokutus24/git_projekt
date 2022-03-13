<?php

if (file_exists(dirname(__FILE__)."/vendor/autoload.php")) {

    /**
    * @return PSR-4 PHP
    */
     require_once dirname(__FILE__)."/vendor/autoload.php";
}

use Inc\Api\Novitax;

/*$_SERVER['HTTP_X_AUTHORIZATION'] = 'QIBsYdAHDKPhxCpcclgwC1tvuAHYF87L2a5gSKtCtv';
$_SERVER['HTTP_X_TAXNUMBER'] = '23418398';
*/

if (isset($_SERVER['HTTP_X_AUTHORIZATION']) &&  $_SERVER['HTTP_X_AUTHORIZATION'] == 'QIBsYdAHDKPhxCpcclgwC1tvuAHYF87L2a5gSKtCtv') {

	/*
	print_r($_SERVER['HTTP_X_AUTHORIZATION']);
	print_r($_SERVER['HTTP_X_TAXNUMBER']);
	*/

	$novi = new Novitax($_SERVER['HTTP_X_AUTHORIZATION'], $_SERVER['HTTP_X_TAXNUMBER']);
	$novi->run();
}