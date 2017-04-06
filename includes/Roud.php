<?php
require_once(dirname(__FILE__) . '/Roud_CustomPost.php');
require_once(dirname(__FILE__) . '/Roud_Options.php');
require_once(dirname(__FILE__) . '/Roud_CMB2.php');

if( !class_exists('Roud') ) {

  class Roud
  {
    static protected $domain;

    public function __construct( $domain = 'Roud' )
    {
      if( is_string($domain) && $domain !== '' )
        self::$domain = $domain;
      else
        self::$domain = 'Roud';

      $this->custom_post = new Roud_CustomPost( self::$domain );
      $this->cmb2 = new Roud_CMB2( self::$domain );

			new Roud_Options( self::$domain );

      $this->default_init();
    }


    private function default_init()
    {
			//add_action('init', array( $this, 'unregister_tag_taxonomies' ));
			add_action('init', array( $this, 'unregister_category_taxonomies' ));

			if( is_admin() ) {
	      add_action('admin_menu', array( $this, 'reset_filter_primary_nav_menu' ));
	      add_filter('custom_menu_order', '__return_true');
	      add_filter('menu_order', array( $this, 'reset_filter_menu_order' ));
			}
			else {
				add_action('wp_enqueue_scripts', array($this, 'preload_jquery'));
			}

      //言語ファイルのフォルダ名を指定
      $this->set_load_theme_textdomain('languages');

			//wp_head での除去する項目を指定
      $this->clean_wp_head(array(
        'wp_generator',
        'wp_shortlink_wp_head',
        'feed_links',
        'feed_links_extra',
        'rsd_link', //EditURI　外部ツールから記事を投稿しないのなら不要
        'wlwmanifest_link', //wlwmanifest　Windows Live Writer　を使わないなら不要
        /*	page-links	*/
        'index_rel_link',
        'parent_post_rel_link',
        'start_post_rel_link',
        'adjacent_posts_rel_link_wp_head',
        /*	oEmbed	*/
        //'rest_output_link_wp_head',
        'wp_oembed_add_discovery_links',
        'wp_oembed_add_host_js',
        /*	emoji	*/
        'print_emoji_detection_script',
        'print_emoji_styles',
        /*	inline-style	*/
        'recent_comments_style',
      ));
    }

		//言語フォルダを指定する
    private function set_load_theme_textdomain( $folder = 'languages' )
    {
      load_theme_textdomain(get_template_directory() . '/' . $folder);
    }

		//jQuery をロード
    public function preload_jquery()
    {
      wp_deregister_script('jquery');
      wp_enqueue_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
    }

		//外観サブメニュー内のメニュー項目を外部に出す
    public function reset_filter_primary_nav_menu()
    {
			//メニューの有効化
      add_theme_support( 'menus' );
			//メニューをサブメニューから除去
      remove_submenu_page( 'themes.php','nav-menus.php' );
			//メインメニュー内に"メニュー"として追加
      add_menu_page(
				__( 'メニュー', self::$domain ),
				__( 'メニュー', self::$domain ),
        'edit_theme_options',
				'nav-menus.php',
				'',
				null,
				5
      );
    }

		//投稿関係のメニューを上部に上げる
    public function reset_filter_menu_order( $menu_order )
    {
      $menu = array();

			//最初の"投稿"までのメニューを取得
      foreach( $menu_order as $key => $val ) {
        if( 0 === strpos( $val, 'edit.php' ) )
          break;

        $menu[] = $val;
        unset( $menu_order[$key] );
      }

			//投稿に関するメニューを取得
      foreach( $menu_order as $key => $val ) {
				//投稿に関するメニューを取得
        if( 0 === strpos( $val, 'edit.php' ) ) {
          $menu[] = $val;
          unset( $menu_order[$key] );
        }
      }

      return array_merge( $menu, $menu_order );
    }

		//wp_head で出力される meta データの除去
    private function clean_wp_head( array $keys = array() )
    {
      foreach( $keys as $key ) {
        switch( $key ) {
          case 'index_rel_link' :
          case 'rsd_link' :
          case 'wlwmanifest_link' :
          case 'wp_generator' :
          case 'wp_shortlink_wp_head' :
          case 'rest_output_link_wp_head' :
          case 'wp_oembed_add_discovery_links' :
          case 'wp_oembed_add_host_js' :
            remove_action( 'wp_head', $key );
            break;
          case 'parent_post_rel_link' :
          case 'start_post_rel_link' :
          case 'adjacent_posts_rel_link_wp_head' :
            remove_action( 'wp_head', $key, 10, 0 );
            break;
          case 'feed_links' :
            remove_action( 'wp_head', $key, 2 );
            break;
          case 'feed_links_extra' :
            remove_action( 'wp_head', $key, 3 );
            break;
          case 'print_emoji_detection_script' :
            remove_action( 'wp_head', $key, 7 );
            remove_action( 'admin_print_scripts', $key );
          case 'print_emoji_styles' :
            remove_action( 'wp_print_styles', $key );
            remove_action( 'admin_print_styles', $key );
            break;
          case 'wp_staticize_emoji_for_email' :
            remove_action( 'wp_mail', $key );
            break;
          case 'wp_staticize_emoji' :
            remove_action( 'the_content_feed', $key );
            remove_action( 'comment_text_rss', $key );
            break;
          case 'recent_comments_style' :
            add_action('widgets_init', function() {
              global $wp_widget_factory;
              remove_action( 'wp_head', array(
            		$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'
            	));
            });
            break;
          default;
        }
      }
    }

		//投稿のタグを削除
    public function unregister_tag_taxonomies()
    {
      global $wp_taxonomies;

      if( !empty($wp_taxonomies['post_tag']->object_type) ) {
        foreach( $wp_taxonomies['post_tag']->object_type as $i => $object_type ) {
          if( $object_type == 'post' ) {
            unset( $wp_taxonomies['post_tag']->object_type[$i] );
          }
        }
      }
      return true;
    }

		//投稿のカテゴリーを削除
    public function unregister_category_taxonomies()
    {
      global $wp_taxonomies;

      if( !empty($wp_taxonomies['category']->object_type) ) {
        foreach( $wp_taxonomies['category']->object_type as $i => $object_type ) {
          if( $object_type === 'post' ) {
            unset( $wp_taxonomies['category']->object_type[$i] );
          }
        }
      }
      return true;
    }

  }// Roud Class

}
