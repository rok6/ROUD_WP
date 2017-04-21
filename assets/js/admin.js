(function(window, $) {

	'use strict';

	$('#category-pop').find('li').each(function() {
		var $input = $(this).find('input');
		$input.attr('type', 'radio');
		console.log($input);
	});

})(this, this.jQuery);
