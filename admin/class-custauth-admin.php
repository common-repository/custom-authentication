<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Custauth
 * @subpackage Custauth/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custauth
 * @subpackage Custauth/admin
 * @author     Lahoucine
 */
class Custauth_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/custauth-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/custauth-admin.js', array( 'jquery' ), $this->version, false );

	}

	function custauth_add_admin_menu(  ) { 

		add_menu_page( 'Custom auth', 'Custom auth', 'manage_options', 'custom_auth', array($this,'custauth_options_page'), 'dashicons-admin-tools' );

	}


	function custauth_settings_init(  ) { 
		// TODO: add sanitizing callback
		register_setting( 'custauthsettings', 'custauth_settings', array($this, 'custauthsettings_sanitize'));

		// sections
		add_settings_section(
			'custauth_general_section', 
			'', 
			array($this,'custauth_general_section_callback'), 
			'custauthsettings'
		);

		// fileds
		// general
		add_settings_field( 
			'custauth_require_firstlast', 
			__( 'Require First/Last names on Registration', 'custauth' ), 
			array($this,'custauth_require_firstlast_names'), 
			'custauthsettings', 
			'custauth_general_section' 
		);

		add_settings_field( 
			'custauth_allow_oldauth', 
			__( 'Allow old wordpress authentication', 'custauth' ), 
			array($this,'custauth_allow_old_auth'), 
			'custauthsettings', 
			'custauth_general_section' 
		);

		add_settings_field( 
			'custauth_default_css', 
			__( 'Enable plugin CSS', 'custauth' ), 
			array($this,'custauth_use_default_css'), 
			'custauthsettings', 
			'custauth_general_section' 
		);
		// recaptcha
		add_settings_field( 
			'custauth_recaptcha', 
			__( 'Enable Recaptcha', 'custauth' ), 
			array($this,'custauth_user_recaptcha'), 
			'custauthsettings', 
			'custauth_general_section' 
		);

		add_settings_field( 
			'custauth_secret_key', 
			__( 'Recaptcha Secret Key', 'custauth' ), 
			array($this,'custauth_recaptcha_secret_key'), 
			'custauthsettings', 
			'custauth_general_section' 
		);

		add_settings_field( 
			'custauth_site_key', 
			__( 'Recaptcha Site Key', 'custauth' ), 
			array($this,'custauth_recaptcha_site_key'), 
			'custauthsettings', 
			'custauth_general_section' 
		);

		// redirect
		add_settings_field( 
			'custauth_login_redirect', 
			__( 'Login redirect Url', 'custauth' ), 
			array($this,'custauth_login_redirect_url'), 
			'custauthsettings', 
			'custauth_general_section' 
		);

		add_settings_field( 
			'custauth_registration_redirect', 
			__( 'Registration Redirect Url', 'custauth' ), 
			array($this,'custauth_registration_redirect_url'), 
			'custauthsettings', 
			'custauth_general_section' 
		);

		add_settings_field( 
			'custauth_logout_redirect', 
			__( 'Logout Redirect Url', 'custauth' ), 
			array($this,'custauth_logout_redirect_url'), 
			'custauthsettings', 
			'custauth_general_section' 
		);
	

	}


	function custauth_recaptcha_secret_key(  ) { 

		$options = get_option( 'custauth_settings' );
		?>
		<input type='text' name='custauth_settings[custauth_secret_key]' value='<?php echo esc_attr($options['custauth_secret_key']); ?>'>
		<?php

	}


	function custauth_recaptcha_site_key(  ) { 

		$options = get_option( 'custauth_settings' );
		?>
		<input type='text' name='custauth_settings[custauth_site_key]' value='<?php echo esc_attr($options['custauth_site_key']); ?>'>
		<?php

	}


	function custauth_user_recaptcha(  ) { 

		$options = get_option( 'custauth_settings' );
		?>
		<label for="custauth_enable_recaptcha">
			<input type='checkbox' name='custauth_settings[custauth_recaptcha]' id="custauth_enable_recaptcha" value='1' <?php checked( $options['custauth_recaptcha'], '1' ); ?> >
			Get Keys <a href="https://www.google.com/recaptcha" target="_blank">here</a>
		</label>
		<?php

	}


	function custauth_use_default_css(  ) { 

		$options = get_option( 'custauth_settings' );
		?>
		<input type='checkbox' name='custauth_settings[custauth_default_css]' value='1' <?php checked(  $options['custauth_default_css'], '1' ); ?> >
		<?php

	}

	function custauth_allow_old_auth(){

		$options = get_option( 'custauth_settings' );
		?>
		<input type='checkbox' name='custauth_settings[custauth_allow_oldauth]' value='1' <?php checked(  $options['custauth_allow_oldauth'],'1' ); ?> >
		<?php		
	}

	function custauth_login_redirect_url(  ) { 

		$options = get_option( 'custauth_settings' );
		?>
		<input type='text' name='custauth_settings[custauth_login_redirect]' value='<?php echo esc_attr($options['custauth_login_redirect']); ?>'>
		<p id="tagline-description" class="description"><?php _e('The url users will be redirected to after login', 'custauth'); ?></p>
		<?php

	}


	function custauth_registration_redirect_url(  ) { 

		$options = get_option( 'custauth_settings' );
		?>
		<input type='text' name='custauth_settings[custauth_registration_redirect]' value='<?php  echo esc_attr($options['custauth_registration_redirect']); ?>'>
		<p id="tagline-description" class="description"><?php _e('The url users will be redirected to after registration', 'custauth'); ?></p>
		<?php

	}


	function custauth_logout_redirect_url(  ) { 

		$options = get_option( 'custauth_settings' );
		?>
		<input type='text' name='custauth_settings[custauth_logout_redirect]' value='<?php echo esc_attr($options['custauth_logout_redirect']); ?>'>
		<p id="tagline-description" class="description"><?php _e('The url users will be redirected to after logout', 'custauth'); ?></p>
		<?php

	}


	function custauth_require_firstlast_names(  ) { 

		$options = get_option( 'custauth_settings' );
		?>
		<input type='checkbox' name='custauth_settings[custauth_require_firstlast]' <?php checked( $options['custauth_require_firstlast'], '1'); ?> value='1'>
		<?php

	}


	function custauth_general_section_callback(  ) { 

		//echo __( 'Plugin general options', 'custauth' );

	}


	function custauth_options_page(  ) { 

		?>
		<div class="wrap">
			<form action='options.php' method='post'>
				<h2><?php _e('Custom Authentication Options', 'custauth'); ?></h2>
				<?php settings_errors(); ?>
				<?php
				settings_fields( 'custauthsettings' );
				do_settings_sections( 'custauthsettings' );
				submit_button();
				?>
				
			</form>
		</div>
		<?php

	}

	/**
	 * sanitizing input data
	 *
	 * @return Array
	 * @author 
	 **/
	public function custauthsettings_sanitize($input){
		$valid = array();

		$valid['custauth_require_firstlast'] = (isset($input['custauth_require_firstlast']) && !empty($input['custauth_require_firstlast'])) ? 1 : 0;
		$valid['custauth_allow_oldauth'] = (isset($input['custauth_allow_oldauth']) && !empty($input['custauth_allow_oldauth'])) ? 1 : 0;
		$valid['custauth_default_css'] = (isset($input['custauth_default_css']) && !empty($input['custauth_default_css'])) ? 1 : 0;
		$valid['custauth_recaptcha'] = (isset($input['custauth_recaptcha']) && !empty($input['custauth_recaptcha'])) ? 1 : 0;
		$valid['custauth_secret_key'] = sanitize_text_field($input['custauth_secret_key']);
		$valid['custauth_site_key'] = sanitize_text_field($input['custauth_site_key']);
		$valid['custauth_login_redirect'] = esc_url($input['custauth_login_redirect']);
		$valid['custauth_registration_redirect'] = esc_url($input['custauth_registration_redirect']);
		$valid['custauth_logout_redirect'] = esc_url($input['custauth_logout_redirect']);

		return $valid;
	}


	/**
	 * Register links widget
	 *
	 * @return void
	 * @author 
	 **/
	public function register_links_widget(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-custauth-widgets.php'; 
		register_widget('LinksWidget');
	}

}
