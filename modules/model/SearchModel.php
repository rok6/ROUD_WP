<?php
namespace Roud\module\model;

class SearchModel
{
	protected $default = [];

	public function __construct()
	{
		$post_types = get_post_types([
			'public'	=> true,
		]);
		unset($post_types['attachment']);

		$front_page_id = get_option( 'page_on_front' );

		$this->default = [
			'post_status'	=> 'publish',
			'orderby'			=> 'modified',
			'order'				=> 'desc',
			's'						=> get_query_var('s'),
			'posts_per_page' => get_option('posts_per_page'),
			'paged'					 => get_query_var('paged'),
			'post_type'			 => $post_types,
			'post__not_in'	 => [$front_page_id],
		];
	}

	public function get( array $args = [] ) {
		return get_posts( $args += $this->default );
	}
}
