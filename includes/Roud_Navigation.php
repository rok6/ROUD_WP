<?php

class Roud_Navigation
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;

		/*	メニューの有効化	*/
		add_theme_support( 'menus' );

		/*	カスタムメニューの位置を登録	*/
		register_nav_menus ( [
			'primary'		=> __('メインメニュー', self::$domain),
			'social'		=> __('ソーシャル', self::$domain),
		]);

		/*	管理画面出力の整形	*/
		add_filter('wp_nav_menu_args', function( array $args ) {
			$args['indent'] = isset($args['indent']) ? $args['indent'] + 1 : 0;
			$indent = str_repeat( "\t", $args['indent'] + 1 );

			$args['items_wrap']	= '';
			$args['items_wrap'] .= PHP_EOL . $indent . '<ul>';
			$args['items_wrap'] .= '%3$s';
			$args['items_wrap'] .= PHP_EOL . $indent .'</ul>';

			$indent = str_repeat( "\t", $args['indent'] );
			$args['items_wrap'] .= PHP_EOL . $indent;

			return $args;
		});
		add_filter('wp_nav_menu', function( $wp_nav_menu ) {
			return $wp_nav_menu . PHP_EOL;
		});

		/*	管理画面のカスタムメニューの位置変更	*/
		if( is_admin() ) {
			add_action('admin_menu', array( $this, 'reset_filter_primary_nav_menu' ));
		}
	}

	//外観サブメニュー内のメニュー項目を外部に出す
	public function reset_filter_primary_nav_menu()
	{
		//メニューをサブメニューから除去
		remove_submenu_page( 'themes.php','nav-menus.php' );
		//メインメニュー内に"メニュー"として追加
		add_menu_page(
			__( 'メニュー', self::$domain ),
			__( 'メニュー', self::$domain ),
			'edit_theme_options',
			'nav-menus.php',
			'',
			null,
			5
		);

	}
}


class Roud_Walker extends Walker_Nav_Menu
{
	function start_lvl( &$output, $depth = 0, $args = array() )
	{
		$output .= "</li>" . PHP_EOL;
		$output .= $this->indent($args->indent, $depth) . "<li>" . PHP_EOL;
		$output .= $this->indent($args->indent, $depth + 1) . "<ul class=\"nav-children\">";
	}

	function end_lvl( &$output, $depth = 0, $args = array() )
	{
		$output .= PHP_EOL . $this->indent($args->indent, $depth + 1) . "</ul>" . PHP_EOL;
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
	{
		if( $item->menu_item_parent ) {
			$depth++;
		}
		$output .= PHP_EOL . $this->indent($args->indent, $depth) . "<li>";
		$output .= $this->builder($item, $depth, $args);
	}

	function end_el( &$output, $item, $depth = 0, $args = array() )
	{
		if( in_array('menu-item-has-children', $item->classes) ) {
			$output .= $this->indent($args->indent, $depth);
		}
		$output .= "</li>";
	}

	private function indent( $indent, $depth )
	{
		$indent = $indent + $depth + 2;
		return str_repeat( "\t",	$indent );
	}

	private function builder( $item, $depth, $args)
	{
		// link attributes
		$attributes	= ! empty( $item->attr_title )	? ' title="'	. esc_attr( $item->attr_title )	 .'"' : '';
		$attributes .= ! empty( $item->target )			? ' target="'	. esc_attr( $item->target )			 .'"' : '';
		$attributes .= ! empty( $item->xfn )				? ' rel="'		. esc_attr( $item->xfn )				 .'"' : '';
		$attributes .= ! empty( $item->url )				? ' href="'		. esc_attr( $item->url )				 .'"' : '';

		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args->link_after,
			$args->after
		);
		return apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
