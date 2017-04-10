<?php
/**
 * 一般設定
 */
?>
<div class="wrap">
<h1><?php echo esc_html( __('General Settings') ); ?></h1>

<form method="post" action="options.php">
<?php settings_fields('site_settings'); ?>
<?php do_settings_sections('site_settings'); ?>

<table class="form-table">

	<tr>
		<th scope="row"><label for="blogname"><?php _e('サイトのタイトル') ?></label></th>
		<td>
			<input name="blogname" type="text" id="blogname" value="<?php form_option('blogname'); ?>" class="regular-text" />
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="blogdescription"><?php _e('キャッチフレーズ') ?></label></th>
		<td>
			<input name="blogdescription" type="text" id="blogdescription" aria-describedby="tagline-description" value="<?php form_option('blogdescription'); ?>" class="regular-text" />
			<p class="description" id="tagline-description"><?php _e( 'このサイトの簡単な説明' ) ?></p>
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="admin_email"><?php _e('メールアドレス') ?> </label></th>
		<td>
			<input name="admin_email" type="email" id="admin_email" aria-describedby="admin-email-description" value="<?php form_option( 'admin_email' ); ?>" class="regular-text ltr" />
			<p class="description" id="admin-email-description"><?php _e( 'このアドレスは新規ユーザーの通知などサイト管理のために使われます。' ) ?></p>
		</td>
	</tr>

	<tr>
    <th scope="row"><label for="twitter"><?php _e( 'Twitterアカウント' ); ?></label></th>
    <td>
			<input name="twitter" type="text" value="<?php form_option('twitter'); ?>" class="regular-text">
		</td>
	</tr>

  <tr>
    <th scope="row"><label for="facebook"><?php _e( 'Facebookアカウント' ); ?></label></label></th>
    <td>
			<input name="facebook" type="text" value="<?php form_option('facebook'); ?>" class="regular-text">
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="posts_per_page"><?php _e( '1ページに表示する最大投稿数' ); ?></label></th>
		<td>
				<input name="posts_per_page" type="number" step="1" min="1" id="posts_per_page" value="<?php form_option( 'posts_per_page' ); ?>" class="small-text" /> <?php _e( 'posts' ); ?>
		</td>
	</tr>

	<tr>
		<th scope="row"><label for="posts_per_rss"><?php _e( 'RSS/Atom フィードで表示する最新の投稿数' ); ?></label></th>
		<td>
			<input name="posts_per_rss" type="number" step="1" min="1" id="posts_per_rss" value="<?php form_option( 'posts_per_rss' ); ?>" class="small-text" /> <?php _e( 'items' ); ?>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php _e( 'RSS/Atom フィードでの各投稿の表示' ); ?> </th>
		<td>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e( 'RSS/Atom フィードでの各投稿の表示 ' ); ?> </span></legend>
				<p>
					<label><input name="rss_use_excerpt" type="radio" value="0" <?php checked( 0, get_option( 'rss_use_excerpt' ) ); ?>	/> <?php _e( 'Full text' ); ?></label>
					<br />
					<label><input name="rss_use_excerpt" type="radio" value="1" <?php checked( 1, get_option( 'rss_use_excerpt' ) ); ?> /> <?php _e( 'Summary' ); ?></label>
				</p>
			</fieldset>
		</td>
	</tr>

	<tr class="option-site-visibility">
		<th scope="row"><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site Visibility' ) : _e( 'Search Engine Visibility' ); ?> </th>
		<td>
			<fieldset>
				<legend class="screen-reader-text"><span><?php has_action( 'blog_privacy_selector' ) ? _e( 'Site Visibility' ) : _e( 'Search Engine Visibility' ); ?> </span></legend>
				<?php if ( has_action( 'blog_privacy_selector' ) ) : ?>

					<input id="blog-public" type="radio" name="blog_public" value="1" <?php checked('1', get_option('blog_public')); ?> />
					<label for="blog-public"><?php _e( 'Allow search engines to index this site' );?></label>
					<br/>
					<input id="blog-norobots" type="radio" name="blog_public" value="0" <?php checked('0', get_option('blog_public')); ?> />
					<label for="blog-norobots"><?php _e( 'このリクエストを尊重するかどうかは検索エンジンの設定によります。' ); ?></label>
					<p class="description">
						<?php _e( 'Note: Neither of these options blocks access to your site &mdash; it is up to search engines to honor your request.' ); ?>
					</p>
					<?php do_action( 'blog_privacy_selector' ); ?>

				<?php else : ?>

					<label for="blog_public">
						<input name="blog_public" type="checkbox" id="blog_public" value="0" <?php checked( '0', get_option( 'blog_public' ) ); ?> />
						<?php _e( '検索エンジンがサイトをインデックスしないようにする' ); ?>
					</label>
					<p class="description">
						<?php _e( 'このリクエストを尊重するかどうかは検索エンジンの設定によります。' ); ?>
					</p>

				<?php endif; ?>
			</fieldset>
		</td>
	</tr>

</table>

<?php submit_button(); ?>
</form>

</div>
