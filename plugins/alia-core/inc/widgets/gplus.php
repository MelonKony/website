<?php
add_action('widgets_init', 'gplus_widget_init');

function gplus_widget_init() {
    register_widget('gplus_widget');
}

class gplus_widget extends WP_Widget {


    function __construct() {
		parent::__construct(
			'gplus-widget', // Base ID
			theme_name . ' - ' . __('Google+', 'alia-core'), // Name
			array( 'classname' => 'alia-gplus-widget',
						 'description' => '',
						 'width' => 250,
						 'height' => 350,
						 'customize_selective_refresh' => true
					 ) // Args
		);
	}

    function widget($args, $instance) {
        extract($args);
        wp_enqueue_script( 'alia-gplus-script', 'https://apis.google.com/js/platform.js', array( 'jquery' ) );

        $title = isset( $instance['title'] ) ? apply_filters('widget_title', $instance['title']) : '' ;
        $gpurl = isset( $instance['gpurl'] ) ? esc_url($instance['gpurl']) : '' ;
        $type = isset( $instance['type'] ) ? esc_attr($instance['type']) : '' ;

        echo $before_widget;

        if ($title) :
            echo $before_title;
            echo $title;
            echo $after_title;
        endif;
        $widget_width = '280';
        if ( $args['id'] == 'footer-1' || $args['id'] == 'footer-2' || $args['id'] == 'footer-3' ) {
            $widget_width = '300';
        }

        echo '<div class="g-'.$type.'" style="max-width:100%!important;" data-width="'.$widget_width.'" data-width="100%" data-href="'.$gpurl.'"></div>';

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['gpurl'] = $new_instance['gpurl'];
        $instance['type'] = $new_instance['type'];
        return $instance;
    }

    function form($instance) {
        $defaults = array('title' => __('Google+', 'alia-core'), 'gpurl' => '', 'type' => 'person');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'alia-core'); ?>: </label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('gpurl'); ?>"><?php _e('Google+ URL', 'alia-core'); ?>: </label>
            <input id="<?php echo $this->get_field_id('gpurl'); ?>" name="<?php echo $this->get_field_name('gpurl'); ?>" value="<?php echo $instance['gpurl']; ?>" class="widefat" type="text" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e('Profile Type', 'alia-core'); ?>: </label>
            <select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>" >
                <option value="person" <?php if ($instance['type'] == 'person') echo "selected=\"selected\"";
        else echo ""; ?>><?php _e('Person', 'alia-core'); ?></option>
                <option value="page" <?php if ($instance['type'] == 'page') echo "selected=\"selected\"";
        else echo ""; ?>><?php _e('Page', 'alia-core'); ?></option>
                <option value="community" <?php if ($instance['type'] == 'community') echo "selected=\"selected\"";
        else echo ""; ?>><?php _e('Community', 'alia-core'); ?></option>
            </select>
        </p>
        <?php
    }

}
?>