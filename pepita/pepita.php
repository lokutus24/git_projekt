<?php
/**
* Plugin Name: Pepita Feed creator
* Plugin URI: https://fonaloazis.hu
* Description: Pepita.hu - hoz generál termék listát
* Version: 1.0 teszt
* Author: Raska Gábor
* Author URI: https://fonaloazis.hu
**/

defined('ABSPATH') or die('nem kéne..');


if (file_exists(dirname(__FILE__)."/vendor/autoload.php")) {
  require_once dirname(__FILE__)."/vendor/autoload.php";
}


function activate(){
  Inc\Base\Activate::activate();
}

function deactivate(){
  Inc\Base\Deactivate::deactivate();
}

register_activation_hook(__FILE__, 'activate');

register_deactivation_hook(__FILE__, 'deactivate');

if (class_exists('Inc\\Init')) {
  
  Inc\Init::serviceRegister();

}