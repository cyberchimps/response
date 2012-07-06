// temporary fix for oembed for Twitter
jQuery(document).ready(function($){
	setInterval( function() {
		$( '.twitter-tweet-rendered' ).removeAttr( 'style' );
	}, 100 );
});