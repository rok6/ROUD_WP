<?php

class WPRD_CMB2
{
	static private $domain;
	static private $allow_posts = [];

	public function __construct( $domain )
	{
		if( !is_admin() ) {
			return false;
		}

		self::$domain = $domain;
		/* カスタムフィールドを有効化させる投稿タイプ（配列：カンマ区切り） */
		self::$allow_posts = ['post',];


		/* Load CMB2 */
		if( !function_exists(WP_PLUGIN_DIR . '/cmb2/init.php') ) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');

			if( is_plugin_active('cmb2/init.php') ) {

				add_action('cmb2_init', function() {
					$this->add_meta_fields();
					/* admin columns */
					$this->custom_admin_columns();
				});

			}
		}

	}


	/**
	 * Add Custom Fields
	 *=====================================================*/
	private function add_meta_fields()
	{
		$prefix = 'meta_';

		$cmb = new_cmb2_box([
			'id'						=>	$prefix . 'fields',
			'title'					=> __('Meta data', self::$domain),
			'object_types'	=> self::$allow_posts,
			'context'				=> 'normal',
			'priority'			=> 'high',
			'show_names'		=> true,
		]);

		$cmb->add_field([
			'name'		=> __('robots', self::$domain),
			'id'			=> $prefix . 'robots',
			'type'		=> 'select',
			'default'	=> get_option('blog_public'),
			'options'	=> [
				'noindex, follow',
				'index, follow',
			],
		]);

		$cmb->add_field([
			'name'			=> __('description', self::$domain),
			'id'				=> $prefix . 'description',
			'type'			=> 'textarea_small',
			'attributes'	=> [
				'maxlength' => 120,
			],
		]);
	}

	/* コールバックサンプル */
	public function field_meta_set_desc($cmb_args, $cmb)
	{
		$content = get_post($cmb->object_id())->post_content;
		$content = strip_shortcodes($content);
		$content = wp_trim_words($content, 100, '…');
		echo $content;
	}


	/**
	 * Admin Columns
	 *=====================================================*/
	private function custom_admin_columns()
	{
		$this->add_columns();
		$this->add_quickedit();
		$this->add_cmb2_scripts_in_admin();
	}


	private function add_cmb2_scripts_in_admin()
	{
		add_action( 'admin_enqueue_scripts', function() {
			global $post_type;
			if( !(isset($post_type) && in_array($post_type, self::$allow_posts, true)) ) {
				return;
			}
			wp_enqueue_script( 'my_custom_script', get_template_directory_uri().'/assets/js/admin_custom_fields.js', false, null, true );
		});
	}


	private function add_columns()
	{

		foreach( self::$allow_posts as $post_type) {

			if( $post_type === 'post' ) {
				$post_type = 'posts';
			}
			else if( $post_type === 'page' ) {
				$post_type = 'pages';
			}
			else {
				$post_type .= '_posts';
			}

			/**
			 * カラムの追加
			 */
			add_filter('manage_'.$post_type.'_columns', function($columns, $post_type) {

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
			}, 10, 2);

			/**
			 * 投稿ごとの値の表示
			 */
			add_filter('manage_'.$post_type.'_custom_column', function($column_name, $post_id) {

				switch( $column_name ) {

					case 'meta_description':
						$desc = get_post_custom($post_id);
						$desc = ( isset($desc['meta_description'][0]) ) ? $desc['meta_description'][0] : '';
						echo esc_attr($desc);
						break;

					case 'meta_robots':
						$robots = get_post_custom($post_id);
						$robots = ( isset($robots['meta_robots'][0]) ) ? $robots['meta_robots'][0] : get_option('blog_public');
						echo esc_html( $robots ? 'index, follow' : 'noindex, follow' );
						break;
				}

			}, 10, 2);

		}

	}


	private function add_quickedit()
	{
		add_action( 'quick_edit_custom_box', function($column_name, $post_type) {
			static $print_nonce = true;

			if( $print_nonce ) {
				$print_nonce = FALSE;
				wp_nonce_field( 'quick_edit_action', $post_type . '_edit_nonce' );
			}

			switch( $column_name ) {

				case 'meta_description':
					$label = [
						'<span class="title">description</span>',
						'<textarea name="meta_description" maxlength="120"></textarea>',
					];
					break;

				case 'meta_robots':
					$label = [
						'<span class="title">robots</span>',
						'<select name="meta_robots">',
						[
							'<option value="0">noindex, follow</option>',
							'<option value="1">index, follow</option>',
						],
						'</select>',
					];
					break;

				default: $label = '';
			}

			echo Helper::_render([
				'<fieldset class="inline-edit-col-right inline-custom-meta">',
				[
					'<div class="inline-edit-col quick-col-' . $column_name . '">',
					[
						'<label class="inline-edit-group">',
						$label,
						'</label>',
					],
					'</div>',
				],
				'</fieldset>',
			]);

		}, 10, 2 );


		add_action('save_post', function($post_id) {

			if( false === $k = array_search(get_post_type( $post_id ), self::$allow_posts, true) ) {
				return;
			}
			else {
				$slug = self::$allow_posts[$k];
			}

			if( !current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			$_POST += array("{$slug}_edit_nonce" => '');
			if( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"], 'quick_edit_action' ) ) {
				return;
			}

			if( isset( $_REQUEST['meta_description'] ) ) {
				update_post_meta( $post_id, 'meta_description', $_REQUEST['meta_description'] );
			}

			if( isset( $_REQUEST['meta_robots'] ) ) {
				update_post_meta( $post_id, 'meta_robots', $_REQUEST['meta_robots'] );
			}

		});

	}
}
