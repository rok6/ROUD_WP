<?php

class Roud_Options
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;

		if( is_admin() ) {
			add_action('admin_menu', array( $this, 'set_menu_options' ));
		}
	}

	public function register_settings()
	{
		register_setting('site_settings', 'blogname');
		register_setting('site_settings', 'blogdescription');
		register_setting('site_settings', 'twitter');
		register_setting('site_settings', 'facebook');
	}

	public function set_menu_options()
	{
		//$this->reset_wp_options_general();

		$options_group = 'site_settings';
		$role = 'administrator';

		add_menu_page(
			__( 'サイト設定', self::$domain ),
			__( 'サイト設定', self::$domain ),
			$role,
			$options_group,
			[$this, 'require_options'],
			'dashicons-admin-settings',
			80
		);
		add_submenu_page(
			$options_group,
			__( '一般設定', self::$domain ),
			__( '一般設定', self::$domain ),
			$role,
			$options_group,
			[$this, 'require_options']
		);
		add_submenu_page(
			$options_group,
			__( 'メディア', self::$domain ),
			__( 'メディア', self::$domain ),
			$role,
			$options_group . '-media',
			[$this, 'require_options']
		);

		add_action('admin_init', array( $this, 'register_settings' ));
	}

	public function require_options()
	{
		require_once(TEMPLATEPATH . '/settings/options.php');
	}

	private function reset_wp_options_general()
	{
		//options-general.php, options-writing.php, options-reading.php, options-discussion.php, options-media.php, options-permalink.php
		remove_submenu_page('options-general.php', 'options-general.php');
		remove_submenu_page('options-general.php', 'options-writing.php');
		remove_submenu_page('options-general.php', 'options-reading.php');
		remove_submenu_page('options-general.php', 'options-discussion.php');
		remove_submenu_page('options-general.php', 'options-media.php');
		remove_submenu_page('options-general.php', 'options-permalink.php');

		remove_menu_page('options-general.php');

		add_menu_page(
			__( 'その他の設定', self::$domain ),
			__( 'その他の設定', self::$domain ),
			'administrator',
			'options-general.php',
			'',
			null,
			81
		);
	}

}
