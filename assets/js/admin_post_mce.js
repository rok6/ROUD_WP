(function(window, $) {

	'use strict';

	tinymce.create('tinymce.plugins.wprdCustomEdit',
	{
		init : function(ed, url) {

			ed.addButton('dlist-dl', {
				text	: 'dl',
				title	: '定義リスト <dl>',
				cmd		: 'dlist-dl' + '_cmd'
			});
			ed.addCommand('dlist-dl' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<dl>' + selected_text + '</dl>';
				ed.execCommand('mceInsertContent', 0, return_text);
			});
			ed.addButton('dlist-dt', {
				text	: 'dt',
				title	: '定義リスト <dt>',
				cmd		: 'dlist-dt' + '_cmd'
			});
			ed.addCommand('dlist-dt' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<dt>' + selected_text + '</dt>';
				ed.execCommand('mceInsertContent', 0, return_text);
			});
			ed.addButton('dlist-dd', {
				text	: 'dd',
				title	: '定義リスト <dd>',
				cmd		: 'dlist-dd' + '_cmd'
			});
			ed.addCommand('dlist-dd' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<dd>' + selected_text + '</dd>';
				ed.execCommand('mceInsertContent', 0, return_text);
			});


			ed.addButton('serif', {
				text	: 'serif',
				title	: 'セリフ体 <span class="serif">',
				cmd		: 'serif' + '_cmd'
			});
			ed.addCommand('serif' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<span class="serif">' + selected_text + '</span>';
				ed.execCommand('mceInsertContent', 0, return_text);
			});

			ed.addButton('attention', {
				text	: 'attention',
				title	: '注釈 <p class="attention">',
				cmd		: 'attention' + '_cmd'
			});
			ed.addCommand('attention' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<p class="attention">' + selected_text + '</p>';
				ed.execCommand('mceInsertContent', 0, return_text);
			});

			ed.addButton('del', {
				text	: 'del',
				title	: '取り消した文字列 <del>',
				cmd		: 'del' + '_cmd'
			});
			ed.addCommand('del' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<del>' + selected_text + '</del>';
				ed.execCommand('mceInsertContent', 0, return_text);
			});

			ed.addButton('ins', {
				text	: 'ins',
				title	: '追記した文字列 <ins>',
				cmd		: 'ins' + '_cmd'
			});
			ed.addCommand('ins' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<ins>' + selected_text + '</ins>';
				ed.execCommand('mceInsertContent', 0, return_text);
			});

			ed.addButton('cite', {
				text	: 'cite',
				title	: '引用元 <cite>',
				cmd		: 'cite' + '_cmd'
			});
			ed.addCommand('cite' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<cite>' + selected_text + '</cite>';
				ed.execCommand('mceInsertContent', 0, return_text);
			});


			ed.addButton('pre', {
				text	: 'pre',
				title	: 'ソースコード <pre>',
				cmd		: 'pre' + '_cmd'
			});
			ed.addCommand('pre' + '_cmd', function() {
				var selected_text = ed.selection.getContent();
				var return_text = '<pre>\n' + selected_text + '\n</pre>\n';
				ed.execCommand('mceInsertContent', 0, return_text);
			});

			/**
			 * TAB indent
			 *----------------------------------------------------------*/
			//
			// ed.on('keyDown', function(e) {
			// 	if( e.keyCode == 9 && !e.altKey && !e.ctrlKey ) {
			// 		// Indent (Tab) or Outdent (Shift+Tab)
			// 		if( e.shiftKey ) {
			// 			ed.execCommand('Outdent');
			// 		}
			// 		else {
			// 			var sel = ed.selection.getSel();
			// 			if( sel ) {
			// 				var $tab = "<span class=\"tab-indent\">\t</span>";
			// 				ed.execCommand('mceInsertContent', 0, $tab);
			// 			}
			// 			//ed.execCommand('Indent');
			// 		}
			// 		// And prevent the default event handler running
			// 		e.preventDefault();
			// 		return false;
			// 	}
			// });

		}// if

	});


	tinymce.PluginManager.add('custom_my_mce', tinymce.plugins.wprdCustomEdit);

})(this, this.jQuery);
