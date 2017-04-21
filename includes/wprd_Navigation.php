<?php

class WPRD_Navigation
{
	static private $domain;

	public function __construct( $domain, array $menus )
	{
		self::$domain = $domain;

		/*	カスタムメニューの位置を登録	*/
		register_nav_menus ( $menus );

		/*	管理画面のカスタムメニューの位置変更	*/
		if( is_admin() ) {
			$this->reorder_custom_nav_menu();
		}

	}

	//外観サブメニュー内のメニュー項目を外部に出す
	private function reorder_custom_nav_menu()
	{
		add_action('admin_menu', function() {
			remove_submenu_page('themes.php', 'nav-menus.php');
			add_menu_page( __('メニュー', self::$domain), __('メニュー', self::$domain), 'edit_theme_options', 'nav-menus.php', '', null, 5 );
		});
	}

}


/**
 * WPRD_Walker
 **/
class WPRD_Walker
{
	function __construct( $menus, $indent = 0 )
	{
		$this->indent = $indent;
		$this->history = [];
		$this->output = '';
		$this->prev_depth = '';


		foreach( $menus as $item ) {
			$depth = $this->get_child_depth($this->history, $item);
			// 前回が子だった場合リストを閉じる
			if( $depth < $this->prev_depth ) {
				$this->end_lvl($this->output, $item, ($this->prev_depth-1)*2);
			}
			// 一番上の親だった場合
			if( !$depth ) {
				$this->start_el($this->output, $item, $depth);
			}
			// 子の場合
			else {
				// 子のさらに子だった場合
				if( $depth > $this->prev_depth ) {
					$this->start_lvl($this->output, $item, ($depth-1)*2);
				}
				$this->start_el($this->output, $item, $depth*2);
			}
			// リストを閉じる
			$this->end_el($this->output, $item, $depth);
			// 前回の深度を更新
			$this->prev_depth = $depth;
			// 親リストの登録
			array_unshift($this->history, $item['ID']);
		}

	}

	protected function start_el(&$output, $item, $depth)
	{
		$output .= PHP_EOL.$this->indent($depth).'<li>';
		$output .= $this->anchor($item);
	}

	protected function end_el(&$output, $item, $depth)
	{
		$output .= '</li>';
	}

	protected function start_lvl(&$output, $item, $depth)
	{
		$output .= PHP_EOL.$this->indent($depth).'<li>';
		$output .= PHP_EOL.$this->indent($depth+1).'<ul>';
	}

	protected function end_lvl(&$output, $item, $depth)
	{
		$output .= PHP_EOL.$this->indent($depth+1).'</ul>';
		$output .= PHP_EOL.$this->indent($depth).'</li>';
	}


	public function get_element()
	{
		return $this->output.PHP_EOL;
	}


	protected function get_child_depth(&$history, $item)
	{
		if( current($history) !== $item['parent_id'] ) {

			if( in_array($item['parent_id'], $history) ) {
				array_shift($history);
				return $this->get_child_depth($history, $item);
			}

			$history = [];
			return 0;
		}
		else {
			return count($history);
		}
	}

	protected function anchor($item)
	{
		$title	= esc_html($item['title']);
		$attrs	= sprintf( ' href="%1$s"', esc_url($item['url']) );
		$attrs .= !empty($item['target'])	? sprintf( ' target="%1$s"',	esc_attr($item['target']) )								: '';
		$attrs .= !empty($item['classes'])	? sprintf( ' class="%1$s"',		esc_attr(implode(' ', $item['classes'])) )	: '';
		$attrs .= !empty($item['attr_title'])	? sprintf( ' title="%1$s"',		esc_attr(implode(' ', $item['classes'])) )	: '';
		$attrs .= !empty($item['xfn'])					? sprintf( ' rel="%1$s"',			esc_attr(implode(' ', $item['classes'])) )	: '';

		return sprintf('<a%2$s>%1$s</a>',
			$title,
			$attrs
		);
	}

	protected function indent($depth)
	{
		return str_repeat( "\t",	$this->indent + $depth );
	}

}
