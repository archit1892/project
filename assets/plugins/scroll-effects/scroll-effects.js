/**
*http://www.jqueryscript.net/other/jQuery-Plugin-To-Determine-If-An-Element-Is-In-the-Viewport-Viewport-Checker.html
https://github.com/dirkgroenen/jQuery-viewport-checker/blob/master/demo/index.html
**/

jQuery(document).ready(function() {

    jQuery('.animate-scroll-bounceInLeft').addClass("hidden-scroll").viewportChecker({
        classToAdd: 'visible-scroll animated bounceInLeft',
        // Class to add to the elements when they are visible
        offset: 100
    });

    jQuery('.animate-scroll-fadeIn').addClass("hidden-scroll").viewportChecker({
        classToAdd: 'visible-scroll animated fadeIn',
        // Class to add to the elements when they are visible
        offset: 100
    });

    jQuery('.animate-scroll-zoomIn').addClass("hidden-scroll").viewportChecker({
        classToAdd: 'visible-scroll animated zoomIn',
        // Class to add to the elements when they are visible
        offset: 100
    });

    jQuery('.animate-scroll-bounceInRight').addClass("hidden-scroll").viewportChecker({
        classToAdd: 'visible-scroll animated bounceInRight',
        // Class to add to the elements when they are visible
        offset: 100
    });

    jQuery('.dummy').viewportChecker({
        callbackFunction: function(elem, action){
            setTimeout(function(){
                elem.html((action == "add") ? 'Callback with 500ms timeout: added class' : 'Callback with 500ms timeout: removed class');
            }, 1500);
        },
        scrollBox: ".scrollwrapper"
    });

    jQuery('.animate-scroll').each( function() {

        console.log(jQuery(this).attr('data-effect'));

        jQuery(this).addClass("hidden-scroll").viewportChecker({
            classToAdd: 'visible-scroll animated ' + jQuery(this).attr('data-effect'),
            offset: 100,
        });
    });



    jQuery('.animate-scroll-callback').addClass("hidden-scroll").viewportChecker({
        classToAdd: 'visible-scroll animated ' + jQuery(this).attr('data-effect'),
        // Class to add to the elements when they are visible
        offset: 100,
        /*
        callbackFunction: function(elem, action) {
            setTimeout(function() {
                elem.html((action == "add") ? 'Callback with 500ms timeout: added class' : 'Callback with 500ms timeout: removed class');
                alert('hi there');
            }, 500);
        },*/
    });

});
