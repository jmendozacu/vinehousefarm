(function ($) {
	// custom css expression for a case-insensitive contains()
	jQuery.expr[':'].Contains = function(a,i,m){
		return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
	};

	function listFilter(header, list) { // header is any element, list is an unordered list
		// create and add the filter form to the header
		var form = $(".filterform");
		var input = $(".filterinput");

		$(input)
				.change( function () {
					var filter = $(this).val();
					if(filter) {
						// this finds all links in a list that contain the input,
						// and hide the ones not containing the input while showing the ones that do
						$(list).find("div.bird-box-name:not(:Contains(" + filter + "))").parent().parent().slideUp();
						$(list).find("div.bird-box-name:Contains(" + filter + ")").parent().parent().slideDown();
					} else {
						$(list).find("li").slideDown();
					}
					return false;
				})
				.keyup( function () {
					// fire the above change event after every letter
					$(this).change();
				});
	}


	//ondomready
	$(function () {
		listFilter($("#header-bird"), $("#list-bird"));
	});
}(jQuery));
