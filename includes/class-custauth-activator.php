<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Custauth
 * @subpackage Custauth/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Custauth
 * @subpackage Custauth/includes
 */
class Custauth_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// register pages
		Custauth_Activator::add_pages();
	}


	/**
	 * Add login / registration pages
	 *
	 * @return void
	 * @author 
	 **/
	public static function add_pages(){
		$pages = array(
			'login' => array(
				'post_content' => '[custauth_login]',
				'post_title' => 'Login',
				'post_status' => 'publish',
				'post_type' => 'page'	
			),
			'registration' => array(
				'post_content' => '[custauth_register]',
				'post_title' => 'Registration',
				'post_status' => 'publish',
				'post_type' => 'page'		
			)
		);
		$pages_id = array();
		foreach ($pages as $key => $value) {
			$res = wp_insert_post($value);
			if($res){
				$pages_id[$key] = $res;
			}	
		}
		update_option("custauth_pages", $pages_id);
	}

}
