<?php

class RoudCustomPost
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;
	}

	public function init()
	{
		$this->news();
		$this->illurweb();
	}

	private function news()
  {
    $cpt_args = array(
      'label'         => 'NEWS',
      'public'        => true,
      'show_in_rest'  => true,
      'menu_position' => 5,
    );
    register_post_type('news', $cpt_args);
  }

	private function illurweb()
	{
		$cpt_args = array(
      'label'         => __('ILLURWEB', self::$domain),
      'public'        => true,
      'has_archive'   => true,
      'hierarchical'  => true,
      'show_in_rest'	=> true,
      'menu_position'	=> 5,
			'support'				=> array(
				'page-attributes',
			),
    );
    register_post_type('illurweb', $cpt_args);
    $tax_args = array(
      'label'         => __('チャプター', self::$domain),
      'hierarchical'	=> true,
      'show_in_rest'	=> false,
    );
    register_taxonomy('illurweb_chapter', 'illurweb', $tax_args);
	}

}
