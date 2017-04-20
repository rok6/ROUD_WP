<?php
require_once(dirname(__FILE__) . '/Roud_Navigation.php');
require_once(dirname(__FILE__) . '/Roud_CustomPost.php');
require_once(dirname(__FILE__) . '/Roud_Options.php');
require_once(dirname(__FILE__) . '/Roud_CMB2.php');

if( !class_exists('Roud') ) {

	class Roud
	{
		static protected $domain;

		public function __construct( $domain = 'Roud' )
		{
			if( is_string($domain) && $domain !== '' ) {
				self::$domain = $domain;
			}
			else {
				self::$domain = 'Roud';
			}

			$this->navigation		= new Roud_Navigation( self::$domain );
			$this->custom_post	= new Roud_CustomPost( self::$domain );
			$this->options			= new Roud_Options( self::$domain );
			$this->cmb2					= new Roud_CMB2( self::$domain );

			$this->default_init();
		}


		private function default_init()
		{
			//投稿のタグを使わない
			// add_action('init', array( $this, 'unregister_tag_taxonomies' ));

			//投稿のカテゴリーを使わない
			add_action('init', array($this, 'unregister_category_taxonomies'));

			if( is_admin() ) {

				//管理画面用独自CSSの追加
				add_action('admin_enqueue_scripts', array($this, 'admin_style'));

				//管理画面左上のWPロゴを除去
				add_action('wp_before_admin_bar_render', array($this, 'hide_wp_admin_logo'));

				//メニューを並び替える
				add_filter('custom_menu_order', '__return_true');
				add_filter('menu_order',array($this, 'reset_filter_menu_order'));

				//日本語タイトルの場合、slugを自動的にentry-IDにする
				add_filter('wp_unique_post_slug', array($this, 'auto_post_slug'), 10, 4);

				//投稿一覧の表示項目をカスタマイズ
				add_filter('manage_posts_columns', array($this, 'custom_columns'), 10, 2);
				add_filter('manage_posts_custom_column', array($this, 'custom_add_columns'), 10, 2);
			}
			else {

				//デフォルトでのjQueryロードはしない
				add_action('wp_enqueue_scripts', array($this, 'deregister_jquery'));

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

		/**
		 * Methods
		 *=====================================================*/

		function admin_style(){
			wp_enqueue_style( 'my_admin_style', get_template_directory_uri().'/assets/css/admin-style.css' );
		}
		function custom_columns( $columns, $post_type )
		{
			unset($columns['author']);
			$reorders = [];
			foreach( $columns as $key => $value ) {
				$reorders[$key] = $value;
				if( $key === 'title' && $post_type === 'post' ) {
					$reorders['meta_description'] = __('Meta Description', self::$domain);
					$reorders['meta_robots'] = __('Meta Robots', self::$domain);
				}
			}
			return $columns = $reorders;
		}

		function custom_add_columns( $column_name, $post_id )
		{
			if( $column_name === 'meta_description' ) {
				$desc = get_post_custom($post_id);
				$desc = ( isset($desc['meta_desc'][0]) ) ? $desc['meta_desc'][0] : __('No settings', self::$domain);
				echo esc_attr($desc);
			}

			if( $column_name === 'meta_robots' ) {
				$robots = get_post_custom($post_id);
				$robots = ( isset($robots['meta_robots'][0]) ) ? $robots['meta_robots'][0] : get_option('blog_public');
				echo esc_html( $robots ? 'index, follow' : 'noindex, follow' );
			}
		}


		//言語フォルダを指定する
		function set_load_theme_textdomain( $folder = 'languages' )
		{
			load_theme_textdomain(get_template_directory() . '/' . $folder);
		}

		function auto_post_slug( $slug, $post_ID, $post_status, $post_type ) {
			if( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) {
				//$slug = utf8_uri_encode( $post_type ) . '-' . $post_ID;
				$slug = 'entry-' . $post_ID;
			}
			return $slug;
		}

		//管理画面左上のWPロゴ除去
		function hide_wp_admin_logo() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu( 'wp-logo' );
		}

		//jQuery をロード
		function deregister_jquery()
		{
			wp_deregister_script('jquery');
		}

		//投稿関係のメニューを上部に上げる
		function reset_filter_menu_order( $menu_order )
		{
			$menu = array();

			//最初の"投稿"までのメニューを取得
			foreach( $menu_order as $key => $val ) {
				if( 0 === strpos( $val, 'edit.php' ) ) {
					break;
				}
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
		function clean_wp_head( array $keys = array() )
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
		function unregister_tag_taxonomies()
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
		function unregister_category_taxonomies()
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
