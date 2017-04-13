<?php
namespace Roud\module\controller;

class Controller
{
	protected $view_vars	= [];
	protected $params			= [];
	protected $post_type	= '';

	/**
	 * render
	 *=====================================================*/
	protected function render( $render_type = '' ) {

		if( $file = $this->valid_views( $render_type ) ) {
			extract($this->view_vars);
			require( $file );
		}

	}

	/**
	* set - post_data
	*=====================================================*/
	protected function set( $name )
	{
		$this->$name = request_module( 'model', false, array(
			'filename'	=> $name,
		));

		$this->post_type = $name;

		if( !is_search() ) {
			//検索以外ではポストタイプをセット
			$this->params['post_type'] = $name;
			$this->view_vars['post_label'] = get_post_type_object($name)->label;
		}

		if( is_category() || is_tag() || is_tax() ) {
			$this->view_vars['post_label'] = get_queried_object()->slug;
		}

		$this->view_vars['post'] = $this->$name->get($this->params);
	}

	private function valid_views( $render_type = '' )
	{
		$module_path = ROUD_MDLS_PATH . '/view/';
		$filename = ( $render_type !== '' ) ?
			$this->post_type . '-' . $render_type
		: $this->post_type;

		if( is_file( $file = $module_path . $filename . '.php' )
						||
				( $render_type !== '' && is_file( $file = $module_path . $this->params['post_type'] . '.php' ) )
						||
				( $render_type !== '' && is_file( $file = $module_path . 'default-' . $render_type . '.php' ) )
						||
				( is_file( $file = $module_path . 'default.php' ) )
		) {
			return $file;
		}
		return false;
	}

}
