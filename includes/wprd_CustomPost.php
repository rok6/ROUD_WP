<?php

class WPRD_CustomPost
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

	/**
	 * New Posts
	 *=====================================================*/

	private function news()
	{
		$custompost_args = [
			'label'					=> 'NEWS',
			'public'				=> true,
			'has_archive'		=> true,
			'show_in_rest'	=> true,
			'menu_position' => 5,
		];
		register_post_type('news', $custompost_args);
		$tax_args = [
			'label'					=> __('カテゴリー', self::$domain),
			'public'				=> true,
			'hierarchical'	=> true,
			'show_in_rest'	=> true,
			'show_admin_column'	=> true,
			'rewrite'	=> [
				'slug'	=> 'news/category'
			]
		];
		register_taxonomy('news_cat', 'news', $tax_args);
		add_rewrite_rule('news/category/([^/]+)/?$', 'index.php?news_cat=$matches[1]', 'top');
	}

	private function wordpress()
	{
		$custompost_args = [
			'label'					=> __('WordPress', self::$domain),
			'public'				=> true,
			'has_archive'		=> true,
			'hierarchical'	=> true,
			'show_in_rest'	=> true,
			'menu_position'	=> 5,
			'support'				=> [
				'page-attributes',
			],
		];
		register_post_type('wordpress', $custompost_args);
	}

}
