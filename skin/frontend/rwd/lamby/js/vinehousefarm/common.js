jQuery(function() {
	jQuery('.jsWhfFaq').on('click', '.whf-faq-title', function(e) {
		e.preventDefault();

		jQuery(this).closest('.whf-faq-row').toggleClass('is-open');
	});

	jQuery('.jsWhfContentTabs').on('click', '.whf-content-tabs-tablist li a', function(e) {
		e.preventDefault();

		var li = jQuery(this).closest('li');
		var index = li.index();
		li.closest('.jsWhfContentTabs').find('li').removeClass('is-visible');
		li.closest('.jsWhfContentTabs').find('.whf-content-tabs-tablist li').eq(index).addClass('is-visible');
		li.closest('.jsWhfContentTabs').find('.whf-content-tabs-contents li').eq(index).addClass('is-visible');
	});
});
