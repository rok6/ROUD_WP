(function(window, $) {

	'use strict';

	var prefix = 'rd_';

	QTags.addButton('h2', 'h2', '<h2>', '</h2>\n');
	QTags.addButton('h3', 'h3', '<h3>', '</h3>\n');
	QTags.addButton('h4', 'h4', '<h4>', '</h4>\n');
	QTags.addButton('h5', 'h5', '<h5>', '</h5>\n');
	QTags.addButton('h6', 'h6', '<h6>', '</h6>\n');

	QTags.addButton('p', '段落', '<p>\n', '\n</p>\n');

	QTags.addButton(
		prefix + 'strong',
		'強調',
		'<strong>', '</strong>'
	);
	QTags.addButton(
		prefix + 'em',
		'斜体',
		'<em>', '</em>'
	);
	QTags.addButton(
		prefix + 'del',
		'取り消し',
		'<em>', '</em>'
	);
	QTags.addButton(
		prefix + 'ins',
		'追記',
		'<ins datetime="' + get_datetime() + '">', '</ins>'
	);

	QTags.addButton(
		prefix + 'cite',
		'引用元',
		'<cite>', '</cite>\n'
	);
	QTags.addButton(
		prefix + 'attention',
		'注釈',
		'<p class="attention">\n', '\n</p>\n'
	);

	QTags.addButton(
		prefix + 'blockquote',
		'引用',
		'<blockquote>\n', '\n</blockquote>\n'
	);

	QTags.addButton(
		prefix + 'ul',
		'<ul>番号なしリスト',
		'<ul>\n', '\n</ul>\n'
	);
	QTags.addButton(
		prefix + 'ol',
		'<ol>番号付きリスト',
		'<ol>\n', '\n</ol>\n'
	);
	QTags.addButton(
		prefix + 'li',
		'li',
		'<li>', '</li>\n'
	);

	QTags.addButton(
		prefix + 'dl',
		'<dl>定義リスト',
		'<dl>\n', '\n</dl>\n'
	);
	QTags.addButton(
		prefix + 'dt',
		'dt',
		'<li>', '</li>\n'
	);
	QTags.addButton(
		prefix + 'dd',
		'dd',
		'<dd>', '</dd>\n'
	);


	QTags.addButton(
		prefix + 'tabel',
		'table',
		'<table>\n', '\n</table>\n'
	);
	QTags.addButton(
		prefix + 'tr',
		'tr',
		'<tr>\n', '\n</tr>'
	);
	QTags.addButton(
		prefix + 'th',
		'th',
		'<th>', '</th>'
	);
	QTags.addButton(
		prefix + 'td',
		'td',
		'<td>', '</td>'
	);



	// 'strong', //強調
	// 'em', //斜体
	// 'serif', //セリフ体
	// 'del', //削除
	// 'ins', // 追記
	//
	// 'attention', //注釈
	// '',
	// 'link',
	// 'hr',
	// 'dfw', //14
	//
	// 'p', //段落
	// 'ul', //
	// 'ol', //
	// 'dl', //
	//
	// 'table', //
	// 'tr', //
	// 'th', //
	// 'td', //

	function get_datetime() {
		var date = new Date(),
				yy	 = date.getFullYear(),
				mm	 = ('0' + (date.getMonth() + 1)).slice(-2),
				dd	 = ('0' + date.getDate()).slice(-2),
				hh	 = ('0' + date.getHours()).slice(-2),
				ii	 = ('0' + date.getMinutes()).slice(-2),
				ss	 = ('0' + date.getSeconds()).slice(-2);

		return yy +'-'+ mm +'-'+ dd +'T'+ hh +':'+ ii +':'+ ss +'+09:00';
	}

})(this, this.jQuery);
