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

		global $wp_query;

		$this->view_vars['found_posts'] = $wp_query->found_posts;

		/* 投稿件数が0の時 */
		if( !$this->view_vars['found_posts'] ) {
			$render_type = 'none';
		}

		if( $file = $this->request_views( $render_type ) ) {
			extract($this->view_vars);
			require( $file );
		}

	}

	/**
	* set - post_data
	*=====================================================*/
	protected function set( $name )
	{
		$name = ( (string)$name !== '' ) ? $name : 'default';

		$this->$name = request_module( 'model', false, [
			'filename'	=> $name,
		]);

		$this->post_type = $name;

		if( !is_search() ) {
			$this->params['post_type'] = $name;
		}

		$this->view_vars['post'] = $this->$name->get($this->params);
	}


	private function request_views( $render_type = '' )
	{
		$module_path = ROUD_MDLS_PATH . '/view/';
		$filename = ( $render_type !== '' ) ?
			$this->post_type . '-' . $render_type
		: $this->post_type;

		/**
		 * 指定ファイル名　→　デフォルト-レンダータイプ　→　デフォルト　と順に検索
		 */
		if( is_file( $file = $module_path . $filename . '.php' )
						||
				( $render_type !== '' && is_file( $file = $module_path . $this->post_type . '.php' ) )
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
