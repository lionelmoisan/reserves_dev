;(function($, window, document, undefined) {
	var $win = $(window);
	var $doc = $(document);

	$doc.ready(function() {
		var $boxes = $('.box');

		$('.box-trigger').on('click', function(event) {
			event.preventDefault();

			var $currentBox = $(this).closest('.box');

			$boxes.not($currentBox).find('.box-body').slideUp();
			
			$currentBox
				.find('.box-body')
					.slideDown();
		});
	});

})(jQuery, window, document);
