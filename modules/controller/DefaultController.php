<?php
namespace Roud\module\controller;

class DefaultController extends Controller
{

	public function __construct( $render_type )
	{
		$this->set( $this->get_post_type() );
		$this->render( $render_type );
	}

	/**
	 * @return $post_type_name
	 */
	private function get_post_type()
	{
		if( is_archive() ) {

			if( is_tax() || is_tag() || is_category() ) {
				/* taxonomy archive */
				$this->params['tax_query'] = [
					[
						'taxonomy'	=> get_queried_object()->taxonomy,
						'terms'			=> get_queried_object()->slug,
						'field'			=> 'slug',
						'operator'	=> 'and',
					]
				];
				return get_taxonomy($this->params['tax_query'][0]['taxonomy'])->object_type[0];
			}
			/* get_query_varで投稿数0の時にもpost_typeを返す */
			return get_query_var('post_type');
		}
		return get_post_type();
	}

}
