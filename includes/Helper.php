<?php

class Helper
{
	public function __construct()
	{

	}

	/**
	 * title
	 *=====================================================*/
	static public function title( $id, $level = 2 )
	{
		return sprintf('<h%1$d><a href="%3$s">%2$s</a></h%1$d>',
			(int)$level,
			esc_html( get_the_title($id) ),
			esc_url( get_permalink($id) )
		);
	}

	/**
	 * author
	 *=====================================================*/
	static public function author( $userid, $field = 'nickname' )
	{
		return sprintf('<span>%1$s</span>',
			esc_html( get_the_author_meta($field, $userid) )
		);
	}

	/**
	 * thumbnail
	 *=====================================================*/
	static public function thumbnail( $id, array $args = array('alt' => '', 'title' => '') )
	{
		$args['alt'] = trim( strip_tags( $args['alt'] ) );
		$args['title'] = trim( strip_tags( $args['title'] ) );
		return get_the_post_thumbnail($id, 'medium', $args);
	}

	/**
	 * datetime
	 *=====================================================*/
	static public function datetime( $id )
	{
		$entry_date					= '';
		$format							= get_option('date_format');
		$published					= get_the_date($format, $id);
		$published_datetime = get_the_date(DATE_W3C, $id);
		$updated						= get_post_modified_time($format, false, $id);
		$updated_datetime		= get_post_modified_time(DATE_W3C, false, $id);

		$entry_date .= sprintf(
			'<time datetime="%1$s" class="published">%2$s</time>' . PHP_EOL,
			esc_attr($published_datetime),
			esc_html($published)
		);

		if( $published !== $updated ) {
			$entry_date .= sprintf(
				'<time datetime="%1$s" class="updated">%2$s</time>' . PHP_EOL,
				esc_attr($updated_datetime),
				esc_html($updated)
			);
		}
		return $entry_date;
	}

	/**
	 * content
	 *=====================================================*/
	static public function content()
	{
		the_post();
		return str_replace( ']]>', ']]&gt;', apply_filters('the_content', get_the_content()) );
	}

	/**
	 * tags
	 *=====================================================*/
	static public function tags( $id )
	{
		$elm = '';
		$tags = get_the_tags($id);

		if( is_array($tags) ) {
			foreach( $tags as $tag ) {
				$elm .= sprintf('<a href="%2$s" class="tag">%1$s</a>',
					esc_html( $tag->name ),
					esc_url( get_tag_link($tag->term_id) )
				);
			}
		}
		else {
			$elm .= '<span class="no-tag">-</span>';
		}

		return $elm . PHP_EOL;
	}

	/**
	 * builder
	 *=====================================================*/
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
