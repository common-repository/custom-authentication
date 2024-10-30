<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Custauth
 * @subpackage Custauth/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custauth
 * @subpackage Custauth/public
 */
class Custauth_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * plugin options
	 *
	 * @var string
	 **/
	private $plugin_options = array();

	/**
	 * custom pages ids
	 *
	 * @var string
	 **/
	private $custom_pages = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_options = get_option( 'custauth_settings' );
		$this->custom_pages = get_option('custauth_pages');
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custauth_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custauth_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if( $this->plugin_options['custauth_default_css'] && (is_page($this->custom_pages['registration']) || is_page($this->custom_pages['login']))){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custauth-public.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script('recaptcha', "https://www.google.com/recaptcha/api.js");
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Custauth_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custauth_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custauth-public.js', array( 'jquery' ), $this->version, false );
		// enqueue only on registration page
		if(is_page($this->custom_pages['registration'])){
			wp_enqueue_script('recaptcha');	
		}
		
	}

	/**
	 * showing login form
	 *
	 * @return void
	 * @author 
	 **/
	public function login_form(){
		ob_start();
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/custauth-login-html.php';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	/**
	 * showing register form
	 *
	 * @return void
	 * @author 
	 **/
	public function register_form(){
		
		if ( !get_option('users_can_register') ) {
			return __('User registration is not enabled');
		}
		ob_start();
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/custauth-register-html.php';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;	
	}

	
	/**
	 * Display errors if exist
	 *
	 * @return void
	 * @author 
	 **/
	public function custauth_show_error_messages(){
		if($codes = $this->custauth_errors()->get_error_codes()) {
				// error loop
			   foreach($codes as $code){
			        $message = $this->custauth_errors()->get_error_message($code);
			        echo '<p class="error"><strong>' . __('Error') . '</strong>: ' . esc_html($message) . '</p>';
			    }
		}	
	}

	/**
	 * add returned errors to global errors
	 *
	 * @return void
	 * @author 
	 **/
	public function custauth_assign_errors($error_object){
		if($codes = $error_object->get_error_codes()) {
			foreach($codes as $code){
			    $message = $error_object->get_error_message($code);
			    $this->custauth_errors()->add($code, $message);
			}
		}
	}

	// logs a member in after submitting a form
	public function custauth_login_member() {
	 
		if(isset($_POST['action']) && $_POST['action'] == "custauth_login" && wp_verify_nonce($_POST['custauth_login_nonce'], 'custauth-login-nonce')) {
	 		$user_login = isset($_POST['custauth_user_login']) ? $_POST['custauth_user_login'] : '';
	 		$user_password = isset($_POST['custauth_user_pass']) ? $_POST['custauth_user_pass'] : '';
	 		$user_remember = isset($_POST['custauth_remember_me']) ? $_POST['custauth_remember_me'] : ''; 
	 		$user_remember = $user_remember == 1 ? true : false;
	 	
	 		

	 		// check if user_login is empty
	 		if (empty($user_login) or empty($user_password)) {
	 			if(empty($user_login)){
		 			$this->custauth_errors()->add('empty_username', __('Please enter a username.'));
		 		}
		 		if(empty($user_password)){
		 			$this->custauth_errors()->add('password_empty', __('Please enter a password'));
		 		}	
	 		}else{
	 			// sanitize user login
	 			$user_name = sanitize_user($user_login);
	 			// get user object by login
	 			$user = get_user_by('login', $user_name);
	 			if(!$user){
	 				// if the user name doesn't exist
					$this->custauth_errors()->add('invalid_username', __('Invalid username'));
	 			}	
	 		}
	 		 		

	 		$errors = $this->custauth_errors()->get_error_messages();
	 		if(empty($errors)) {
	 			$cred = array(
					'user_login' => $user_name,
					'user_password' => $user_password,
					'remember' => $user_remember
				);
				$res = wp_signon($cred, is_ssl());
				if(!is_wp_error($res)){
					//
					$redirect_url = apply_filters("custauth_login_redirect", admin_url());
					wp_redirect($redirect_url); exit;	
				}else{
					$this->custauth_assign_errors($res);
				}
				
			}
			
		}
	}

	public function custauth_errors(){
	    static $wp_error; // Will hold global variable safely
	    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
	}

	function custauth_add_new_member() {
	  	if (isset( $_POST["action"] ) && $_POST['action'] == "custauth_register" && wp_verify_nonce($_POST['custauth_register_nonce'], 'custauth-register-nonce')) {
	  		// check is data input is set if not make it empty
			$user_login		= isset($_POST["custauth_user_login"]) ? $_POST["custauth_user_login"] : '';	
			$user_email		= isset($_POST["custauth_user_email"]) ? $_POST["custauth_user_email"] : '';
			$user_first 	= isset($_POST["custauth_user_first"]) ? $_POST["custauth_user_first"] : '';
			$user_last	 	= isset($_POST["custauth_user_last"]) ? $_POST["custauth_user_last"] : '';
			$user_pass		= isset($_POST["custauth_user_pass"]) ? $_POST["custauth_user_pass"] : '';
			$pass_confirm 	= isset($_POST["custauth_user_pass_confirm"]) ? $_POST["custauth_user_pass_confirm"] : '';
			$recaptcha		= isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response']: ''; 
			// sanitize user data
			$sanitized_user_login		= sanitize_user($user_login);	
			$sanitized_user_first 		= sanitize_text_field($user_first);
			$sanitized_user_last	 	= sanitize_text_field($user_last);
	 		
	 		if($this->plugin_options['custauth_recaptcha'] && !empty($this->plugin_options['custauth_secret_key']) && !empty($this->plugin_options['custauth_site_key'])){
	 			
	 			$recaptcha_secret = $this->plugin_options['custauth_secret_key'];
		 		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $recaptcha_secret ."&response=". $recaptcha);
		 		$response = json_decode($response["body"], true);
		 		

		 		if(!$response['success']){
		 			$this->custauth_errors()->add('recaptcha_error', __('Please Enter Recaptcha'));
		 		}
	 		}
	 		
	 		// added recaptcha

	 		if($sanitized_user_login == '') {
				// empty username
				$this->custauth_errors()->add('empty_username', __('Please enter a username.'));
			}elseif(!validate_username($user_login)) {
				// invalid username
				$this->custauth_errors()->add('invalid_username', __('This username is invalid because it uses illegal characters. Please enter a valid username'));
			}elseif(username_exists($sanitized_user_login)) {
				// Username already registered
				$this->custauth_errors()->add('username_exists', __('This username is already registered. Please choose another one.'));
			}
			if ($user_email == '') {
				$this->custauth_errors()->add('empty_email', __('Please enter an email.'));
			}elseif(!is_email($user_email)) {
				//invalid email
				$this->custauth_errors()->add('email_invalid', __('Invalid email'));
			}elseif(email_exists($user_email)) {
				//Email address already registered
				$this->custauth_errors()->add('email_used', __('Email already registered'));
			}
			if($user_pass == '') {
				// password is empty
				$this->custauth_errors()->add('password_empty', __('Please enter a password'));
			}elseif($user_pass != $pass_confirm) {
				// passwords do not match
				$this->custauth_errors()->add('password_mismatch', __('Passwords do not match'));
			}
	 
			$errors = $this->custauth_errors()->get_error_messages();
	 
			// only create the user in if there are no errors
			if(empty($errors)) {
	 
				$new_user = wp_insert_user(array(
						'user_login'		=> $user_login,
						'user_pass'	 		=> $user_pass,
						'user_email'		=> $user_email,
						'first_name'		=> $user_first,
						'last_name'			=> $user_last,
						'user_registered'	=> date('Y-m-d H:i:s'),
						'role'				=> 'subscriber'
					)
				);
				if(!is_wp_error($new_user)) {
					
					// send an email to the admin alerting them of the registration
					wp_new_user_notification($new_user);				
	 
					// send the newly created user to the home page after logging them in
					$login_page_id = $this->custom_pages['login'];
					$redirect_url = apply_filters("custauth_registration_redirect", home_url("?page_id=$login_page_id"));
					wp_redirect($redirect_url); exit;
				}else{
					$this->custauth_assign_errors($new_user);
				}
	 
			}
	 
		}
	}

	
	/**
	 * redirect logged in user from login/registration pages
	 *
	 * @return void
	 * @author 
	 **/
	public function custauth_conditions(){
		if( (is_page( $this->custom_pages['login'] ) || is_page( $this->custom_pages['registration'] )) && is_user_logged_in() ){
	        wp_redirect(home_url());
	        exit();
	    }
	}

	/**
	 * check if the user allows old authentication (default wordpress authentication)
	 * if not redirect to custom authentication, if the user tries to access old ones
	 *
	 * @return void
	 * @author 
	 **/
	public function custauth_allow_default_auth(){
		if(!$this->plugin_options['custauth_allow_oldauth']){
			global $pagenow;
			if($pagenow == 'wp-login.php'){
				if(isset($_GET['action']) && !empty($_GET['action'])){
					$resp = in_array( $_GET['action'], array('register', 'login' ), true );
					if($resp){
						if(is_user_logged_in()){
							wp_redirect_url(home_url()); exit();
						}else{
							$login = $this->custom_pages['login'];
							wp_redirect(home_url("?page_id=$login")); exit();
						}
					}	
				}else{
					// redirect to custom login
					$login  = $this->custom_pages['login'];
					wp_redirect(home_url("?page_id=$login")); exit();
				}
			}
		}
	}


	/**
	 * Logout redirect
	 *
	 * @return String: Url
	 * @author 
	 **/
	public function custauth_logout_redirect($redirect_to, $requested_redirect_to, $user ){
		if(!empty($this->plugin_options['custauth_logout_redirect'])){
			$requested_redirect_to = $this->plugin_options['custauth_logout_redirect'];
		}
		return $requested_redirect_to;
	}

	/**
	 * registration redirect
	 *
	 * @return String: Url
	 * @author 
	 **/
	public function custauth_register_redirect($default_url){
		if(!empty($this->plugin_options['custauth_registration_redirect'])){
			$default_url = $this->plugin_options['custauth_registration_redirect'];
		}
		return $default_url;
	}

	/**
	 * Login redirect
	 *
	 * @return String: Url
	 * @author 
	 **/
	public function custauth_loginf_redirect($default_url){
		if(!empty($this->plugin_options['custauth_login_redirect'])){
			$default_url = $this->plugin_options['custauth_login_redirect'];
		}
		return $default_url;
	}


}
