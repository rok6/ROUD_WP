<?php
namespace Roud\module\controller;

class SearchController extends Controller
{

	public function __construct( $render_type = '' )
	{
		$this->set( 'search' );
		$this->render( $render_type );
	}

}
