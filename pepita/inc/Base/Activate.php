<?php

/**
 * @package Active
 */

namespace Inc\Base;

class Activate
{
	public static function activate(){

		flush_rewrite_rules();

		$default = array();

		if ( ! get_option( 'szedo_plugin' ) ) {
			update_option( 'szedo_plugin', $default );
		}
	}
}