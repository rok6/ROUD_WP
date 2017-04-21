<?php

class Helper
{
	/*=====================================================

		@ Helpers

			logo
			robots
			description
			headline
			breadcrumb
			navigation_menu
			title
			thumbnail
			author
			datetime
			content
			taxonomies
			post-paginations
			page_links

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
		//全体の公開設定が非公開の時はスルー
		if( !$public = get_option('blog_public') ) {
			return;
		}

		//カスタムフィールドの値を取得
		if( isset(get_post_custom()['meta_robots'][0]) ) {
			$public = get_post_custom()['meta_robots'][0];
		}

		//アーカイブページは共通して noindex 設定にする
		if( is_archive() ) {
			$public = false;
		}

		//取得した値が true の場合はスルー
		if( !!$public ) {
			return;
		}

		return '<meta name="robots" content="noindex, follow" />' . PHP_EOL;
	}

	/**
	 * description
	 *=====================================================*/
	static public function description()
	{
		$desc = get_post_custom();
		$desc = ( isset($desc['meta_description'][0]) && $desc['meta_description'][0] !== '' ) ?
			$desc['meta_description'][0]
		: ( is_front_page() ) ? get_bloginfo('description') : '';

		if( $desc === '' ) {
			return;
		}

		return sprintf(
			'<meta name="description" content="%1$s" />' . PHP_EOL,
			esc_html( $desc )
		);
	}


	/**
	 * headline
	 *=====================================================*/
	static public function headline()
	{
		$label = '';

		if( is_front_page() ) {
			$label = __('What\'s new');
		}

		else if( is_404() ) {
			$label = '404';
		}

		else if( is_search() ) {
			$label = get_search_query();
		}

		else {

			if( is_home() || is_single() ){
				$label = get_queried_object()->post_title;
			}

			else if( is_category() || is_tag() || is_tax() ) {
				$label = get_queried_object()->slug;
			}

			else {
				$label = get_queried_object()->label;
			}

		}

		return esc_html($label);
	}


	/**
	 * breadcrumb
	 *=====================================================*/
	static public function breadcrumb()
	{
		if( is_front_page() ) {
			return;
		}

		$content = 0;

		$list = [
			'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
			[
				'<a href="' . home_url() . '" itemprop="item"><span itemprop="name">ホーム</span></a>',
				'<meta itemprop="position" content="'. ++$content .'" />',
			],
			'</li>'
		];

		if( is_search() ) {
			$list = array_merge($list, [
				'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
				[
					'<span itemprop="name">"'. self::headline() .'" の検索結果</span>',
					'<meta itemprop="position" content="'. ++$content .'" />',
				],
				'</li>'
			]);
		}
		else {

			$queried = get_queried_object();

			if( is_category() || is_tag() || is_tax() || is_single() ) {

				$post_type = ( is_single() ) ?
					$post_type = $queried->post_type
				: get_taxonomy($queried->taxonomy)->object_type[0];

				$anchor = sprintf(
					'<a href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>',
					esc_url(get_post_type_archive_link($post_type)),
					esc_html(get_post_type_object($post_type)->label)
				);
				$list = array_merge($list, [
					'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
					[
						$anchor,
						'<meta itemprop="position" content="'. ++$content .'" />',
					],
					'</li>'
				]);
			}

			$list = array_merge($list, [
				'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">',
				[
					'<span itemprop="name">'. self::headline() .'</span>',
					'<meta itemprop="position" content="'. ++$content .'" />',
				],
				'</li>'
			]);

		}

		return self::_render([
			'<ol class="container">',
			$list,
			'</ol>',
		], 2);
	}

