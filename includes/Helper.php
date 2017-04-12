<?php

class Helper
{
	/*=====================================================

		@ Helpers

			logo
			robots
			description
			title
			thumbnail
			author
			datetime
			content
			tags

	*=====================================================*/


	/**
	 * logo
	 *=====================================================*/
	static public function logo( $link = false )
	{
		$title = $link ?
			sprintf('<a href="%1$s">%2$s</a>',
				esc_url( get_home_url() ),
				esc_html( get_bloginfo('name') )
			)
		: get_bloginfo('name');

		return sprintf(
			'<h1>%1$s</h1>' . PHP_EOL,
			(string)$title
		);
	}

	/**
	 * robots
	 *=====================================================*/
	static public function robots()
	{
		$robots = false;

		if( is_archive() ) {
			$robots = false;
		}

		if( isset(get_post_custom()['meta_robots'][0]) ) {
			$robots = get_post_custom()['meta_robots'][0];
		}
		else {
			$robots = get_option('blog_public');
		}

		if( !!get_option('blog_public') === !!$robots ) {
			return;
		}

		return sprintf(
			'<meta name="robots" content="%1$s" />' . PHP_EOL,
			esc_html( $robots ? 'index, follow' : 'noindex, follow' )
		);
	}

	/**
	 * description
	 *=====================================================*/
	static public function description()
	{
		$desc = get_post_custom();
		$desc = ( isset($desc['meta_desc'][0]) ) ? $desc['meta_desc'][0] : get_bloginfo('description');

		if( $desc === '' ) {
			return;
		}

		return sprintf(
			'<meta name="description" content="%1$s" />' . PHP_EOL,
			esc_html( $desc )
		);
	}

	/**
	 * title
	 *=====================================================*/
	static public function title( $id, $level = 2, $link = false )
	{
		$title = $link ?
			sprintf('<a href="%1$s">%2$s</a>',
				esc_url( get_permalink($id) ),
				esc_html( get_the_title($id) )
			)
		: esc_html( get_the_title($id) );

		return sprintf(
			'<h%1$d>%2$s</h%1$d>',
			(int)$level,
			(string)$title
		);
	}

	/**
	 * thumbnail
	 *=====================================================*/
	static public function thumbnail( $id, array $args = [] )
	{
		$args = $args += [
			'alt' => '',
			'title' => '',
		];
		$args['alt'] = trim( strip_tags( $args['alt'] ) );
		$args['title'] = trim( strip_tags( $args['title'] ) );
		return get_the_post_thumbnail($id, 'medium', $args);
	}

	/**
	 * author
	 *=====================================================*/
	static public function author( $userid, $field = 'nickname' )
	{
		return sprintf('<span>%1$s</span>' . PHP_EOL,
			esc_html( get_the_author_meta($field, $userid) )
		);
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
			'<span class="elapsed-time">%1$s前</span>',
			human_time_diff( get_post_modified_time('U', false, $id), date_i18n('U') )
		);

		$entry_date .= sprintf(
			'<time datetime="%1$s" class="published">%2$s</time>',
			esc_attr($published_datetime),
			esc_html($published)
		);

		if( $published !== $updated ) {
			$entry_date .= sprintf(
				'<time datetime="%1$s" class="updated">%2$s</time>',
				esc_attr($updated_datetime),
				esc_html($updated)
			);
		}
		return $entry_date . PHP_EOL;
	}

	/**
	 * content
	 *=====================================================*/
	static public function content()
	{
		the_post();
		return PHP_EOL . str_replace( ']]>', ']]&gt;', apply_filters('the_content', get_the_content()) ) . PHP_EOL;
	}

	/**
	 * tags
	 *=====================================================*/
	static public function tags( $id )
	{
		$elm = '';
		$taxonomies = get_object_taxonomies(get_post_type());
		$tags				= wp_get_object_terms($id, $taxonomies);

		if( !empty($tags) && !is_wp_error($tags) ) {

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
	 * pages
	 *=====================================================*/
	static public function paginations()
	{
		global $wp_query;

		$paged = get_query_var('paged') ? : 1;
		$max_pages = $wp_query->max_num_pages;

		if( $max_pages <= 1) {
			/* ページがない場合は返す */
			return;
		}

		$page_list = [];
		$range = 1;
		$show_items = ($range * 2) + 1;

		/* 一番最初のページへのリンク */
		if( $paged > 2 && $show_items < $max_pages && $paged > $range + 1 ) {
			$page_list[] = '<li><a href="' . get_pagenum_link(1) . '">&laquo;</a></li>' . PHP_EOL;
		}
		/* 一つ前のページへのリンク */
		if( $paged > 1 && $show_items < $max_pages ) {
			$page_list[] = '<li><a href="' . get_pagenum_link($paged - 1) . '">&lsaquo;</a></li>';
		}

		for( $i = 1; $i <= $max_pages; $i++ ) {
			if( !($i < $paged - $range || $i > $paged + $range) ) {
				$page_list[] = ( $paged === $i ) ? '<li><span class="current">' . $i . '</span></li>'
																				 : '<li><a href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';

 			}
		}

		/* 一つ後のページへのリンク */
		if( $paged < $max_pages && $show_items < $max_pages ) {
			$page_list[] = '<li><a href="' . get_pagenum_link($paged + 1) . '">&rsaquo;</a></li>';
		}
		/* 一番最後のページへのリンク */
		if( $paged < $max_pages - 1 && $show_items < $max_pages && $paged > $range - 1 ) {
			$page_list[] = '<li><a href="' . get_pagenum_link($max_pages) . '">&raquo;</a></li>' . PHP_EOL;
		}

		/**
		 * 配列の階層によりindentを設定
		 * _render()
		 * @param array $array_elements = []
		 * @param $indent = 0
		 */
		return self::_render([
			'<div class="page-guide">' . $paged . 'of' . $max_pages . '</div>',
			[
				'<ul class="pager">',
				$page_list,
				'</ul>',
			],
		], 1);

	}

	private static function _render( array $array_elements = [], $indent = 0 )
	{
		$element = '';
		foreach( $array_elements as $value ) {
			if( is_array($value) ) {
				$element .= self::_render($value, $indent + 1);
				continue;
			}
			$element .= str_repeat( "\t", $indent ) . $value . PHP_EOL;
		}
		return $element;
	}

}
