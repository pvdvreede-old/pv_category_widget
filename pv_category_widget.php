<?php
/*
  Plugin Name: Category Widget
  Plugin URI: http://www.vdvreede.net
  Description: Advanced category widget with more options for displaying category menus.
  Author: Paul Van de Vreede
  Version: 0.5.0
  Author URI: http://www.vdvreede.net
 */

add_action('widgets_init', create_function('', 'return register_widget("PV_Category_Widget");'));

class PV_Category_Widget extends WP_Widget {

    /** constructor */
    function PV_Category_Widget() {
        parent::WP_Widget('pv_category_widget', $name = 'Category Widget');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $capability_limit = $instance['capability_limit'];
        $cats_to_display = $instance['sub_cat_display'];
        $hide_empty = $instance['hide_empty'];

        $display = true;      
        
        if ($capability_limit) {
            $display = current_user_can($capability_limit);
        }

        $cat_args = array(
            'type' => 'post',
            'taxonomy' => 'category',
            'hide_empty' => $hide_empty
        );

        if ($cats_to_display) {
            $cat_args['child_of'] = $cats_to_display;
        }

        $categories = get_categories($cat_args);

        if ($display) {
            echo $before_widget;

            if ($title)
                echo $before_title . $title . $after_title;
            ?>
            <div class="pvcw-categories-display">
                <ul>
                <?php foreach ($categories as $category) : ?>
                
                    <li><a href="<?php echo get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) ?>"><?php echo $category->name; ?></a></li>
                
                <?php endforeach; ?>
                </ul>
            </div>
            <?php
            echo $after_widget;
        }
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['sub_cat_display'] = strip_tags($new_instance['sub_cat_display']);
        $instance['capability_limit'] = strip_tags($new_instance['capability_limit']);
        $instance['hide_empty'] = strip_tags($new_instance['hide_empty']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        if ($instance) {
            $title = esc_attr($instance['title']);
            $sub_cat_display = esc_attr($instance['sub_cat_display']);
            $capability_limit = esc_attr($instance['capability_limit']);
            $hide_empty = (esc_attr($instance['hide_empty']) == 1) ? 'checked' : '';
        } else {
            $title = __('New title', 'text_domain');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('sub_cat_display'); ?>"><?php _e('Only display sub cateogies of (enter category id):'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('sub_cat_display'); ?>" name="<?php echo $this->get_field_name('sub_cat_display'); ?>" type="text" value="<?php echo $sub_cat_display; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('capability_limit'); ?>"><?php _e('Only show this menu to people with this capability (blank for all visitors):'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('capability_limit'); ?>" name="<?php echo $this->get_field_name('capability_limit'); ?>" type="text" value="<?php echo $capability_limit; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e('Hide categories if empty:'); ?></label> 
            <input  id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>" type="checkbox" value="1" <?php echo $hide_empty; ?> />
        </p>
        <?php
    }

}

