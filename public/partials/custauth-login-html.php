
<div class="custauth">
	<h3 class="custauth__title"><?php _e('LOGIN'); ?></h3>
	<div class="custauth__error">
		<?php $this->custauth_show_error_messages(); ?>
	</div>
	<form id="custauth_login_form"  class="custauth__form" action="" method="post">
		<fieldset>
			<p>
				<label for="custauth_user_Login"><?php _e('Username');?></label>
				<input name="custauth_user_login" id="custauth_user_login" class="custauth__input" type="text" value="<?php if(isset($_POST['custauth_user_login'])) echo esc_attr($_POST['custauth_user_login']); ?>">
			</p>
			<p>
				<label for="custauth_user_pass"><?php _e('Password');?></label>
				<input name="custauth_user_pass" id="custauth_user_pass" class="custauth__input" type="password"/>
			</p>
			<p>
				<input name="custauth_remember_me" id="custauth_remember" type="checkbox" class="remember__checkbox" value="1"/>
				<label for="custauth_remember" class="remember__label"><?php _e('Remember me');?></label>
			</p>
			<p>
				<input type="hidden" name="action" value="custauth_login">
				<?php wp_nonce_field( 'custauth-login-nonce', 'custauth_login_nonce' ); ?>
				<input id="custauth_login_submit" type="submit" class="custauth__submit" value="Login"/>
			</p>

			<div class="row custauth__links">
				<?php $registration_page = $this->custom_pages['registration']; ?>
				<p class="custauth__create pull-left"><a class="custauth__link" href="<?php echo esc_url(home_url("?page_id=$registration_page")); ?>"><?php _e('Create an Account');?></a></p>
				<p class="custauth__forget pull-right"><a class="custauth__link" href="<?php echo esc_url(home_url("wp-login.php?action=lostpassword"));?>"><?php _e('Forget Password?');?></a></p>
			</div>
		</fieldset>
	</form>
	
</div>
