<?php

class WPRD_Editor
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;

		add_action('admin_enqueue_scripts', function($hook) {
			if( !('post.php' === $hook || 'post-new.php' === $hook) ) {
				return;
			}
			wp_enqueue_script( 'my_custom_script', get_template_directory_uri().'/assets/js/admin_mce_textEditor.js', false, null, true );
		});

		add_filter('mce_external_plugins', function($plugins) {
			//独自ボタン
			$plugins['custom_button_script'] = get_template_directory_uri() . '/assets/js/admin_mce_plugin.js';
			//テーブルプラグイン
			$plugins['table'] = '//cdn.tinymce.com/4/plugins/table/plugin.min.js';
			return $plugins;
		});

		$this->init();
	}

	private function init()
	{
		add_filter( 'tiny_mce_before_init', function($init) {
			$init['toolbar1'] = 'spellchecker,formatselect,bold,italic,underline,strikethrough,superscript,subscript,serif,link,unlink,table,fullscreen';
			$init['toolbar2'] = 'removeformat,undo,redo,blockquote,cite,attention,del,ins,indent,outdent,alignleft,aligncenter,alignright,hr';
			$init['toolbar3'] = 'pre,wp_code,wp_more,wp_page,charmap,pastetext,wp_help';

			$init['block_formats'] = "段落=p; 見出し2=h2; 見出し3=h3; 見出し4=h4; 見出し5=h5;";
			return $init;
		});

		add_filter( 'quicktags_settings', function($tags) {
			//"strong,em,link,block,del,ins,img,ul,ol,li,code,more,close,dfw"
			$tags['buttons'] = 'close,link,more,dfw';
			return $tags;
		});

	}

}
//
// access+() <Shift+Alt+()>
//
// meta+() <Ctrl+()>
//
// "{
// 	"Heading 1":"access1",
// 	"Heading 2":"access2",
// 	"Heading 3":"access3",
// 	"Heading 4":"access4",
// 	"Heading 5":"access5",
// 	"Heading 6":"access6",
// 	"Paragraph":"access7",
// 	"Blockquote":"accessQ",
// 	"Underline":"metaU",
// 	"Strikethrough":"accessD",
// 	"Bold":"metaB",
// 	"Italic":"metaI",
// 	"Code":"accessX",
// 	"Align center":"accessC",
// 	"Align right":"accessR",
// 	"Align left":"accessL",
// 	"Justify":"accessJ",
// 	"Cut":"metaX",
// 	"Copy":"metaC",
// 	"Paste":"metaV",
// 	"Select all":"metaA",
// 	"Undo":"metaZ",
// 	"Redo":"metaY",
// 	"Bullet list":"accessU",
// 	"Numbered list":"accessO",
// 	"Insert\/edit image":"accessM",
// 	"Insert\/edit link":"metaK",
// 	"Remove link":"accessS",
// 	"Toolbar Toggle":"accessZ",
// 	"Insert Read More tag":"accessT",
// 	"Insert Page Break tag":"accessP",
// 	"Distraction-free writing mode":"accessW",
// 	"Keyboard Shortcuts":"accessH"
// }"

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
