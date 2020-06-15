(function(window, document, undefined) {

    'use strict';


    /**
     * Selectors
     */
    var body = document.body,
        gallery = document.getElementById('js-gallery'),
        galleryWidth = gallery.offsetWidth,
        galleryHeight = gallery.offsetHeight,
        poster = document.getElementById('js-poster'),
        posterWidth = poster.offsetWidth,
        posterHeight = poster.offsetHeight,
        posterPadding = '50';

    /**
     * Prefixed requestAnimationFrame
     */
    var requestAnimationFrame = window.requestAnimationFrame
    || window.webkitRequestAnimationFrame
    || window.mozRequestAnimationFrame
    || window.msRequestAnimationFrame
    || function(callback) {
        return setTimeout(callback, 1000 / 60);
    };


    /**
     * Methods
     */
    var throttle = function(callback, limit) {
        var wait = false;
        return function() {
            if (!wait) {
                callback.call();
                wait = true;
                setTimeout(function() {
                    wait = false;
                }, limit);
            }
        };
    };

    var resizePoster = function() {

        // Define variable
        var scale;

        // Get values for poster dimensions
        scale = Math.min(
            galleryWidth / posterWidth,
            galleryHeight / posterHeight
        );


				// Scale Poster for larger viewports
				poster.style[Modernizr.prefixed('transform')] = 'translate(-50%, -50%) ' + 'scale(' + scale + ')';

        // Sync operation with browser
        requestAnimationFrame(resizePoster);
    };


    var onResize = throttle(function() {

        // Set Gallery width
        galleryWidth = gallery.offsetWidth - posterPadding;

        // Set Gallery height
        galleryHeight = gallery.offsetHeight - posterPadding;

        // Sync operation with browser
        requestAnimationFrame(resizePoster);

    }, 100);


    /**
     * Events/APIs/init
     */

    // Set Gallery width
    galleryWidth = gallery.offsetWidth - posterPadding;

    // Set Gallery height
    galleryHeight = gallery.offsetHeight - posterPadding;

    // Sync operation with browser
    requestAnimationFrame(resizePoster);

    // Listen for resize event
    window.addEventListener('resize', onResize, false);

})(window, document);