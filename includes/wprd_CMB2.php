<?php

class WPRD_CMB2
{
	static private $domain;
	static private $allow_posts = [];

	private $custom_fields = null;

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
					$this->custom_fields = $this->add_meta_fields();
					/* admin columns */
					$this->custom_admin_columns([
						'meta_description'	=> __('Meta Description', self::$domain),
						'meta_robots'				=> __('Meta Robots', self::$domain),
					]);
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

		return $cmb;
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
	private function custom_admin_columns( array $field_names )
	{
		$this->add_cmb2_scripts_in_admin();
		$this->add_quickedit($field_names);

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

			add_filter('manage_'.$post_type.'_columns', function($columns, $post_type) use($field_names) {
				unset($columns['author']);
				$reorders = [];

				foreach( $columns as $key => $value ) {
					$reorders[$key] = $value;
					if( $key === 'title' && $post_type === 'post' ) {
						foreach ($field_names as $field_key => $field_label) {
							$reorders[$field_key] = $field_label;
						}
					}
				}

				return $columns = $reorders;
			}, 10, 2);


			add_filter('manage_'.$post_type.'_custom_column', function($column_name, $post_id) use($field_names) {

				if( !in_array($column_name, array_keys($field_names), true) ) {
					return;
				}

				$meta = get_post_custom($post_id);
				$field_args = $this->get_column_field($column_name, $meta);

				echo esc_html($field_args['value']);
			}, 10, 2);

		}
	}


	private function get_column_field($column_name, $meta)
	{
		$field = $this->custom_fields->get_field($column_name);
		$field_args = [];
		$field_args['type'] = $field->args['type'];
		$value = ( isset($meta[$column_name][0]) ) ? $meta[$column_name][0] : $field->args['default'];

		if( false !== strpos($field_args['type'], 'select') ) {
			$field_args['options'] = $field->args['options'];
			$field_args['value'] = $field->args['options'][$value];
		}
		else {
			$field_args['value'] = $value;
		}

		return $field_args;
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


	private function add_quickedit($field_names)
	{
		add_action( 'quick_edit_custom_box', function($column_name, $post_type) use($field_names) {
			static $print_nonce = true;

			if( $print_nonce ) {
				$print_nonce = false;
				wp_nonce_field( 'quick_edit_action', $post_type . '_edit_nonce' );
			}

			if( !in_array($column_name, array_keys($field_names), true) ) {
				return;
			}

			$label = [''];
			$field = $this->custom_fields->get_field($column_name);
			$type = $field->args['type'];


			$label[] = '<span class="title">'. $field_names[$column_name] .'</span>';

			if( false !== strpos($type, 'textarea') ) {
				$label[] = '<textarea name="'. $column_name .'"></textarea>';
			}

			if( false !== strpos($type, 'select') ) {
				$options = $field->args['options'];
				$label[] = '<select name="'. $column_name .'">';
				foreach ($options as $key => $value) {
					$label[] = '<option value="'. $key .'">'. $value .'</option>';
				}
				$label[] = '</select>';
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


		add_action('save_post', function($post_id) use($field_names) {

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

			foreach ($field_names as $key => $value) {
				if( isset( $_REQUEST[$key] ) ) {
					update_post_meta( $post_id, $key, $_REQUEST[$key] );
				}
			}

		});

	}
}
