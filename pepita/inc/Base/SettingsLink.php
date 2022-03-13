<?php

/**
 * saját link beállítás a pluginhoz a plugin felületén.
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

class SettingsLink extends BaseController
{	

	public function registerFunction(){

		add_filter("plugin_action_links_$this->pluginName", array($this, 'settingsLink') );
	}

    public function settingsLink($links){
		
		/* Ez határozza meg, hogy hogy néz ki a plugin admin része. */
		$mylinks = array(
		 '<a href="' . admin_url( 'admin.php?page=szedo_plugin' ) . '">Beállítás</a>',
		 );
		return array_merge( $links, $mylinks );
	}

}