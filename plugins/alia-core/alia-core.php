<?php
/*
Plugin Name: Alia Core
Plugin URI: http://ahmad.works/alia/
Description: The core plugin of Alia Wordpress theme
Author: Ahmad Works
Author URI: http://ahmad.works/
License: Themeforest Split Licence
License URI: -
Version: 1.17
*/

$social_networks = array(
												"facebook-square" => "Facebook",
												"twitter" => "Twitter",
												"google-plus" =>  "Google Plus",
												"behance" => "Behance",
												"dribbble" => "Dribbble",
												"linkedin" => "Linked In",
												"youtube" => "Youtube",
												'vimeo-square' => 'Vimeo',
												"vk" => "VK",
												"vine" => "Vine",
												"digg" => "Digg",
												"skype" => "Skype",
												"instagram" => "Instagram",
												"pinterest" => "Pinterest",
												"github" => "Github",
												"bitbucket" => "Bitbucket",
												"stack-overflow" => "Stack Overflow",
												"renren" => "Ren Ren",
												"flickr" => "Flickr",
												"soundcloud" => "Soundcloud",
												"steam" => "Steam",
												"qq" => "QQ",
												"slideshare" => "Slideshare",
												'discord' => 'Discord',
												'telegram' => 'Telegram',
												'medium-m' => 'Medium',
												'rss' => 'RSS',
												'mailchimp' => 'Mailchimp'
											);

/* --------
include widgets
------------------------------------------- */
require dirname( __FILE__ ) . '/inc/widgets/about-me.php';
require dirname( __FILE__ ) . '/inc/widgets/fbpage.php';
require dirname( __FILE__ ) . '/inc/widgets/stories_circles.php';
require dirname( __FILE__ ) . '/inc/widgets/gplus.php';
require dirname( __FILE__ ) . '/inc/widgets/postlist.php';
require dirname( __FILE__ ) . '/inc/widgets/social.php';

/* --------
alia core init
------------------------------------------- */
function alia_core_init() {
    $daynight_core_active = ture;
    return $daynight_core_active;
}


