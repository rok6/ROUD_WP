<?php
require_once(dirname(__FILE__) . '/wprd_Organize.php');
require_once(dirname(__FILE__) . '/wprd_Navigation.php');
require_once(dirname(__FILE__) . '/wprd_CustomPost.php');
require_once(dirname(__FILE__) . '/wprd_CMB2.php');
require_once(dirname(__FILE__) . '/wprd_Editor.php');
require_once(dirname(__FILE__) . '/wprd_Options.php');

class WPRD
{
	static protected $domain;

	public function __construct( $domain = 'wprd' )
	{

		//言語ファイルのフォルダ名を指定
		$this->set_load_theme_textdomain('languages');
		//サポートの有効化
		$this->add_supports();
		//リダイレクト時の推測候補先への遷移を禁止
		$this->remove_auto_redirect();

		// ローカルでのメール送信アクション用
		add_filter('wp_mail_from', function() {
			return 'wordpress@example.com';
		});

		new WPRD_Organize( self::$domain );
		new WPRD_CMB2( self::$domain );
		new WPRD_Editor( self::$domain );
		new WPRD_Options( self::$domain );
		
		new WPRD_Navigation( self::$domain, [
			'primary'		=> __('メインメニュー', self::$domain),
			'social'		=> __('ソーシャル', self::$domain),
		]);

		$this->custom_post = new WPRD_CustomPost( self::$domain );
		$this->custom_post->add(['news', 'wordpress']);

	}

	/**
	 * Methods
	 *=====================================================*/

	public function add_supports()
	{
		//編集ショートカットの有効化
		add_theme_support('customize-selective-refresh-widgets');

		//投稿・コメントのRSSフィードリンクの有効化
		add_theme_support('automatic-feed-links');

		//HTML5でのマークアップの許可
		add_theme_support('html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption'
		));

		//wp_head() でのタイトルタグ出力の有効化
		add_theme_support('title-tag');

		//タイトルからのキャッチフレーズの除去
		add_filter( 'document_title_parts', function( $title ) {
			if( isset($title['tagline']) ) {
				unset( $title['tagline'] );
			}
			return $title;
		});
		//タイトルのセパレータ
		add_filter( 'document_title_separator', function( $separator ) {
			return $separator = '|';
		});

		//パスワード保護ページのタイトル接頭語
		add_filter('protected_title_format', function($title) {
			return '閲覧制限:%s';
		});
	}


	public function set_load_theme_textdomain( $folder = 'languages' )
	{
		load_theme_textdomain(get_template_directory() . '/' . $folder);
	}


	public function add_admin_style( $css_path = 'admin-style.css' ){
		add_action('admin_enqueue_scripts', function() use($css_path) {
			wp_enqueue_style( 'my_admin_style', get_template_directory_uri().$css_path );
		});
	}

	public function remove_auto_redirect() {
		add_filter( 'redirect_canonical', function($redirect_url) {
			if( is_404() ) {
				return false;
			}
			return $redirect_url;
		});
	}

}
