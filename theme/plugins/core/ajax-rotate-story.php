<?php

define('WP_USE_THEMES', false);
$wpdir = explode( "wp-content" , __FILE__ );
require $wpdir[0] . "wp-load.php";

if (isset($_POST['postid'])) {
	$postid  = $_POST['postid'];
	$authorid  = $_POST['authorid'];

	$rotateDirection = $_POST['rotateDirection'];

	$current_post = get_post($postid);


	// query next 1 posts
	$args = array(
		'posts_per_page' => 1,
	    'post_type'     =>  'story',
	    'orderby'       =>  'post_date',
	    'order'         =>  'ASC',
	    'date_query' => array(
	        'after' => $current_post->post_date
	    ),
	);
	if ($authorid != 0) {
		$args['author'] = $authorid;
	}

	$next_posts = new WP_Query($args);
	?>
	<!-- start checking for next story -->
	<?php if ( $rotateDirection == "next" && $next_posts->have_posts() ) : ?>
		<?php
		while ( $next_posts->have_posts() ) : $next_posts->the_post();

				// default image url
				$story_bg_image = get_the_post_thumbnail_url(get_the_ID(), 'alia_story_image', array( 'class' => 'img-responsive' ));

				if (function_exists('alia_option') && alia_option('alia_stories_cdn_url') ) {
					// cdn url
					$story_bg_image = str_replace(get_site_url(), alia_option('alia_stories_cdn_url'), $story_bg_image );
				}
				
				?>
				<div data-postid="<?php echo esc_attr(get_the_ID()); ?>" data-author="<?php echo esc_attr($authorid); ?>" class="story_modal_window story_modal_window_nav story_modal_window_preload story_modal_window_after_next"
					style="background-image: url('<?php echo esc_url($story_bg_image); ?>')"
					>
					<?php echo alia_story_content_overlay(get_post()); ?>

				</div>
				<?php
		endwhile; 
		?>
	<?php endif; ?>


	<?php 
	// query previous 1 posts
	$args = array(
		'posts_per_page' => 1,
	    'post_type'     =>  'story',
	    'orderby'       =>  'post_date',
	    'order'         =>  'DESC',
	    'date_query' => array(
	        'before' => $current_post->post_date
	    ),
	);
	if ($authorid != 0) {
		$args['author'] = $authorid;
	}

	$prev_posts = new WP_Query($args);
	?>
	<!-- start checking for previous story -->
	<?php if ( $rotateDirection == "prev" && $prev_posts->have_posts() ) : ?>
		<?php
		while ( $prev_posts->have_posts() ) : $prev_posts->the_post();
				// default image url
				$story_bg_image = get_the_post_thumbnail_url(get_the_ID(), 'alia_story_image', array( 'class' => 'img-responsive' ));

				if (function_exists('alia_option') && alia_option('alia_stories_cdn_url') ) {
					// cdn url
					$story_bg_image = str_replace(get_site_url(), alia_option('alia_stories_cdn_url'), $story_bg_image );
				}
				?>
				<div data-postid="<?php echo esc_attr(get_the_ID()); ?>" data-author="<?php echo esc_attr($authorid); ?>" class="story_modal_window story_modal_window_nav story_modal_window_preload story_modal_window_before_prev"
					style="background-image: url('<?php echo esc_url($story_bg_image); ?>')"
					>
					<?php echo alia_story_content_overlay(get_post()); ?>
						
				</div>
				<?php
		endwhile; 
		?>
	<?php endif;
}
?>