function alia_load_textdomain() {
    load_plugin_textdomain( 'alia-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'alia_load_textdomain' );


function alia_core_setup() {
    add_image_size( 'alia_story_image', 600, 800, true );
    add_image_size( 'alia_story_thumbnail', 300, 398, true );
}
add_action( 'after_setup_theme', 'alia_core_setup' );

/* --------
register scripts
------------------------------------------- */
if ( ! function_exists( 'alia_core_scripts' ) ) :
function alia_core_scripts() {
    wp_enqueue_script( 'alia-core-script', plugins_url( '/inc/alia-core.js', __FILE__ ) , array( 'alia-global-script' ), '1.17', true );

    // define js vars
    $alia_core_variables_array = array(
        'ajax_load_story' => plugins_url( '/ajax-load-story.php', __FILE__ ),
        'ajax_rotate_story' => plugins_url( '/ajax-rotate-story.php', __FILE__ ),
    );

    wp_localize_script( 'alia-core-script', 'alia_core_vars', $alia_core_variables_array );

}
endif;
add_action( 'wp_enqueue_scripts', 'alia_core_scripts' );
/* --------
register scripts
------------------------------------------- */

/* --------
 * Create Story posttype
------------------------------------------- */
function alia_create_stories() {
  if (alia_option('alia_stories_slug') != '') {
    $slug = alia_option('alia_stories_slug');
  } else {
    $slug = _x('story', 'Stories Slug to Show at URLs', 'alia-core');
  }
  register_post_type( 'story',
    array(
      'labels' => array(
        'name'               => _x( 'Stories', 'Stories general name', 'alia-core' ),
        'singular_name'      => _x( 'Story', 'Stories singular name', 'alia-core' ),
        'menu_name'          => _x( 'Stories', 'Stories admin menu', 'alia-core' ),
        'name_admin_bar'     => _x( 'Story', 'Add new story on admin bar', 'alia-core' ),
        'add_new'            => _x( 'Add New', 'Add new story', 'alia-core' ),
        'add_new_item'       => __( 'Add New Story', 'alia-core' ),
        'new_item'           => __( 'New Story', 'alia-core' ),
        'edit_item'          => __( 'Edit Story', 'alia-core' ),
        'view_item'          => __( 'View Story', 'alia-core' ),
        'all_items'          => __( 'All Stories', 'alia-core' ),
        'search_items'       => __( 'Search Stories', 'alia-core' ),
        'parent_item_colon'  => __( 'Parent Stories:', 'alia-core' ),
        'not_found'          => __( 'No stories found.', 'alia-core' ),
        'not_found_in_trash' => __( 'No stories found in Trash.', 'alia-core' )
      ),
      'public' => true,
      'has_archive' => true,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-format-image',
      'supports' => array('title', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions'),
      'has_archive' => true,
      'delete_with_user' => true,
      'rewrite' => array(
        'slug' => $slug
      )
    )
  );
}
add_action( 'init', 'alia_create_stories' );

add_filter( 'post_updated_messages', 'alia_story_updated_messages' );
function alia_story_updated_messages( $messages ) {
    $post             = get_post();
    $post_type        = get_post_type( $post );
    $post_type_object = get_post_type_object( $post_type );

    $messages['story'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => __( 'Story updated.', 'alia-core' ),
        2  => __( 'Custom field updated.', 'alia-core' ),
        3  => __( 'Custom field deleted.', 'alia-core' ),
        4  => __( 'Story updated.', 'alia-core' ),
        /* translators: %s: date and time of the revision */
        5  => isset( $_GET['revision'] ) ? sprintf( __( 'Story restored to revision from %s', 'alia-core' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6  => __( 'Story published.', 'alia-core' ),
        7  => __( 'Story saved.', 'alia-core' ),
        8  => __( 'Story submitted.', 'alia-core' ),
        9  => sprintf(
            __( 'Story scheduled for: <strong>%1$s</strong>.', 'alia-core' ),
            // translators: Publish box date format, see http://php.net/date
            date_i18n( __( 'M j, Y @ G:i', 'alia-core' ), strtotime( $post->post_date ) )
        ),
        10 => __( 'Story draft updated.', 'alia-core' )
    );

    if ( $post_type_object->publicly_queryable && 'story' === $post_type ) {
        $permalink = get_permalink( $post->ID );

        $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View story', 'alia-core' ) );
        $messages[ $post_type ][1] .= $view_link;
        $messages[ $post_type ][6] .= $view_link;
        $messages[ $post_type ][9] .= $view_link;

        $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
        $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview story', 'alia-core' ) );
        $messages[ $post_type ][8]  .= $preview_link;
        $messages[ $post_type ][10] .= $preview_link;
    }

    return $messages;
}


/* --------
social share icons
------------------------------------------- */
if (!function_exists('alia_share_icons')):
    function alia_share_icons() {

        $image_url = '';
        if (has_post_thumbnail() ) {
            $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            $image_url = $image_url[0];
        }

        $pinterest_title = str_replace(' ', '%20', get_the_title() );
        $pinterest_media = '';
        $pinterest_media_sep = '';
        if ($image_url != '') {
            $pinterest_media_sep = '&amp;media=';
            $pinterest_media =  $image_url;

        }

        $active_icons_num = 0;
				$amp = false;
				if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
					$amp = true;
				}

        ?>
        <?php if (alia_option('alia_facebook_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;

                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="share_item share_item_social share_facebook" <?php if (!$amp) { ?>onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>', 'facebook-share-dialog', 'width=626,height=436');
                return false;"<?php } ?> ><i class="fab fa-facebook-square"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_twitter_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://twitter.com/share?url=<?php echo urlencode(get_the_permalink()); ?>" target="_blank" class="share_item share_item_social share_twitter"><i class="fab fa-twitter"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_gplus_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" <?php if (!$amp) { ?>onclick="javascript:window.open(this.href,
                                        '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                return false;"<?php } ?> class="share_item share_item_social share_googleplus"><i class="fab fa-google-plus"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_linkedin_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>" target="_blank" class="share_item share_item_social share_linkedin"><i class="fab fa-linkedin"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_pinterest_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.pinterest.com/pin/create/button/?url=<?php the_permalink(); ?><?php echo esc_attr($pinterest_media_sep) . esc_url($pinterest_media); ?>&amp;description=<?php echo esc_attr($pinterest_title); ?>" class="share_item share_item_social share_pinterest" target="_blank"><i class="fab fa-pinterest"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_reddit_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://reddit.com/submit?url=<?php the_permalink(); ?>" class="share_item share_item_social share_reddit" target="_blank"><i class="fab fa-reddit"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_tumblr_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.tumblr.com/share/link?url=<?php the_permalink(); ?>" class="share_item share_item_social share_tumblr" target="_blank"><i class="fab fa-tumblr"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_vk_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://vk.com/share.php?url=<?php the_permalink(); ?>" class="share_item share_item_social share_vk" target="_blank"><i class="fab fa-vk"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_pocket_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a class="share_item share_item_social share_pocket" href="https://getpocket.com/save?url=<?php the_permalink(); ?>&title=<?php echo esc_attr($pinterest_title); ?>" data-event-category="Social" data-event-action="Share:pocket"><i class="fab fa-get-pocket"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_stumbleupon_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a class="share_item share_item_social share_stumbleupon" <?php if (!$amp) { ?>onclick="javascript:window.open('http://www.stumbleupon.com/badge/?url=<?php the_permalink(); ?>');return false;"<?php } ?> href="http://www.stumbleupon.com/badge/?url=<?php the_permalink(); ?>" target="_blank"><i class="fab fa-stumbleupon"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_whatsapp_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a class="share_item share_item_social share_whatsapp" href="whatsapp://send?text=<?php the_permalink(); ?>" data-action="share/whatsapp/share" target="_blank"><i class="fab fa-whatsapp"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_telegram_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="post_share_container clearfix">';

                        echo '<span class="share_item share_title">'._x( 'Share', 'Before share icons', 'alia-core' ).'</span>';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a class="share_item share_item_social share_telegram" href="https://t.me/share/url?url=<?php the_permalink(); ?>"><i class="fab fa-telegram"></i></a></span>
        <?php endif; ?>

        <?php

        if ($active_icons_num > 0) {
            echo '</div></div>'; // close .post_share_container .post_share_icons_wrapper
        }
    }
endif;


/* --------
social share icons - Posts List
------------------------------------------- */
if (!function_exists('alia_blog_list_share_icons')):
    function alia_blog_list_share_icons() {

        $image_url = '';
        if (has_post_thumbnail() ) {
            $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            $image_url = $image_url[0];
        }

        $pinterest_title = str_replace(' ', '%20', get_the_title() );
        $pinterest_media = '';
        $pinterest_media_sep = '';
        if ($image_url != '') {
            $pinterest_media_sep = '&amp;media=';
            $pinterest_media =  $image_url;

        }

        $active_icons_num = 0;
				$amp = false;
				if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
					$amp = true;
				}

        ?>
        <?php if (alia_option('alia_blog_list_facebook_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;

                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="share_item share_item_social share_facebook" <?php if (!$amp) { ?>onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>', 'facebook-share-dialog', 'width=626,height=436');
                return false;"<?php } ?>><i class="fab fa-facebook-square"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_twitter_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://twitter.com/share?url=<?php echo urlencode(get_the_permalink()); ?>" target="_blank" class="share_item share_item_social share_twitter"><i class="fab fa-twitter"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_gplus_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" <?php if (!$amp) { ?>onclick="javascript:window.open(this.href,
                                        '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                return false;"<?php } ?> class="share_item share_item_social share_googleplus"><i class="fab fa-google-plus"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_linkedin_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>" target="_blank" class="share_item share_item_social share_linkedin"><i class="fab fa-linkedin"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_pinterest_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.pinterest.com/pin/create/button/?url=<?php the_permalink(); ?><?php echo esc_attr($pinterest_media_sep) . esc_url($pinterest_media); ?>&amp;description=<?php echo esc_attr($pinterest_title); ?>" class="share_item share_item_social share_pinterest" target="_blank"><i class="fab fa-pinterest"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_reddit_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://reddit.com/submit?url=<?php the_permalink(); ?>" class="share_item share_item_social share_reddit" target="_blank"><i class="fab fa-reddit"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_tumblr_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.tumblr.com/share/link?url=<?php the_permalink(); ?>" class="share_item share_item_social share_tumblr" target="_blank"><i class="fab fa-tumblr"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_vk_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://vk.com/share.php?url=<?php the_permalink(); ?>" class="share_item share_item_social share_vk" target="_blank"><i class="fab fa-vk"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_pocket_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a class="share_item share_item_social share_pocket" href="https://getpocket.com/save?url=<?php the_permalink(); ?>&title=<?php echo esc_attr($pinterest_title); ?>" data-event-category="Social" data-event-action="Share:pocket"><i class="fab fa-get-pocket"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_stumbleupon_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a class="share_item share_item_social share_stumbleupon" <?php if (!$amp) { ?>onclick="javascript:window.open('http://www.stumbleupon.com/badge/?url=<?php the_permalink(); ?>');return false;"<?php } ?> href="http://www.stumbleupon.com/badge/?url=<?php the_permalink(); ?>" target="_blank"><i class="fab fa-stumbleupon"></i></a></span>
        <?php endif; ?>

				<?php if (alia_option('alia_blog_list_whatsapp_share', 1)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a class="share_item share_item_social share_whatsapp" href="whatsapp://send?text=<?php the_permalink(); ?>" data-action="share/whatsapp/share" target="_blank"><i class="fab fa-whatsapp"></i></a></span>
        <?php endif; ?>

        <?php if (alia_option('alia_blog_list_telegram_share', 0)): ?>
            <?php
                if ($active_icons_num == 0) {
                    echo '<div class="blog_list_share_container clearfix">';

                        echo '<div class="post_share_icons_wrapper">';
                    $active_icons_num++;


                }
            ?>
            <span class="social_share_item_wrapper"><a class="share_item share_item_social share_telegram" href="https://t.me/share/url?url=<?php the_permalink(); ?>"><i class="fab fa-telegram"></i></a></span>
        <?php endif; ?>

        <?php

        if ($active_icons_num > 0) {
                echo '</div>'; // close.post_share_icons_wrapper

            echo '</div>'; // close .blog_list_share_container
        }
    }
endif;

/* --------
author social icons
------------------------------------------- */
if (!function_exists('alia_social_icons')):
function alia_author_social_icons() {
    global $social_networks;

    $social_networks['user_url'] = esc_attr__('Website', 'alia-core');

    $output = '';
    $activated = 0;
    foreach ($social_networks as $network => $social ) {
        if (get_the_author_meta($network) != "") {
            $activated++;
            if ($activated == 1) {
                $output .= '<div class="social_icons_list author_social_icons_list">';
            }

            $icon_class = $network;

            if ($network == 'user_url') {
                $icon_class = 'globe fas';
            }

            $output .= '<a rel="nofollow" target="_blank" href="'.get_the_author_meta($network).'" title="'.$social.'" class="social_icon author_social_icon social_' . $network . ' social_icon_' . $network . '"><i class="fab fa-' . $icon_class . '"></i></a>';

        }
    }

    if ($activated != "0") {
        $output .= '</div>'; // end social_icons_list in case it's already opened
    }

    return $output;
}
endif;
add_filter('user_contactmethods','alia_author_social_profiles',10,1);


if (!function_exists('alia_toolbar_link')):
function alia_toolbar_link( $wp_admin_bar ) {

	$alia_theme = wp_get_theme();

	$args = array(
		'id'    => 'alia_changelog',
		'title' => sprintf(esc_attr__('Alia %s Changelog', 'alia'), $alia_theme->get( 'Version' ) ),
		'href'  => 'https://ahmad.works/alia/alia-release-notes?utm_medium=alia_adminbar',
		'meta'  => array( 'class' => 'alia-changelog-page', 'target' => '_blank' )
	);
	$wp_admin_bar->add_node( $args );
}
endif;
add_action( 'admin_bar_menu', 'alia_toolbar_link', 999 );
?>