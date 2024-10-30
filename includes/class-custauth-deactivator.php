<?php

/**
 * Fired during plugin deactivation
 *
 * @since      1.0.0
 *
 * @package    Custauth
 * @subpackage Custauth/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Custauth
 * @subpackage Custauth/includes
 */
class Custauth_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

	}

	/**
	 * delete custom login/registration pages
	 *
	 * @return void
	 * @author 
	 **/
	public static function delete_pages(){
		$array = get_option('custauth_pages'); 
		if($array != false){
			foreach ($array as $key => $value) {
				wp_delete_post($key, true);
			}
			delete_option('custauth_pages');
		}
	}

}
