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
		//管理画面用CSSの追加
		$this->add_admin_style();
		//サポートの有効化
		$this->add_supports();
		//Editor CSS へのパス
		add_editor_style( '/assets/css/editor-style.css' );

		// ローカルでのメール送信アクション用
		add_filter('wp_mail_from', function() {
			return 'wordpress@example.com';
		});

		new WPRD_Organize( self::$domain );
		new WPRD_Navigation( self::$domain );
		new WPRD_CMB2( self::$domain );
		new WPRD_Editor( self::$domain );
		new WPRD_Options( self::$domain );

		$this->custom_post = new WPRD_CustomPost( self::$domain );
		$this->custom_post->add(['news',]);
	}

	/**
	 * Methods
	 *=====================================================*/

	private function add_supports()
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


	private function set_load_theme_textdomain( $folder = 'languages' )
	{
		load_theme_textdomain(get_template_directory() . '/' . $folder);
	}


	private function add_admin_style(){
		add_action('admin_enqueue_scripts', function() {
			wp_enqueue_style( 'my_admin_style', get_template_directory_uri().'/assets/css/admin-style.css' );
		});
	}

}