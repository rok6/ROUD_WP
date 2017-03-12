<?php
namespace Roud;

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
    );
  }
  
  public function get( array $args = array() ) {
    return get_posts($args += $this->default);
  }
}
