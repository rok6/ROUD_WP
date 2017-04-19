<?php

class WPRD_Organize
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;

		$this->default_init();
	}

	private function default_init()
	{
		//$this->deregister_category_taxonomies();
		//$this->deregister_tag_taxonomies();

		if( is_admin() ) {
			$this->remove_wp_admin_logo();
			$this->reorder_admin_menu();
			$this->auto_post_slug();
		}
		else {
			//$this->deregister_jquery();
			$this->clean_wp_head([
				'wp_generator',
				'wp_shortlink_wp_head',
				'feed_links',
				'feed_links_extra',
				/* EditURI */
				'rsd_link',
				/* wlwmanifest */
				'wlwmanifest_link',
				/* page-links */
				'index_rel_link',
				'parent_post_rel_link',
				'start_post_rel_link',
				'adjacent_posts_rel_link_wp_head',
				/* oEmbed */
				//'rest_output_link_wp_head',
				'wp_oembed_add_discovery_links',
				'wp_oembed_add_host_js',
				/* emoji */
				'print_emoji_detection_script',
				'print_emoji_styles',
				/* inline-style */
				'recent_comments_style',
			]);
		}
	}

	private function clean_wp_head( array $keys = [] )
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
						remove_action( 'wp_head', [
							$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'
						]);
					});
					break;
			}
		}
	}


	private function remove_wp_admin_logo() {
		add_action('wp_before_admin_bar_render', function() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu( 'wp-logo' );
		});
	}


	private function deregister_jquery()
	{
		add_action('wp_enqueue_scripts', function() {
			wp_deregister_script('jquery');
		});
	}


	private function reorder_admin_menu()
	{
		add_filter('custom_menu_order',		'__return_true');
		add_filter('menu_order', function( $menu_order ) {
			$menu = [];

			foreach( $menu_order as $key => $val ) {
				if( 0 === strpos( $val, 'edit.php' ) ) {
					break;
				}
				$menu[] = $val;
				unset( $menu_order[$key] );
			}

			foreach( $menu_order as $key => $val ) {
				if( 0 === strpos( $val, 'edit.php' ) ) {
					$menu[] = $val;
					unset( $menu_order[$key] );
				}
			}

			return array_merge( $menu, $menu_order );
		});
	}


	private function deregister_tag_taxonomies()
	{
		add_action('init', function() {
			global $wp_taxonomies;

			if( !empty($wp_taxonomies['post_tag']->object_type) ) {
				foreach( $wp_taxonomies['post_tag']->object_type as $i => $object_type ) {
					if( $object_type == 'post' ) {
						unset( $wp_taxonomies['post_tag']->object_type[$i] );
					}
				}
			}
			return true;
		});
	}


	private function deregister_category_taxonomies()
	{
		add_action('init', function() {
			global $wp_taxonomies;

			if( !empty($wp_taxonomies['category']->object_type) ) {
				foreach( $wp_taxonomies['category']->object_type as $i => $object_type ) {
					if( $object_type === 'post' ) {
						unset( $wp_taxonomies['category']->object_type[$i] );
					}
				}
			}
			return true;
		});
	}


	private function auto_post_slug()
	{
		add_filter('wp_unique_post_slug', function( $slug, $post_ID, $post_status, $post_type ) {
			if( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) {
				//$slug = utf8_uri_encode( $post_type ) . '-' . $post_ID;
				$slug = 'entry-' . $post_ID;
			}
			return $slug;
		}, 10, 4);
	}

}
