<?php
namespace Roud\module\controller;

class PostController extends Controller
{

	public function __construct( $render_type )
	{
		$this->set( 'post' );
		$this->render( $render_type );
	}

}
