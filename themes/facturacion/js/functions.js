var $=jQuery.noConflict();
 
(function($){
	"use strict";
	$(function(){
 
		/*------------------------------------*\
			#GLOBAL
		\*------------------------------------*/

		$(document).ready(function() {
			footerBottom();
		});
 
		$(window).on('resize', function(){
			footerBottom();
		});

		$(window).scroll(function() {
		    if ($(this).scrollTop() > 600) { // this refers to window
		        $('#return-page').removeClass('hide');
		    } else {
		    	$('#return-page').addClass('hide');
		    }
		});

		//Scroll #retunt-page
		$("#return-page").click(function() {
			console.log('CLICK');
			$('html, body').animate({		
				scrollTop: $('#navAE').offset().top - 50
			}, 500);
		});

		// Nav Alto Empleo

		// Modal
		$(".open-modal").click(function() {
			var idModal = $(this).attr('id');
			$('#modal-' + idModal).show();
			$('body').addClass('overflow-hide');
		});
		$(".close-modal, .exit-modal").click(function() {
			$('.modal').hide();
			$('body').removeClass('overflow-hide');
		});

	});
})(jQuery);
 
/**
 * Fija el footer abajo
 */
function footerBottom(){
	var alturaFooter = getFooterHeight();
	var alturaHeader = getHeaderHeight();
	$('.main-body').css({
		'padding-bottom': alturaFooter,
		'min-height': 'calc( 100vh - ' + alturaHeader + 'px)' 
	});
}
function getHeaderHeight(){
	return $('.js-header').outerHeight();
}// getHeaderHeight
function getFooterHeight(){
	return $('footer').outerHeight();
}// getFooterHeight