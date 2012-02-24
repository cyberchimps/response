jQuery(document).ready(function($) {
	function if_check_slider_value(value) {
		var slider_value = $("select[name=\'page_slider_type\']").val();

		if ( slider_value == "0" ) {
			$(".slider_blog_category").hide();
			$(".slider_category").fadeIn();
		} else if ( slider_value == "1" ){
			$(".slider_category").hide();
			$(".slider_blog_category").fadeIn();
		}

		return false;
	}

	if_check_slider_value();

	$("select[name=\'page_slider_type\']").change(function() {
		if_check_slider_value();
	});
});

jQuery(document).ready(function($) {
	function if_check_slider_value(value) {
		var slider_value = $("select[name=\'page_nivoslider_type\']").val();

		if ( slider_value == "0" ) {
			$(".nivoslider_blog_category").hide();
			$(".nivoslider_category").fadeIn();
		} else if ( slider_value == "1" ){
			$(".nivoslider_category").hide();
			$(".nivoslider_blog_category").fadeIn();
		}

		return false;
	}

	if_check_slider_value();

	$("select[name=\'page_nivoslider_type\']").change(function() {
		if_check_slider_value();
	});
});