	/**
	 * navigation
	 *=====================================================*/
	static public function navgation_menu( array $args = [] )
	{
		$default = [
			'menu'			=> '',
			'location'	=> '',
		];
		$args += $default;

		if( ($locations = get_nav_menu_locations()) && isset($locations[$args['location']]) ) {
			$menu = wp_get_nav_menu_object( $locations[$args['location']] );
			$menu_id = $menu->term_id;
		}
		else {
			$menu_id = $args['menu'];
		}

		if( !$navs = wp_get_nav_menu_items( $menu_id ) ) {
			return false;
		}

		$menus = [];

		foreach( $navs as $nav ) {
			$m = [];
			$m['ID']					= $nav->ID;
			$m['title']				= $nav->title;
			$m['attr_title'] 	= $nav->attr_title;
			$m['description']	= $nav->description;
			$m['url']					= $nav->url;
			$m['target']			= $nav->target;
			$m['xfn']					= $nav->xfn;
			$m['classes']			= array_values(array_diff($nav->classes, ['']));
			$m['parent_id'] = (int)$nav->menu_item_parent;
			$m['has_parent'] = ( !$m['parent_id'] ) ? false : true;

			$menus[] = $m;
		}

		$walker = new WPRD_Walker( $menus, 4 );

		return self::_render([
			'<ul>',
			$walker->get_element(),
			'</ul>',
		], 3);

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

		return sprintf('<h%1$d>%2$s</h%1$d>' . PHP_EOL,
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

		if( is_single() ) {
			$entry_date .= sprintf(
				'<span class="elapsed-time">%1$s前</span>',
				human_time_diff( get_post_modified_time('U', false, $id), date_i18n('U') )
			);
		}

		if( $published !== $updated ) {
			$entry_date .= sprintf(
				'<time datetime="%1$s" class="updated">%2$s</time>',
				esc_attr($updated_datetime),
				esc_html($updated)
			);
		}

		$entry_date .= sprintf(
			'<time datetime="%1$s" class="published">%2$s</time>',
			esc_attr($published_datetime),
			esc_html($published)
		);

		return $entry_date . PHP_EOL;
	}

	/**
	 * content
	 *=====================================================*/
	static public function content()
	{
		the_post();
		return sprintf( PHP_EOL . '<!-- Content -->' . PHP_EOL . '%1$s' . '<!-- //Content -->' . PHP_EOL . PHP_EOL,
			str_replace( ']]>', ']]&gt;', apply_filters('the_content', get_the_content( '続きを表示する' )) )
		);
	}

	/**
	 * tas
	 *=====================================================*/
	static public function taxonomies( $id )
	{
		$taxonomy		= get_object_taxonomies(get_post_type($id));
		$taxonomies	= wp_get_object_terms($id, $taxonomy);

		$terms = [];

		if( !empty($taxonomies) && !is_wp_error($taxonomies) ) {

			$history = [];

			foreach( $taxonomies as $term ) {

				$tax = get_taxonomy($term->taxonomy);
				$name = $tax->name;

				if( !in_array($name, $history) ) {
					$history[] = $name;
					$terms[$name] = [];
					array_unshift($terms[$name],
						sprintf('<span class="name%2$s">%1$s</span>',
							esc_html( $tax->label ),
							esc_attr(' ' . $name)
						)
					);
				}

				$terms[$name][] = sprintf('<a href="%2$s" class="term">%1$s</a>',
					esc_html( $term->name ),
					esc_url( get_tag_link($term->term_id) )
				);

			}

		}
		else {
			$terms[] = '<span class="no-taxonomy"></span>';
		}

		return self::_render($terms, 6);
	}

	/**
	 * pages
	 *=====================================================*/
	static public function post_paginations()
	{
		global $wp_query;

		$max = $wp_query->max_num_pages;

		if( $max <= 1) {
			return;
		}

		$current = get_query_var('paged') ? : 1;
		$range = 2;

		return self::_pager( $max, $current, $range );
	}

	public static function page_links()
	{
		global $page, $numpages, $multipage, $more;

		if( !$multipage ) {
			return;
		}

		$max = $numpages;
		$current = $page;
		$range = 2;

		return self::_pager( $max, $current, $range, 'page-links' );
	}


	private static function _pager( $max, $current, $range = 2, $class = '' )
	{
		$page_list = [];
		$show_items = ($range * 2) + 1;

		/* 一番最初のページへのリンク */
		if( $current > $range + 1 ) {
			$page_list[] = '<li><a href="' . esc_url(self::get_page_link(1)) . '">&laquo;</a></li>' . PHP_EOL;
		}
		/* 一つ前のページへのリンク */
		if( $current > 1 ) {
			$page_list[] = '<li><a href="' . esc_url(self::get_page_link($current - 1)) . '">&lsaquo;</a></li>';
		}

		for( $i = 1; $i <= $max; $i++ ) {
			if( !($i < $current - $range || $i > $current + $range) ) {
				$page_list[] = ( $current === $i ) ? '<li><span class="current">' . $i . '</span></li>'
																				 	 : '<li><a href="' . esc_url(self::get_page_link($i)) . '">' . $i . '</a></li>';
 			}
		}

		/* 一つ後のページへのリンク */
		if( $current < $max ) {
			$page_list[] = '<li><a href="' . esc_url(self::get_page_link($current + 1)) . '">&rsaquo;</a></li>';
		}
		/* 一番最後のページへのリンク */
		if( $current < $max - $range ) {
			$page_list[] = '<li><a href="' . esc_url(self::get_page_link($max)) . '">&raquo;</a></li>' . PHP_EOL;
		}


		if( $class !== '' ) {

			if( is_array($class) ) {
				$class = implode(' ', $class);
			}

			$class = ' ' . $class;
		}

		return self::_render([
			'<!-- .pagination -->',
			'<div class="pagination'. esc_attr($class) .'">',
			[
				'<div class="page-guide">' . $current . 'of' . $max . '</div>',
				'<ul class="pager">',
				$page_list,
				'</ul>',
			],
			'</div>',
			'<!-- //.pagination -->',
		], 1);
	}

	private static function get_page_link( $page = 1 )
	{
		$page = (int)$page;

		if( !is_single() ) {
			$page_link = get_pagenum_link($page);
		}
		else {
			$page_link = get_the_permalink();
			if( $page > 1 ) {
				$page_link .= $page . '/';
			}
		}

		return $page_link;
	}

	/**
	 * Helper render
	 *=====================================================*/
	public static function _render( array $array_elements = [], $indent = 0 )
	{
		$element = '';
		$nl = false;
		foreach( $array_elements as $value ) {
			if( is_array($value) ) {
				$element .= self::_render($value, $indent + 1);
				continue;
			}
			if( !$nl ) {
				$nl = true;
				$element .= PHP_EOL;
			}
			$element .= str_repeat( "\t", $indent ) . $value . PHP_EOL;
		}
		return $element . PHP_EOL;
	}

}
