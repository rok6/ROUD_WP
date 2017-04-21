(function(window, $) {

	'use strict';

	var $pop_list = $('#category-pop').find('li');

	$pop_list.each(function() {
		var $input = $(this).find('input');
		$input.attr('type', 'radio');

		$input.on('change', function() {
			if( this.checked ) {
				$pop_list.find('input').attr('checked', false);
				$(this).attr('checked', true);
			}
		});
	});

})(this, this.jQuery);
