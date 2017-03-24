<?php

class Roud_CMB2
{
	static private $domain;

  public function __construct( $domain )
  {
		self::$domain = $domain;
  }

  public function init()
  {
    if( !is_admin() ) {
      return false;
    }

    if( !function_exists(WP_PLUGIN_DIR . '/cmb2/init.php') ) {
      require_once(ABSPATH . 'wp-admin/includes/plugin.php');
      if( is_plugin_active('cmb2/init.php') ) {
        add_action('cmb2_init', array($this, 'cmb2_add_fields'));
      }
    }
  }

  public function cmb2_add_fields()
  {
		$this->field_meta();
  }

  private function field_meta()
  {
    $prefix = 'meta_';

    $cmb = new_cmb2_box(array(
      'id'            =>  $prefix . 'fields',
      'title'         => __('Meta data', self::$domain),
      'object_types'  => array( 'post', ),
      'context'       => 'normal',
      'priority'      => 'high',
      'show_names'    => true,
    ));

    $cmb->add_field(array(
      'name'		=> __('robots', self::$domain),
      'id'      => $prefix . 'robots',
      'type'		=> 'select',
      'default'	=> get_option('blog_public'),
      'options'	=> array(
        'noindex, follow',
        'index, follow',
      ),
    ));

    $cmb->add_field(array(
      'name'			=> __('description', self::$domain),
      'id'        => $prefix . 'desc',
      'type'			=> 'textarea_small',
      'default_cb'	=> array( $this, 'field_meta_set_desc' ),
      'attributes'	=> array(
        //'rows' => 3,
      ),
    ));
  }

  public function field_meta_set_desc($cmb_args, $cmb)
  {
    $content = get_post($cmb->object_id())->post_content;
    $content = strip_shortcodes($content);
    $content = wp_trim_words($content, 98, ' …');
    echo $content;
  }

}
