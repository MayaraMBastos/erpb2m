jQuery(document).ready(function(){
	$ = jQuery.noConflict();

	(function(){
		$('.woo-parcelas-com-e-sem-juros form h3').click(function(){
			$('.woo-parcelas-com-e-sem-juros form h3').removeClass('fs-active');
			$(this).addClass('fs-active');
		});

		$('.color-field').wpColorPicker();
	})();
});