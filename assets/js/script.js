jQuery(document).ready(function(){
 jQuery('.listings-phone-email').click(function () {

        jQuery(this).parent().find('.fa').hide();
        jQuery(this).parent().find('.listings-phone-email-details').slideToggle('slow');

    });

    jQuery(".cb-enable").click(function () {
        var parent = jQuery(this).parents('.switch');
        jQuery('.cb-disable', parent).removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('.checkbox', parent).attr('checked', true);
    });

    jQuery(".cb-disable").click(function () {
        var parent = jQuery(this).parents('.switch');
        jQuery('.cb-enable', parent).removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('.checkbox', parent).attr('checked', false);
    });


    jQuery("#carousel-example-generic-mobile").swipe({
        swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
            if (direction == 'left')
                jQuery(this).carousel('next');
            if (direction == 'right')
                jQuery(this).carousel('prev');
        },
        allowPageScroll: "vertical"
    });

    /**
    jQuery('.jscroll').jscroll({
        debug: true,
        loadingHtml: '<img src="loading.gif" alt="Loading" /> Loading...',
        padding: 20,
        nextSelector: 'a.jscroll-next:last',
        contentSelector: 'li'
    });**/


   jQuery('.animate-scroll-fadeIn').addClass("hidden-scroll").viewportChecker({
        classToAdd: 'visible-scroll animated fadeIn',
        classToRemove: 'hidden-scroll',
        offset: 100,
    });

    
});

   
