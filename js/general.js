$(document).ready(function(){
	var urlPath = window.location.pathname,
    urlPathArray = urlPath.split('.'),
    tabId = urlPathArray[0].split('/').pop();
	$('#category, #tables, #tax, #items, #user, #order, #billing').removeClass('active');	
	$('#'+tabId).addClass('active');
	$('div[id^="expand"]').click(function(){
		$(this).next().show();
	})		
});