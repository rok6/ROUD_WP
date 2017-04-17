(function(window, $) {

	'use strict';

	var $wp_inline_edit = inlineEditPost.edit;

	inlineEditPost.edit = function( id ) {
		$wp_inline_edit.apply( this, arguments );

		var $post_id = 0;

		if( typeof( id ) === 'object' ) {
			$post_id = parseInt( this.getId( id ) );
		}

		if( $post_id > 0 ) {
			var $edit_row = $( '#edit-' + $post_id );
			var $post_row = $( '#post-' + $post_id );

			var $meta_desc = $( '.column-meta_description', $post_row ).html();
			$( ':input[name="meta_description"]', $edit_row ).val( $meta_desc );

			var $meta_robots = $('.column-meta_robots', $post_row).html();
			if( $meta_robots === 'index, follow' ) {
				$meta_robots = 1;
			}
			else {
				$meta_robots = 0;
			}
			$( ':input[name="meta_robots"]', $edit_row ).children().eq($meta_robots).attr('selected', true );
			console.log($( ':input[name="meta_robots"]', $edit_row ));
		}
	};

})(this, this.jQuery);
