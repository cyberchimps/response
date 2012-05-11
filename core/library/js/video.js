jQuery(document).ready(function($) {
	$("iframe[src^='http://www.youtube.com']").wrap("<div class='flex-video' />");
	$("iframe[src^='http://player.vimeo.com']").wrap("<div class='flex-video' />");
});