<?php
add_action('widgets_init', 'fbpage_widget_init');

function fbpage_widget_init() {
    register_widget('fbpage_widget');
}

class fbpage_widget extends WP_Widget {

    function __construct() {
		parent::__construct(
			'fbpage-widget', // Base ID
			theme_name . ' - '. __('Facebook Page', 'alia-core'), // Name
			array( 'classname' => 'alia-fbpage-widget',
						 'description' => '',
						 'width' => 250,
						 'height' => 350,
						 'customize_selective_refresh' => true,
					  ) // Args
		);
	}

    function widget($args, $instance) {
        extract($args);

        $title = isset( $instance['title'] ) ? apply_filters('widget_title', $instance['title']) : '';
        $fburl = isset( $instance['fburl'] ) ? esc_url($instance['fburl']) : '';
        $small_header = (isset($instance['small_header'])) ? $instance['small_header'] : 'false';
        $hide_posts = (isset($instance['hide_posts']) && $instance['hide_posts'] == 'true') ? '' : 'data-tabs="timeline"';
        $hide_cover = (isset($instance['hide_cover'])) ? $instance['hide_cover'] : 'false';
        $show_faces = (isset($instance['show_faces'])) ? $instance['show_faces'] : 'true';
        echo $before_widget;

        if ($title) :
            echo $before_title;
            echo $title;
            echo $after_title;
        endif;

        echo '<div class="fb-page" data-href="'.$fburl.'"  '.$hide_posts.' data-width="280" data-small-header="'.$small_header.'" data-adapt-container-width="true" data-hide-cover="'.$hide_cover.'" data-show-facepile="'.$show_faces.'"><div class="fb-xfbml-parse-ignore"></div></div>';
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['fburl'] = $new_instance['fburl'];
        $instance['hide_posts'] = $new_instance['hide_posts'];
        $instance['hide_cover'] = $new_instance['hide_cover'];
        $instance['show_faces'] = $new_instance['show_faces'];
        $instance['small_header'] = $new_instance['small_header'];
        return $instance;
    }

    function form($instance) {
        $defaults = array('title' => __('Facebook Page', 'alia-core'), 'fburl' => '', 'hide_posts' => 'false', 'hide_cover' => 'false', 'small_header' => 'false', 'show_faces' => 'true');
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'alia-core'); ?>: </label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('fburl'); ?>"><?php _e('Facebook Page URL', 'alia-core'); ?>: </label>
            <input id="<?php echo $this->get_field_id('fburl'); ?>" name="<?php echo $this->get_field_name('fburl'); ?>" value="<?php echo $instance['fburl']; ?>" class="widefat" type="text" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('hide_posts'); ?>"><?php _e('Hide Page Posts', 'alia-core'); ?>: </label>
          <select id="<?php echo $this->get_field_id('hide_posts'); ?>" name="<?php echo $this->get_field_name('hide_posts'); ?>" >
              <option value="false" <?php if ($instance['hide_posts'] == 'false') echo "selected=\"selected\"";
          else echo ""; ?>><?php _e('No', 'alia-core'); ?></option>
              <option value="true" <?php if ($instance['hide_posts'] == 'true') echo "selected=\"selected\"";
          else echo ""; ?>><?php _e('Yes', 'alia-core'); ?></option>
          </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('small_header'); ?>"><?php _e('Use Small Header', 'alia-core'); ?>: </label>
          <select id="<?php echo $this->get_field_id('small_header'); ?>" name="<?php echo $this->get_field_name('small_header'); ?>" >
              <option value="false" <?php if ($instance['small_header'] == 'false') echo "selected=\"selected\"";
          else echo ""; ?>><?php _e('No', 'alia-core'); ?></option>
              <option value="true" <?php if ($instance['small_header'] == 'true') echo "selected=\"selected\"";
          else echo ""; ?>><?php _e('Yes', 'alia-core'); ?></option>
          </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('hide_cover'); ?>"><?php _e('Hide Cover Photo', 'alia-core'); ?>: </label>
          <select id="<?php echo $this->get_field_id('hide_cover'); ?>" name="<?php echo $this->get_field_name('hide_cover'); ?>" >
              <option value="false" <?php if ($instance['hide_cover'] == 'false') echo "selected=\"selected\"";
          else echo ""; ?>><?php _e('No', 'alia-core'); ?></option>
              <option value="true" <?php if ($instance['hide_cover'] == 'true') echo "selected=\"selected\"";
          else echo ""; ?>><?php _e('Yes', 'alia-core'); ?></option>
          </select>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php _e('Show Friend\'s Faces', 'alia-core'); ?>: </label>
          <select id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" >
              <option value="true" <?php if ($instance['show_faces'] == 'true') echo "selected=\"selected\"";
              else echo ""; ?>><?php _e('Yes', 'alia-core'); ?></option>
              <option value="false" <?php if ($instance['show_faces'] == 'false') echo "selected=\"selected\"";
          else echo ""; ?>><?php _e('No', 'alia-core'); ?></option>
          </select>
        </p>
        <?php
    }

}
?>