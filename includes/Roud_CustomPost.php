<?php

class Roud_CustomPost
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;
	}

	public function add( array $custom_posts = [] )
	{
		foreach( $custom_posts as $f ) {
			if( method_exists($this, $f) ) {
				$this->$f();
			}
		}
	}

	private function news()
	{
		$cpt_args = array(
			'label'					=> 'NEWS',
			'public'				=> true,
			'has_archive'		=> true,
			'show_in_rest'	=> true,
			'menu_position' => 5,
		);
		register_post_type('news', $cpt_args);
		$tax_args = array(
			'label'					=> __('カテゴリー', self::$domain),
			'public'				=> true,
			'hierarchical'	=> true,
			'show_in_rest'	=> true,
			'show_admin_column'	=> true,
		);
		register_taxonomy('news_cat', 'news', $tax_args);
	}

	private function illurweb()
	{
		$cpt_args = array(
			'label'					=> __('ILLURWEB', self::$domain),
			'public'				=> true,
			'has_archive'		=> true,
			'hierarchical'	=> true,
			'show_in_rest'	=> true,
			'menu_position'	=> 5,
			'support'				=> array(
				'page-attributes',
			),
		);
		register_post_type('illurweb', $cpt_args);
	}

}
