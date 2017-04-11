<?php
namespace Roud\module\model;

class Model
{
  protected $default = array();

  public function __construct()
  {
    $this->default = array(
      'post_status' => 'publish',
      'orderby'     => 'post_date',
      'order'       => 'desc',
      'category'    => null,
      'exclude'     => null,
      'posts_per_page' => get_option('posts_per_page'),
      'paged'					 => get_query_var('paged'),
    );
  }

  public function get( array $args = array() ) {
    if( is_single() ) {
      return get_post();
    }
    return get_posts($args += $this->default);
  }
}
