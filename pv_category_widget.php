<?php
/*
Plugin Name: Category Widget
Plugin URI: http://www.vdvreede.net
Description: Advanced category widget with more options for displaying category menus.
Author: Paul Van de Vreede
Version: 0.5.0
Author URI: http://www.vdvreede.net
*/

add_action( 'widgets_init', create_function( '', 'return register_widget("PV_Category_Widget");' ) );

class PV_Category_Widget extends WP_Widget {
	/** constructor */
	function PV_Category_Widget() {
		parent::WP_Widget( 'pv_category_widget', $name='Category Widget' );
	}

	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
		
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ) {
			$title = esc_attr( $instance[ 'title' ] );
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php 
	}

}


