<?php
add_action('widgets_init', 'about_me_init');

function about_me_init() {
    register_widget('alia_about_me');
}

function widgets_script(){
	global $pagenow;
	if (in_array($pagenow, array('widgets.php'))) {
    wp_enqueue_media();
		wp_enqueue_editor();
    wp_enqueue_script('widgets_script', plugin_dir_url( __FILE__ ) . 'widgets.js');
	}
}
add_action('admin_enqueue_scripts', 'widgets_script');

class alia_about_me extends WP_Widget {

    function __construct() {
		parent::__construct(
			'about-me-widget', // Base ID
			theme_name . ' - ' . __('About Me', 'alia-core'), // Name
			array( 'classname' => 'alia-about-me',
			 			 'description' => '',
						 'width' => 250,
						 'height' => 350,
						 'customize_selective_refresh' => true
					 ) // Args
		);
	}


    function widget($args, $instance) {
        extract($args);

        global $social_networks;

        $title = isset( $instance['title'] ) ? apply_filters('widget_title', $instance['title']) : '' ;
        $image = isset( $instance['image'] ) ? $instance['image'] : '' ;
				$bio_content = isset( $instance['bio_content'] ) ? $instance['bio_content'] : 'none' ;
        $below_bio = isset( $instance['below_bio'] ) ? $instance['below_bio'] : '' ;
        $stories_page_url = isset( $instance['stories_page_url'] ) ? $instance['stories_page_url'] : '' ;
        foreach ($social_networks as $network => $social ) {
            $$network = isset( $instance[$network] ) ? esc_url($instance[$network]) : '' ;
        }

        echo $before_widget;


				$image = isset( $instance['image'] ) ? esc_url($instance['image']) : '' ;

				if ($image != '') {
					?>
					<img class="alia_about_me_image" alt="<?php if (isset($title)) { echo $title; } else { echo 'About Me';}?>" src="<?php echo esc_url($image); ?>" />
					<?php
				}

				if ($title) :
					echo $before_title;
					echo $title;
					echo $after_title;
				endif;

				if ($bio_content) {
					?>
					<div class="text-widget">
						<p><?php echo do_shortcode(wp_specialchars_decode($bio_content)); ?></p>
					</div>
					<?php
				}

				$below_bio_text = '';

				if ($below_bio == 'stories') {
					$below_bio_text .= alia_stories_circles(4, $stories_page_url);
				} else if ($below_bio == 'social_links') {

	        $activated = 0;

	        foreach ($social_networks as $network => $social ) {
	            if ( $$network != "") {

	                $activated++;
	                if ($activated == 1) {
	                    $below_bio_text .= '<div class="social_icons_list widget_social_icons_list">';
	                }

	                $below_bio_text .= '<a rel="nofollow" target="_blank" href="'.$$network.'" title="'.$social.'" class="social_icon widget_social_icon social_' . $network . ' social_icon_' . $network . '"><i class="fab fa-' . $network . '"></i></a>';

	            }
	        }
	        if ($activated != "0") {
	            $below_bio_text .= '</div>'; // end social_icons_list in case it's already opened
	        }
				}

				$below_bio_text = ($below_bio_text != '') ? '<div class="below_bio_text">' . $below_bio_text . '</div>' : '' ;

				echo $below_bio_text;

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        global $social_networks;
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['image'] = strip_tags($new_instance['image']);
				$instance['bio_content'] = $new_instance['bio_content'];
        // $instance['bio_content'] = strip_tags($new_instance['bio_content']);
        $instance['below_bio'] = strip_tags($new_instance['below_bio']);
        $instance['stories_page_url'] = strip_tags($new_instance['stories_page_url']);
        foreach ($social_networks as $network => $social ) {
            $instance[$network] = $new_instance[$network];
        }
        return $instance;
    }

    function form($instance) {
        global $social_networks;
        $defaults = array(
														'title' => __('About Me', 'alia-core'),
														'image' => '',
														'bio_content' => '',
														'below_bio' => 'none',
														'stories_page_url' => '',
													);
        foreach ($social_networks as $network => $social ) {
            $defaults[$network] = '';
        }
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'alia-core'); ?>: </label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>

