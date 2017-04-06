<?php
/**
 * 一般設定
 */


add_action('admin_init', function() {
	register_setting('site_settings', 'blogname');
	register_setting('site_settings', 'blogdescription');
	register_setting('site_settings', 'twitter');
	register_setting('site_settings', 'facebook');
});

?>
<div class="wrap">
<h1><?php echo esc_html( __('General Settings') ); ?></h1>

<form method="post" action="options.php">
<?php settings_fields('site_settings'); ?>
<?php do_settings_sections('site_settings'); ?>

<table class="form-table">

	<tr>
		<th scope="row"><label for="blogname"><?php _e('Site Title') ?></label></th>
		<td>
			<input name="blogname" type="text" id="blogname" value="<?php form_option('blogname'); ?>" class="regular-text" />
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="blogdescription"><?php _e('Tagline') ?></label></th>
		<td>
			<input name="blogdescription" type="text" id="blogdescription" aria-describedby="tagline-description" value="<?php form_option('blogdescription'); ?>" class="regular-text" />
			<p class="description" id="tagline-description"><?php _e( 'このサイトの簡単な説明' ) ?></p>
		</td>
	</tr>

	<tr>
    <th scope="row"><label for="twitter">Twitterアカウント</label></th>
    <td><input name="twitter" type="text" value="<?php form_option('twitter'); ?>" class="regular-text">
  </tr>

  <tr>
    <th scope="row"><label for="facebook">Facebookアカウント</label></label></th>
    <td><input name="facebook" type="text" value="<?php form_option('facebook'); ?>" class="regular-text">
  </tr>

</table>

<?php submit_button(); ?>
</form>

</div>
