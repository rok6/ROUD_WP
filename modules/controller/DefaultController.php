<?php
namespace Roud\module\controller;

class DefaultController extends Controller
{

  public function __construct( $render_type )
  {
    $this->set( $this->get_post_type() );

    global $wp_query;

    if( !$wp_query->found_posts ) {
      $render_type = 'none';
    }

    $this->render( $render_type );
  }

  private function get_post_type()
  {
    global $wp_query;
    _dump($wp_query);
    if( is_archive() ) {

      if( $slug = get_query_var( 'post_type' ) ) {
        return $slug;
      }
      else {
        return get_queried_object()->taxonomy;
      }

    }
    return get_post_type();
  }

}