				<p>
         <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image', 'alia-core'); ?>:</label><br />
           <img class="custom_media_image" src="<?php if(!empty($instance['image'])){echo $instance['image'];} ?>" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" />
           <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('image'); ?>" id="<?php echo $this->get_field_id('image'); ?>" value="<?php echo $instance['image']; ?>">
           <input type="button" value="<?php _e( 'Upload Image', 'alia-core' ); ?>" class="button custom_media_upload" id="custom_image_uploader"/>
        </p>

				<p>
					<label for="<?php echo $this->get_field_id( 'bio_content' ); ?>"><?php _e( 'Bio:', 'alia-core' ); ?>:</label>
					<textarea class="widefat wp-editor-aboutme" rows="8" cols="20" id="<?php echo $this->get_field_id('bio_content'); ?>" name="<?php echo $this->get_field_name('bio_content'); ?>"><?php echo $instance['bio_content']; ?></textarea>
					<script type="text/javascript">

					</script>
        </p>

				<p>
            <label for="<?php echo $this->get_field_id('below_bio'); ?>"><?php _e('Show Below Bio', 'alia-core'); ?>: </label>
            <select id="<?php echo $this->get_field_id('below_bio'); ?>" name="<?php echo $this->get_field_name('below_bio'); ?>" >
                <option value="none" <?php if ($instance['below_bio'] == 'none') echo "selected=\"selected\"";
        else echo ""; ?>><?php _e('None', 'alia-core'); ?></option>
                <option value="stories" <?php if ($instance['below_bio'] == 'stories') echo "selected=\"selected\"";
        else echo ""; ?>><?php _e('Stories', 'alia-core'); ?></option>
                <option value="social_links" <?php if ($instance['below_bio'] == 'social_links') echo "selected=\"selected\"";
        else echo ""; ?>><?php _e('Social Profiles Links', 'alia-core'); ?></option>
            </select>
        </p>

				<script type="text/javascript">
					jQuery('#<?php echo $this->get_field_id('below_bio'); ?>').on('change', function() {
						if (jQuery(this).attr('value') == 'stories') {
							jQuery('.stories_bio').slideDown();
							jQuery('.social_bio').slideUp();
						} else if (jQuery(this).attr('value') == 'social_links') {
							jQuery('.social_bio').slideDown();
							jQuery('.stories_bio').slideUp();
						} else {
							jQuery('.stories_bio').slideUp();
							jQuery('.social_bio').slideUp();
						}
					});
					jQuery(window).on('load', function() {
							jQuery('.hide_fields').hide();
					});
					jQuery(document).ajaxComplete(function () {
						jQuery('.hide_fields').hide();
					});
				</script>
				<div class="stories_bio <?php if ($instance['below_bio'] != 'stories') { echo 'hide_fields';}?>">
					<p>
	            <label for="<?php echo $this->get_field_id('stories_page_url'); ?>"><?php _e('Stories Page URL', 'alia-core'); ?>: </label>
	            <input id="<?php echo $this->get_field_id('stories_page_url'); ?>" name="<?php echo $this->get_field_name('stories_page_url'); ?>" value="<?php echo $instance['stories_page_url']; ?>" class="widefat" type="text" />
	        </p>
				</div>
				<div class="social_bio <?php if ($instance['below_bio'] != 'social_links') { echo 'hide_fields';}?>">
	        <?php foreach ($social_networks as $network => $social ) { ?>
	            <p>
	                <label for="<?php echo $this->get_field_id($network); ?>"><?php echo $social; ?>: </label>
	                <input id="<?php echo $this->get_field_id($network); ?>" name="<?php echo $this->get_field_name($network); ?>" value="<?php echo $instance[$network]; ?>" class="widefat" type="text" />
	            </p>
	        <?php } // end for each ?>
				</div>
				<?php
    }

}
?>