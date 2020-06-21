<?php
add_action('widgets_init', 'alia_stories_circles_widget_init');

function alia_stories_circles_widget_init() {
    register_widget('alia_stories_circles_widget');
}

class alia_stories_circles_widget extends WP_Widget {

    function __construct() {
		parent::__construct(
			'alia-stories-circles-widget', // Base ID
			theme_name . ' - ' . __('Stories Circles', 'alia-core'), // Name
			array( 'classname' => 'alia-stories-circles-widget',
						 'description' => __('Shows 5 circles of the latest stories', 'alia-core'),
						 'width' => 250,
						 'height' => 350,
						 'customize_selective_refresh' => true
						) // Args
		);
	}

    function widget($args, $instance) {
        extract($args);

        $title = isset( $instance['title'] ) ? apply_filters('widget_title', $instance['title']) : '';
        $stories_page_url = isset( $instance['stories_page_url'] ) ? esc_url($instance['stories_page_url']) : '';
        echo $before_widget;

        if ($title) :
            echo $before_title;
            echo $title;
            echo $after_title;
        endif;

        echo alia_stories_circles(4, $stories_page_url);
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['stories_page_url'] = $new_instance['stories_page_url'];
        return $instance;
    }

    function form($instance) {
        $defaults = array('title' => '', 'stories_page_url' => '');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'alia-core'); ?>: </label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('stories_page_url'); ?>"><?php _e('Stories Page URL', 'alia-core'); ?>: </label>
            <input id="<?php echo $this->get_field_id('stories_page_url'); ?>" name="<?php echo $this->get_field_name('stories_page_url'); ?>" value="<?php echo $instance['stories_page_url']; ?>" class="widefat" type="text" />
        </p>
        <?php
    }

}
?>