function aliaCoreReadyFn (jQuery) {
	"use strict";

	/* --------
	start open stories modal
	------------------------------------------- */
	jQuery(document).on("click", ".story_hotlink", function (e) {
		storyModalResizeToFit();

		var postid = jQuery(this).attr('data-postid');
		var authorid = jQuery(this).attr('data-author');

		jQuery('#ajax_modal_story').html('<div class="story_modal_loader"><div class="alia_spinner"><div class="alia-double-bounce1"></div><div class="alia-double-bounce2"></div></div></div>');
		jQuery('#ajax_modal_story').modal();

		jQuery.ajax({
			url: alia_core_vars.ajax_load_story,
			type: 'POST',
			data: "postid=" + postid + "&authorid=" + authorid,
			success: function (html) {
				jQuery("#ajax_modal_story").html(html).prepend('<div class="story_modal_overlay_helper"></div><div class="story_rotate_loader story_modal_loader"><div class="alia_spinner"><div class="alia-double-bounce1"></div><div class="alia-double-bounce2"></div></div></div>');
			}
		});
		e.preventDefault();
	});

	/* call storyModalResizeToFit function on dom ready and resize */
	htmlCheckWindowHeight();
	jQuery(window).on('resize', function () {
		htmlCheckWindowHeight();

		storyModalResizeToFit();
	});

	/* --------
	end open stories modal
	------------------------------------------- */

	/* --------
	start stories rotate
	------------------------------------------- */
	jQuery(document).on('click', ".story_modal_window_next", function (e) {
		storyRotateNext(e);
	});

	jQuery(document).on('click', ".story_modal_window_prev, .story_modal_window_current", function (e) {
		storyRotatePrev(e);
	});

	jQuery(document).on('click', ".story_meta, .story_item_author_avatar", function (e) {
		e.preventDefault();
	});

	var touchStartX;
	var touchStartY;

	jQuery(document).on('touchstart', ".ajax_modal .story_modal_window", function (e) {
		touchStartX = e.originalEvent.touches[0].clientX;
		touchStartY = e.originalEvent.touches[0].clientY;
	});

	jQuery(document).on('touchmove', ".ajax_modal .story_modal_window", function (e) {
		var touchEndX = e.originalEvent.changedTouches[0].clientX;
		var touchEndY = e.originalEvent.changedTouches[0].clientY;
		var touchDif;
		var blurAmount;

		touchDif = touchEndX - touchStartX;
		if (touchDif < 0) {
			touchDif = touchDif * -1;
		}

		if (blurAmount < 2) {
			blurAmount = touchDif * 2 / 60;
		} else {
			blurAmount = 2;
		}

		blurAmount = 'blur(' + parseInt(blurAmount) + 'px)';
		jQuery('.story_modal_window_current').css({'filter'				 : blurAmount,
																								'-webkit-filter' : blurAmount,
																								'-moz-filter'		: blurAmount,
																								'-o-filter'			: blurAmount,
																								'-ms-filter'		 : blurAmount});
	});

	jQuery(document).on('touchend', ".ajax_modal .story_modal_window, .ajax_modal .story_modal_window .story_black_overlay", function (e) {
		if (e.target !== this) {
			return;
		}
		var touchEndX = e.originalEvent.changedTouches[0].clientX;
		var touchEndY = e.originalEvent.changedTouches[0].clientY;

		if (jQuery('body.rtl').length) {
			if (touchStartX > touchEndX + 60 && (touchStartY < touchEndY + 130 && touchStartY > touchEndY - 130)) {
				storyRotateNext(e);
			} else if (
				(touchStartX < touchEndX - 60 && (touchStartY < touchEndY + 130 && touchStartY > touchEndY - 130)) ||
				(touchStartX < touchEndX + 30 && touchStartX > touchEndX - 30 && touchStartY < touchEndY + 30 && touchStartY > touchEndY - 30)
			) {
				storyRotatePrev(e);
			} else {
				jQuery('.story_modal_window').css({'filter'				 : "initial",
																					'-webkit-filter' : "initial",
																					'-moz-filter'		: "initial",
																					'-o-filter'			: "initial",
																					'-ms-filter'		 : "initial"});
			}
		} else {
			if (touchStartX < touchEndX - 60 && (touchStartY > touchEndY - 130 && touchStartY < touchEndY + 130)) {
				storyRotateNext(e);
			} else if (
				(touchStartX > touchEndX + 60 && (touchStartY > touchEndY - 130 && touchStartY < touchEndY + 130)) ||
				(touchStartX > touchEndX - 30 && touchStartX < touchEndX + 30 && touchStartY > touchEndY - 30 && touchStartY < touchEndY + 30)
			) {
				storyRotatePrev(e);
			} else {
				jQuery('.story_modal_window').css({'filter'				 : "initial",
																					'-webkit-filter' : "initial",
																					'-moz-filter'		: "initial",
																					'-o-filter'			: "initial",
																					'-ms-filter'		 : "initial"});
			}
		}
	});

	jQuery(document).on('touchmove touchstart touchcancel click touchend', "#ajax_modal_story.disable_rotate .story_modal_overlay_helper", function (e) {
		e.preventDefault();
	});

	jQuery(document).on('touchend click', "#ajax_modal_story.enable_rotate .story_modal_overlay_helper", function (e) {
		jQuery.modal.close();
	});

	jQuery('.ajax_modal').on('touchmove', function (e) {
		e.preventDefault();
	});

	jQuery(document).keydown(function (e) {
		switch (e.which) {
			case 37: // left
				if (jQuery('body.rtl').length) {
					storyRotatePrev(e);
				} else {
					storyRotateNext(e);
				}
				break;

			case 39: // right
				if (jQuery('body.rtl').length) {
					storyRotateNext(e);
				} else {
					storyRotatePrev(e);
				}
				break;

			case 13: // escape
				if (jQuery('.story_modal_window_current').length) {
					jQuery.modal.close();
					e.preventDefault();
				}
				break;

			default: return; // exit this handler for other keys
		}
	});

	/* --------
	end stories rotate
	------------------------------------------- */
}

