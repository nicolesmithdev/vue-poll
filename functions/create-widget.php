<?php
add_action('widgets_init', 'register_voting_widget');

function register_voting_widget() {
    register_widget('voting_widget');
}

class voting_widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'voting_widget',
			__( 'Poll' ),
			array(
				'customize_selective_refresh' => true
			)
		);
	}

	public function form($instance) {
		$title = ( $instance ? esc_attr( $instance['title'] ) : '' ); ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php _e('Widget Title' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
	<?php }

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	public function widget($args, $instance) {
		extract($args);

		$title = isset( $instance['title'] ) ? apply_filters('widget_title', $instance['title']) : '';

		echo $before_widget;
		if ( $title ) { echo $before_title . $title . $after_title; }
		echo '<div id="app"></div>';
		echo $after_widget;
	}
}
?>