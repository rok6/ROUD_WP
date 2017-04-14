<?php
namespace Roud\module\controller;

class SearchController extends Controller
{

	public function __construct( $render_type = '' )
	{
		$this->set( 'search' );

		global $wp_query;
		$this->view_vars['found_posts'] = $wp_query->found_posts;
		if( !$this->view_vars['found_posts'] ) {
			$render_type = 'none';
		}

		$this->render( $render_type );
	}

}
