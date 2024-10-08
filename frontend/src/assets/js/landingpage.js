//window.addEventListener("load", windowLoad);

$(document).ready(function(){

	$('.lesmer').click(function(event){
		$('.lesmer').hide();
		$('.registrert').show();
	});

	$('.header-menu__icon').click(function(event){
		$(this).toggleClass('active');
		$('.header-menu').toggleClass('active');

		if ($(this).hasClass('active')){
			$('body').data('scroll',$(window).scrollTop());
		}

		$('body').toggleClass('lock');

		if (!$(this).hasClass('active')){
			$('body,html').scrollTop(parseInt($('body').data('scroll')));
		}
	});



	// Close the menu when a menu link is clicked
    $('.menu__link').click(function() {
        if ($('.header-menu').hasClass('active')) {
            $('.header-menu').removeClass('active');
            $('.header-menu__icon').removeClass('active');
            $('body').removeClass('lock');
        }
    });

    // Handle search form submission
    $('.form__search').submit(function(event) {
        if ($('.header-menu').hasClass('active')) {
            $('.header-menu').removeClass('active');
            $('.header-menu__icon').removeClass('active');
            $('body').removeClass('lock');
        }
    });

});



function ibg(){
	$.each($('.ibg'), function(index, val) {
	if($(this).find('img').length>0){
		$(this).css('background-image','url("'+$(this).find('img').attr('src')+'")');
	}
	});
}

ibg();



//adaptive functions
$(window).resize(function(event){
   adaptive_function();
});

function adaptive_header(w,h){
	var headerMenu=$('.header-menu');
	var headerSearch=$('.form__search');
	var headerMenu1=$('.menu__list');
	var headerTheme = $('.page__action');
	var headerTheme2 = $('.page__user');


	if(w<1180) {
		if(!headerMenu1.hasClass('done')){
			headerMenu1.addClass('done').appendTo(headerMenu);
		
		}
	} else {
		if(headerMenu1.hasClass('done')){
			headerMenu1.removeClass('done').appendTo($('.menu__body'));
		}
	}

	if(w<1180) {
		if(!headerSearch.hasClass('done')){
			headerSearch.addClass('done').appendTo(headerMenu);
		
		}
	} else {
		if(headerSearch.hasClass('done')){
			headerSearch.removeClass('done').appendTo($('.menu__body'));
		}
	}

	if(w<1180) {
		if(!headerTheme.hasClass('done')){
			headerTheme.addClass('done');
			headerTheme2.addClass('done');
		
		}
	} else {
		if(headerTheme.hasClass('done')){
			headerTheme.removeClass('done').appendTo($('.menu__body'));
			headerTheme2.removeClass('done').appendTo($('.menu__body'));
		}
	}
}

function adaptive_function(){
   var w=$(window).outerWidth();
   var h= $(window).outerHeight();
 
   adaptive_header(w,h);
}
adaptive_function();