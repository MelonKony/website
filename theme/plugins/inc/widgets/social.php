<?php
add_action('widgets_init', 'social_widget_init');

function social_widget_init() {
    register_widget('social_widget');
}

class social_widget extends WP_Widget {

    function __construct() {
		parent::__construct(
			'social-widget', // Base ID
			theme_name . ' - ' . __('Social Profiles', 'alia-core'), // Name
			array( 'classname' => 'alia-social-widget',
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
        foreach ($social_networks as $network => $social ) {
            $$network = isset( $instance[$network] ) ? esc_url($instance[$network]) : '' ;
        }

        echo $before_widget;

        if ($title) :
            echo $before_title;
            echo $title;
            echo $after_title;
        endif;

        $activated = 0;

        foreach ($social_networks as $network => $social ) {
            if ( $$network != "") {

                $activated++;
                if ($activated == 1) {
                    echo '<div class="social_icons_list widget_social_icons_list">';
                }

                echo '<a rel="nofollow" target="_blank" href="'.$$network.'" title="'.$social.'" class="social_icon widget_social_icon social_' . $network . ' social_icon_' . $network . '"><i class="fab fa-' . $network . '"></i></a>';

            }
        }
        if ($activated != "0") {
            echo '</div>'; // end social_icons_list in case it's already opened
        }

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        global $social_networks;
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        foreach ($social_networks as $network => $social ) {
            $instance[$network] = $new_instance[$network];
        }
        return $instance;
    }

    function form($instance) {
        global $social_networks;
        $defaults = array('title' => __('Social Profiles', 'alia-core'));
        foreach ($social_networks as $network => $social ) {
            $defaults[$network] = '';
        }
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'alia-core'); ?>: </label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        <?php foreach ($social_networks as $network => $social ) { ?>
            <p>
                <label for="<?php echo $this->get_field_id($network); ?>"><?php echo $social; ?>: </label>
                <input id="<?php echo $this->get_field_id($network); ?>" name="<?php echo $this->get_field_name($network); ?>" value="<?php echo $instance[$network]; ?>" class="widefat" type="text" />
            </p>
        <?php } // end for each

    }

}
?>