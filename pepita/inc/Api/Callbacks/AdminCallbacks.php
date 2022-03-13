<?php

/**
 * @package Admin menü class
 */

namespace Inc\Api\Callbacks;

use \Inc\Base\BaseController;

/**
 * 
 */
class AdminCallbacks extends BaseController
{
	public function adminDashboard(){

		return require_once ($this->pluginPath."templates/admin.php");
	}

	public function szedoOptionGroup($input){
		return $input;
	}

	
}