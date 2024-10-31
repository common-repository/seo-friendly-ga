/* SEO Friendly GA */

jQuery(document).ready(function($) {
	
	$('.default-hidden').hide();
	
	$('h2').click(function() { $(this).next().slideToggle(300); });
	
	$('.sfga-toggle-all a').click(function(e) { e.preventDefault(); $('.toggle').slideToggle(300); });
	
	$('.sfga-toggle').click(function(e) { e.preventDefault(); $('.toggle').slideUp(300); $('#sfga-panel-'+ $(this).data('target') +' .toggle').slideDown(300); });
	
	$('.sfga-reset-options').click(function() { return confirm(sfga_google_analytics.confirm_message); });
	
	$('.sfga-select-method:nth-child(1), .sfga-select-method:nth-child(3)').click(function() { $('.sfga-info-universal').slideDown(300); });
	
	$('.sfga-select-method:nth-child(2)').click(function() { $('.sfga-info-universal').slideUp(300); });
	
});