jQuery(document).ready(aliaCoreReadyFn);

/* --------
start story modal resize function
------------------------------------------- */

function storyRotateNext (e) {
	if (jQuery('.story_modal_window_next').length && !jQuery("#ajax_modal_story.disable_rotate").length) {
		jQuery('#ajax_modal_story').addClass('disable_rotate').removeClass('enable_rotate');

		jQuery('.story_modal_window_before_prev').remove();

		jQuery('.story_modal_window_prev').addClass('story_modal_window_before_prev').removeClass('story_modal_window_prev').addClass('story_modal_window_preload');

		jQuery('.story_modal_window_current').addClass('story_modal_window_prev').addClass('story_modal_window_nav').removeClass('story_modal_window_current');

		jQuery('.story_modal_window_next').addClass('story_modal_window_current').removeClass('story_modal_window_next').removeClass('story_modal_window_nav');

		if (jQuery('.story_modal_window_after_next').length) {
			var postid = jQuery('.story_modal_window_after_next').attr('data-postid');
			var authorid = jQuery('.story_modal_window_after_next').attr('data-author');
			var rotateDirection = "next";

			jQuery('.story_modal_window_after_next').addClass('story_modal_window_next').removeClass('story_modal_window_after_next').removeClass('story_modal_window_preload');

			jQuery.ajax({
				url: alia_core_vars.ajax_rotate_story,
				type: 'POST',
				data: "postid=" + postid + "&authorid=" + authorid + "&rotateDirection=" + rotateDirection,
				success: function (html) {
					jQuery("#ajax_modal_story").append(html);
					jQuery('#ajax_modal_story').removeClass('disable_rotate').addClass('enable_rotate');
					jQuery('.story_modal_window').css({'filter'				 : "initial",
																					 '-webkit-filter' : "initial",
																					 '-moz-filter'		: "initial",
																					 '-o-filter'			: "initial",
																					 '-ms-filter'		 : "initial"});
				}
			});
		} else {
			jQuery('#ajax_modal_story').removeClass('disable_rotate').addClass('enable_rotate');
		}

		e.preventDefault();
	} else {
		jQuery('.story_modal_window').css({'filter'				 : "initial",
																						 '-webkit-filter' : "initial",
																						 '-moz-filter'		: "initial",
																						 '-o-filter'			: "initial",
																						 '-ms-filter'		 : "initial"});
	}
}

