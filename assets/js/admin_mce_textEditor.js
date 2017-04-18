(function(window, $) {

	'use strict';

	var prefix = 'rd_';

	quickedit();

	function quickedit() {

		//id, display, arg1, arg2, access_key, title, priority, instance

		QTags.addButton('h2', 'h2', '<h2>', '</h2>\n');
		QTags.addButton('h3', 'h3', '<h3>', '</h3>\n');
		QTags.addButton('h4', 'h4', '<h4>', '</h4>\n');
		QTags.addButton('h5', 'h5', '<h5>', '</h5>\n');
		QTags.addButton('h6', 'h6', '<h6>', '</h6>\n');

		QTags.addButton('p', '<p>段落', '<p>\n', '\n</p>\n');

		QTags.addButton(
			prefix + 'strong',
			'<strong>強調',
			'<strong>', '</strong>'
		);
		QTags.addButton(
			prefix + 'em',
			'<em>斜体',
			'<em>', '</em>'
		);
		QTags.addButton(
			prefix + 'serif',
			'<span.serif>セリフ体',
			'<span class="serif">', '</span>'
		);

		QTags.addButton(
			prefix + 'attention',
			'<p.attention>注釈',
			'<p class="attention">\n', '\n</p>\n'
		);
		QTags.addButton(
			prefix + 'del',
			'<del>取り消し',
			'<del>', '</del>'
		);
		QTags.addButton(
			prefix + 'ins',
			'<ins>追記',
			'<ins datetime="' + get_datetime() + '">', '</ins>'
		);
		QTags.addButton(
			prefix + 'cite',
			'<cite>引用元',
			'<cite>', '</cite>\n'
		);

		QTags.addButton(
			prefix + 'blockquote',
			'<blockquote>引用',
			'\n\n<blockquote>\n', '\n</blockquote>\n\n'
		);
		QTags.addButton(
			prefix + 'hr',
			'<hr>ライン',
			'\n<hr />\n\n', ''
		);

		QTags.addButton(
			prefix + 'ul',
			'<ul>番号なしリスト',
			'<ul>\n', '</ul>\n'
		);
		QTags.addButton(
			prefix + 'ol',
			'<ol>番号付きリスト',
			'<ol>\n', '</ol>\n'
		);
		QTags.addButton(
			prefix + 'li',
			'li',
			'<li>', '</li>\n'
		);

		QTags.addButton(
			prefix + 'dl',
			'<dl>定義リスト',
			'<dl>\n', '</dl>\n'
		);
		QTags.addButton(
			prefix + 'dt',
			'dt',
			'<dt>', '</dt>\n'
		);
		QTags.addButton(
			prefix + 'dd',
			'dd',
			'<dd>', '</dd>\n'
		);


		QTags.addButton(
			prefix + 'tabel',
			'table',
			'<table>\n', '\n</table>\n\n'
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

		QTags.addButton(
			prefix + 'pre',
			'pre',
			'<pre>\n', '\n</pre>\n\n'
		);
		QTags.addButton(
			prefix + 'code',
			'code',
			'<code>', '</code>'
		);
	}

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
