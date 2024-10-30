<div class="custauth">
	<h3 class="custauth__title"><?php _e('REGISTER'); ?></h3>
	<div class="custauth__error">
		<?php $this->custauth_show_error_messages(); ?>
	</div>
	<form id="custauth_registration_form" class="custauth__form" action="" method="POST">
		<fieldset>
			<p>
				<label for="custauth_user_Login"><?php _e('Username'); ?></label>
				<input name="custauth_user_login" id="custauth_user_login" class="custauth__input" type="text" value="<?php if(isset($_POST['custauth_user_login'])) echo esc_attr($_POST['custauth_user_login']); ?>">
			</p>
			<p>
				<label for="custauth_user_email"><?php _e('Email'); ?></label>
				<input name="custauth_user_email" id="custauth_user_email" class="custauth__input" type="email" value="<?php if(isset($_POST['custauth_user_email'])) echo esc_attr($_POST['custauth_user_email']); ?>">
			</p>
			<?php if($this->plugin_options['custauth_require_firstlast']){ ?>
			<p>
				<label for="custauth_user_first"><?php _e('First Name'); ?></label>
				<input name="custauth_user_first" id="custauth_user_first" type="text" class="custauth__input" value="<?php if(isset($_POST['custauth_user_first'])) echo esc_attr($_POST['custauth_user_first']); ?>">
			</p>
			<p>
				<label for="custauth_user_last"><?php _e('Last Name'); ?></label>
				<input name="custauth_user_last" id="custauth_user_last" type="text" class="custauth__input" value="<?php if(isset($_POST['custauth_user_last'])) echo esc_attr($_POST['custauth_user_last']); ?>">
			</p>
			<?php } ?>
			<p>
				<label for="password"><?php _e('Password'); ?></label>
				<input name="custauth_user_pass" id="password" class="custauth__input" type="password">
			</p>
			<p>
				<label for="password_again"><?php _e('Confirm Password'); ?></label>
				<input name="custauth_user_pass_confirm" id="password_again" class="custauth__input" type="password" >
			</p>
			<?php if($this->plugin_options['custauth_recaptcha'] && !empty($this->plugin_options['custauth_secret_key']) && !empty($this->plugin_options['custauth_site_key'])){ ?>
			<p>
				<div class="g-recaptcha" data-sitekey="<?php echo esc_attr($this->plugin_options['custauth_site_key']); ?>"></div>
			</p>	
			<?php } ?>
			<p>
				<input type="hidden" name="action" value="custauth_register">
				<?php wp_nonce_field( 'custauth-register-nonce', 'custauth_register_nonce' ); ?>
				<input type="submit" class="custauth__submit" value="<?php _e('Register'); ?>">
			</p>

			<div class="row custauth__links">
				<?php $login_page = $this->custom_pages['login']; ?>
				<p class="custauth__create pull-left"><a class="custauth__link" href="<?php echo esc_url(home_url("?page_id=$login_page")); ?>"><?php _e('Log in');?></a></p>
			</div>
		</fieldset>
	</form>
</div>