function storyRotatePrev (e) {
	if (jQuery('.story_modal_window_prev').length && !jQuery("#ajax_modal_story.disable_rotate").length) {
		jQuery('#ajax_modal_story').addClass('disable_rotate').removeClass('enable_rotate');

		jQuery('.story_modal_window_after_next').remove();

		jQuery('.story_modal_window_next').addClass('story_modal_window_after_next').removeClass('story_modal_window_next').addClass('story_modal_window_preload');

		jQuery('.story_modal_window_current').addClass('story_modal_window_next').addClass('story_modal_window_nav').removeClass('story_modal_window_current');

		jQuery('.story_modal_window_prev').addClass('story_modal_window_current').removeClass('story_modal_window_prev').removeClass('story_modal_window_nav');

		if (jQuery('.story_modal_window_before_prev').length) {
			var postid = jQuery('.story_modal_window_before_prev').attr('data-postid');
			var authorid = jQuery('.story_modal_window_before_prev').attr('data-author');
			var rotateDirection = "prev";

			jQuery('.story_modal_window_before_prev').addClass('story_modal_window_prev').removeClass('story_modal_window_before_prev').removeClass('story_modal_window_preload');

			jQuery.ajax({
				url: alia_core_vars.ajax_rotate_story,
				type: 'POST',
				data: "postid=" + postid + "&authorid=" + authorid + "&rotateDirection=" + rotateDirection,
				success: function (html) {
					jQuery("#ajax_modal_story").append(html);
					jQuery('#ajax_modal_story').removeClass('disable_rotate').addClass('enable_rotate');
					jQuery('.story_modal_window').css({'filter'				 : "initial",
																					 '-webkit-filter' : "initial",
																					 '-moz-filter'		: "initial",
																					 '-o-filter'			: "initial",
																					 '-ms-filter'		 : "initial"});
				}
			});
		} else {
			jQuery('#ajax_modal_story').removeClass('disable_rotate').addClass('enable_rotate');
		}

		e.preventDefault();
	} else {
		jQuery('.story_modal_window').css({'filter'				 : "initial",
																					 '-webkit-filter' : "initial",
																					 '-moz-filter'		: "initial",
																					 '-o-filter'			: "initial",
																					 '-ms-filter'		 : "initial"});
	}
}

function storyModalResizeToFit () {
	var windowHeight = jQuery(window).height();
	var windowWidth = jQuery(window).width();
	var newWindowHeight;
	var newWindowWidth;

	if ((windowHeight < 800 || windowWidth < 600) && (windowHeight / windowWidth) <= (800 / 600)) {
		newWindowHeight = windowHeight - 116;
		newWindowWidth = newWindowHeight * 600 / 800;
		jQuery('#ajax_modal_story').css({'height': newWindowHeight, 'width': newWindowWidth}).removeClass("small_screens");
	} else if ((windowHeight < 800 || windowWidth < 600) && (windowHeight / windowWidth) >= (800 / 600)) {
		newWindowWidth = windowWidth - 40;
		newWindowHeight = newWindowWidth * 800 / 600;
		jQuery('#ajax_modal_story').css({'height': newWindowHeight, 'width': newWindowWidth}).addClass("small_screens");
	} else {
		newWindowWidth = 600;
		newWindowHeight = 800;
		jQuery('#ajax_modal_story').css({'height': newWindowHeight, 'width': newWindowWidth}).addClass("small_screens");
	}
}

function htmlCheckWindowHeight () {
	var windowHeight = jQuery(window).height();
	if (windowHeight < 450) {
		jQuery('html').addClass('small_screen_height');
	} else {
		jQuery('html').removeClass('small_screen_height');
	}
}
/* --------
end story modal resize function
------------------------------------------- */
