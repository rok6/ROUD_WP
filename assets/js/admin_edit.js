(function(window, $) {

	'use strict';

	var _EDIT = inlineEditPost.edit;

	inlineEditPost.edit = function(id) {

		_EDIT.apply( this, arguments );

		if( typeof( id ) === 'object' ) {
			id = this.getId( id );
		}

		if( id > 0 ) {
			var $edit_row = $( '#edit-' + id );
			var $post_row = $( '#post-' + id );
			var $inline_row = $( '#inline_' + id );

			// カテゴリー
			$('.post_category', $inline_row).each(function() {
				var $this = $(this),
						tax_name,
						term_ids = $this.text();
				if( term_ids ) {
					tax_name = $this.attr('id').replace('_' + id, '');
					// カテゴリーのラジオボタンにチェック
					$('ul.'+tax_name+'-checklist :radio', $edit_row).val(term_ids.split(','));
				}
			});

			// Meta description - カスタムフィールド
			var $meta_desc = $( '.column-meta_description', $post_row ).html();
			$( ':input[name="meta_description"]', $edit_row ).val( $meta_desc );

			// Meta robots - カスタムフィールド
			var $meta_robots = $('.column-meta_robots', $post_row).html();
			$meta_robots = ($meta_robots === 'index, follow') ? 1 : 0;
			$( ':input[name="meta_robots"]', $edit_row ).children().eq($meta_robots).attr('selected', true );
		}

	};

})(this, this.jQuery);
