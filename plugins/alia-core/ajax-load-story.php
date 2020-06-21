<?php

define('WP_USE_THEMES', false);
$wpdir = explode( "wp-content" , __FILE__ );
require $wpdir[0] . "wp-load.php";

if (isset($_POST['postid'])):
	$postid  = $_POST['postid'];
	$authorid  = $_POST['authorid'];


	$current_post = get_post($postid);

	// default image url
	$story_bg_image = get_the_post_thumbnail_url($postid, 'alia_story_image', array( 'class' => 'img-responsive' ));

	if (function_exists('alia_option') && alia_option('alia_stories_cdn_url') ) {
		// cdn url
		$story_bg_image = str_replace(get_site_url(), alia_option('alia_stories_cdn_url'), $story_bg_image );
	}
	?>

	<!-- show current story window -->
	<div data-postid="<?php echo esc_attr($current_post->ID); ?>" data-author="<?php echo esc_attr($authorid); ?>" class="story_modal_window story_modal_window_current"
		style="background-image: url('<?php echo esc_url($story_bg_image); ?>')"
		>

		<?php echo alia_story_content_overlay($current_post); ?>

	</div>


	<?php 
	// query next 2 posts
	$args = array(
		'posts_per_page' => 2,
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
	$post_num = 0;
	?>
	<!-- start checking for next and after the next story -->
	<?php if ( $next_posts->have_posts() ) : ?>
		<?php
		while ( $next_posts->have_posts() ) : $next_posts->the_post();
				$post_num++;
				if ($post_num == 1) {
					// default image url
					$story_bg_image = get_the_post_thumbnail_url(get_the_ID(), 'alia_story_image', array( 'class' => 'img-responsive' ));

					if (function_exists('alia_option') && alia_option('alia_stories_cdn_url') ) {
						// cdn url
						$story_bg_image = str_replace(get_site_url(), alia_option('alia_stories_cdn_url'), $story_bg_image );
					}
					?>
					<div data-postid="<?php echo esc_attr(get_the_ID()); ?>" data-author="<?php echo esc_attr($authorid); ?>" class="story_modal_window story_modal_window_nav story_modal_window_next"
						style="background-image: url('<?php echo esc_url($story_bg_image); ?>')"
						>

						<?php echo alia_story_content_overlay(get_post()); ?>

					</div>
					<?php
				}elseif($post_num == 2) {
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
				}
		endwhile; 
		?>
	<?php endif; ?>




	<?php 
	// query previous 2 posts
	$args = array(
		'posts_per_page' => 2,
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
	$post_num = 0;
	?>
	<!-- start checking for previous and after the previous story -->
	<?php if ( $prev_posts->have_posts() ) : ?>
		<?php
		while ( $prev_posts->have_posts() ) : $prev_posts->the_post();
				$post_num++;
				if ($post_num == 1) {
					// default image url
					$story_bg_image = get_the_post_thumbnail_url(get_the_ID(), 'alia_story_image', array( 'class' => 'img-responsive' ));

					if (function_exists('alia_option') && alia_option('alia_stories_cdn_url') ) {
						// cdn url
						$story_bg_image = str_replace(get_site_url(), alia_option('alia_stories_cdn_url'), $story_bg_image );
					}
					?>
					<div data-postid="<?php echo esc_attr(get_the_ID()); ?>" data-author="<?php echo esc_attr($authorid); ?>" class="story_modal_window story_modal_window_nav story_modal_window_prev" 
							style="background-image: url('<?php echo esc_url($story_bg_image); ?>')"
						>
						
						<?php echo alia_story_content_overlay(get_post()); ?>

					</div>
					<?php
				}elseif($post_num == 2) {
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
				}
		endwhile; 
		?>
	<?php endif; ?>
	<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a>
<?php endif; ?>