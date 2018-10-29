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
		$(".btn-ae-nav").click(function() {
			$('.ae-nav').addClass('active');
		});

		$(".close-ae-nav span").click(function() {
			$('.ae-nav').removeClass('active');
		});

		// Nav Mobile
		$("#btn-nav-mobile").click(function() {
			$('#nav-mobile').addClass('active');
		});
		$(".close-nav-mobile span, .link.open-modal").click(function() {
			$('#nav-mobile').removeClass('active');
		});

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
		$(".btn-postularme").click(function() {
			$('.modal-vacante').hide();
			/* Obtener el nombre de la vacante para formulario de postulación */
			var title = $(this).attr('for');
			$('#vacanteName input').val(title);			
			//$('input[name="postulante-email"]').attr("disabled", true);			
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