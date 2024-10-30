<?php
/**
 * Register all widgets for the plugin
 *
 * @since      1.0.0
 *
 * @package    Custauth
 * @subpackage Custauth/includes
 */

/**
* Authentication links wiget
*/
class LinksWidget extends WP_Widget{

	/**
	 * custom authentication pages' ids
	 *
	 * @var array
	 **/
	private $custom_pages = array();
	
	function __construct(){
		parent::__construct(
			'custauth_links',
			__('Custom Authentication Widget', 'custauth'),
			array('description' => __('Display (login, registration or logout) links', 'custauth'))
		);
		$this->custom_pages = get_option('custauth_pages');
	}


	/**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
	public function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;
		if(!empty($title)){
			echo $before_title . $title . $after_title;
		}
		// show login/regisration links if not logged in logout else
		if(is_user_logged_in()){

			echo "<ul>";
			echo '<li><a href="'.esc_url(wp_logout_url(home_url())). '">'.__('Logout','custauth') . '</a></li>';
			echo "</ul>";
		}else{
			echo "<ul>";
			$login = $this->custom_pages['login'];
			echo '<li><a href="'.esc_url(home_url("?page_id=$login")) .'">';
			echo __('Login', 'custauth');
			echo '</a></li>';
			
			$registeration = $this->custom_pages['registration'];
			echo '<li><a href="'.esc_url(home_url("?page_id=$registeration ")) .'">';
			echo __('Register', 'custauth');
			echo '</a></li>';

			echo "</ul>";
		}

		echo $after_widget;
	}

	/**
    * Back-end widget form.
    *
    * @see WP_Widget::form()
    *
    * @param array $instance Previously saved values from database.
    */
	public function form($instance){
		if(isset($instance['title'])){
			$title = $instance['title'];
		}else{
			$title = __('New Title', 'custauth');
		}
		?>	
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'custauth'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<?php
	}

	/**
    * Sanitize widget form values as they are saved.
    *
    * @see WP_Widget::update()
    *
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */
	public function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

		return $instance;
	}


}
