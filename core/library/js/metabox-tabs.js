jQuery(document).ready(function($) {

 	
	// tab between them
	jQuery('.metabox-tabs li a').each(function(i) {
		var thisTab = jQuery(this).parent().attr('class').replace(/active /, '');

		if ( 'active' != jQuery(this).attr('class') )
			jQuery('div.' + thisTab).hide();
		
		jQuery('div.' + thisTab).addClass('tab-content');
 
		jQuery(this).click(function(){
			// hide all child content
			jQuery(this).parent().parent().parent().children('div').hide();
 
			// remove all active tabs
			jQuery(this).parent().parent('ul').find('li.active').removeClass('active');
 
			// show selected content
			jQuery(this).parent().parent().parent().find('div.'+thisTab).show();
			jQuery(this).parent().parent().parent().find('li.'+thisTab).addClass('active');
		});
	});

	jQuery('.heading').hide();
	jQuery('.metabox-tabs').show();

	$(".subsection-items").hide();
	$(".subsection > h4").click(function() {
		var $this = $(this);
		$this.find("span.minus").removeClass('minus');
		if($this.siblings('div').is(":visible")) {
			$this.siblings('div').fadeOut();
		} else {
			$this.siblings('div').fadeIn();
			$this.find("span").addClass('minus');
		}
	});

	// show by default
	
	$("#subsection-Page-Options > h4").click();
	var page_subsection_map = {
		page_slider: "subsection-Response-Slider-Options",
		callout_section: "subsection-Callout-Options",
	};
	$("#page_section_order").change(function(){
		var array = $(this).val().split(",");
		$.each(page_subsection_map, function(key, value) {
			if($.inArray(key, array) != -1) {
				$("#" + value).show();
			} else {
				$("#" + value).hide();
			}
		});
	}).change();


	// image_select
	$(".image_select").each(function(){
		$(this).find("img").click(function(){
			if($(this).hasClass('selected')) return;
			$(this).siblings("img").removeClass('selected');
			$(this).addClass('selected');
			$(this).siblings("input").val($(this).data("key"));
		});
    if($(this).find("img.selected").length) {
			$(this).find("input").val($(this).find("img.selected").data("key"));
    }
	});

	 /*
      Add toggle switch after each checkbox.  If checked, then toggle the switch.
    */
     $('.checkbox').after(function(){
       if ($(this).is(":checked")) {
         return "<a href='#' class='toggle checked' ref='"+$(this).attr("id")+"'></a>";
       }else{
         return "<a href='#' class='toggle' ref='"+$(this).attr("id")+"'></a>";
       }
       
     });
     
     /*
      When the toggle switch is clicked, check off / de-select the associated checkbox
     */
    $('.toggle').click(function(e) {
       var checkboxID = $(this).attr("ref");
       var checkbox = $('#'+checkboxID);

       if (checkbox.is(":checked")) {
         checkbox.removeAttr("checked").change();
       }else{
         checkbox.attr("checked","checked").change();
       }
       $(this).toggleClass("checked");

       e.preventDefault();

    });

    /*
      For demo purposes only....shows/hides checkboxes.
    */
    $('#showCheckboxes').click(function(e) {
     $('.checkbox').toggle()
     e.preventDefault();
    });

    $('#checkbox-extra_callout_options').change(function(){
	    var items = $("tr.callout_image, tr.custom_callout_color, tr.custom_callout_title_color, tr.custom_callout_text_color, tr.custom_callout_button_color, tr.custom_callout_button_text_color");
		if($(this).is(':checked')) {
			items.show();
		} else {
			items.hide();
		}
		$("#checkbox-disable_callout_button").trigger("change");
    }).trigger('change');

    $('#checkbox-disable_callout_button').change(function(){
	    var items = $("tr.callout_button_text, tr.callout_url");
		if($(this).is(':checked')) {
			items.show();
		} else {
			items.hide();
		}
    }).trigger('change');
});
