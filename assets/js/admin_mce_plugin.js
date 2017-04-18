(function(window, $) {

	'use strict';

	tinymce.create('tinymce.plugins.MyButtons',
	{
		init : function(ed, url) {

			ed.addButton('serif', {
				text	: 'serif',
				title	: 'セリフ体 <span class="serif">',
				cmd		: 'serif' + '_cmd'
			});
			ed.addCommand('serif_cmd', function() {
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

		}

	});

	tinymce.PluginManager.add(
		'custom_button_script',
		tinymce.plugins.MyButtons
	);

})(this, this.jQuery);
