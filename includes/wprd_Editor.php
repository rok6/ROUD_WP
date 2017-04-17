<?php

class WPRD_Editor
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;

		add_action( 'admin_enqueue_scripts', function($hook) {
			if( !('post.php' === $hook || 'post-new.php' === $hook) ) {
				return;
			}
			wp_enqueue_script( 'my_custom_script', get_template_directory_uri().'/assets/js/admin_mce_editor.js', false, null, true );
		});

		$this->init();
	}

	private function init()
	{
		add_filter( 'mce_buttons', function($buttons) {

			$order = [
				'formatselect',
				'styleselect', // new Styles
				'link', 'unlink',
				'wp_more',
				'spellchecker',
				'dfw',
				'fullscreen',
			];
			return $order;
		}, 1000 );

		add_filter( 'mce_buttons_2', function($buttons) {
			$order = [
				'undo', 'redo',
				'removeformat',
				'pastetext',
				'indent', 'outdent',
				'alignleft', 'aligncenter', 'alignright',
				'hr',
				'charmap',
			];

			if ( ! wp_is_mobile() ) {
				$order[] = 'wp_help';
			}
			return $order;
		}, 1000 );

		add_filter( 'tiny_mce_before_init', function($init) {
			return $init;
		});

		add_filter( 'quicktags_settings', function($tags) {
			//string(61) "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,dfw"
			// $buttons = [];
			// $tags['buttons'] = implode(',', $buttons);
			$tags['buttons'] = 'close,link,more,dwf';

			return $tags;
		});

	}

}

//
// array(15) {
// 	[0]=>
// 	string(12) "formatselect"
// 	[1]=>
// 	string(4) "bold"
// 	[2]=>
// 	string(6) "italic"
// 	[3]=>
// 	string(7) "bullist"
// 	[4]=>
// 	string(7) "numlist"
// 	[5]=>
// 	string(10) "blockquote"
// 	[6]=>
// 	string(9) "alignleft"
// 	[7]=>
// 	string(11) "aligncenter"
// 	[8]=>
// 	string(10) "alignright"
// 	[9]=>
// 	string(4) "link"
// 	[10]=>
// 	string(6) "unlink"
// 	[11]=>
// 	string(7) "wp_more"
// 	[12]=>
// 	string(12) "spellchecker"
// 	[13]=>
// 	string(3) "dfw"
// 	[14]=>
// 	string(6) "wp_adv"
// }
// array(11) {
// 	[0]=>
// 	string(13) "strikethrough"
// 	[1]=>
// 	string(2) "hr"
// 	[2]=>
// 	string(9) "forecolor"
// 	[3]=>
// 	string(9) "pastetext"
// 	[4]=>
// 	string(12) "removeformat"
// 	[5]=>
// 	string(7) "charmap"
// 	[6]=>
// 	string(7) "outdent"
// 	[7]=>
// 	string(6) "indent"
// 	[8]=>
// 	string(4) "undo"
// 	[9]=>
// 	string(4) "redo"
// 	[10]=>
// 	string(7) "wp_help"
// }
