<?php

class Helper
{
	public function __construct()
	{

	}

	static public function title( $id, array $args = array() )
	{
		//$val->ID, array('url' => get_permalink($val->ID), 'alt' => $val->post_title)
		_dump( get_the_title($id) );
	}
	static public function thumbnail( $id, array $args = array() )
	{
		//$val->ID, array('url' => get_permalink($val->ID), 'alt' => $val->post_title)
	}
	static public function publish_date( $id, array $args = array() )
	{
		//$val->ID, array('url' => get_permalink($val->ID), 'alt' => $val->post_title)
	}
	static public function content( $id, array $args = array() )
	{
		//$val->ID, array('url' => get_permalink($val->ID), 'alt' => $val->post_title)
	}


	private function builder( $elm, array $attrs = array() )
	{
		$params = '';

		foreach( $attrs as $key => $val ) {
			if( is_bool($val) ) {
				if( $val ) $params .= " $key";
			}
			else {
				$params .= " $key=\"$val\"";
			}
		}

		//

	}

}
