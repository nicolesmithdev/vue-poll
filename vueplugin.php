<?php
/**
 * Plugin Name: Poll Plugin
 * Description: Vote for your favorite post
 * Author: Nicole Smith
 * Author URI: https://nicolesmith.dev/
 * Version: 1.0
 */

/*
 * Register, localize, and enqueue scripts
 */
add_action('wp_enqueue_scripts', 'enqueue_vue_scripts');
function enqueue_vue_scripts() {
	global $post;

	wp_register_script('vue-poll', plugin_dir_url( __FILE__ ) . 'js/vue-poll.js');
	wp_localize_script('vue-poll', 'specialObj', array( 'security' => wp_create_nonce('wp_rest') ) );

	if ( is_active_widget( false, false, 'voting_widget', true ) ) {
		wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js', [], '2.5.17');
		wp_enqueue_style('vue-poll', plugin_dir_url(__FILE__) . 'css/vue-poll.css', [], rand() );
		wp_enqueue_script('vue-poll', plugin_dir_url(__FILE__) . 'js/vue-poll.js', [], rand(), true);
	}
}

// Create Widget
add_action('widgets_init', 'register_voting_widget');
function register_voting_widget() { register_widget('voting_widget'); }
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

// Add meta data in REST API response object
add_action('rest_api_init', 'adding_post_meta_rest');
function adding_post_meta_rest() {
	register_rest_field('post', 'votes', array(
		'get_callback'		=> 'post_meta_callback',
		'update_callback'	=> function($value, $object) {
			$ret = update_post_meta( $object->ID, 'votes', $value );

			if ( false === $ret ) { return new WP_Error( 'rest_post_votes_failed', __('Failed to update post votes'), array( 'status'	=> 500 ) ); }
			return true;
		},
		'schema' => array( 'type'	=> 'integer' )
	) );

	function post_meta_callback($post, $field_name) {
		return get_post_meta( $post['id'], 'votes', true );
	}
}
?>