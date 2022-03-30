<?php
/*
 * Register, localize, and enqueue scripts
 */
add_action('wp_enqueue_scripts', 'enqueue_vue_scripts');
function enqueue_vue_scripts() {
	global $post;

	wp_register_script('vue-poll', plugin_dir_url( __DIR__ ) . 'js/app.js');
	wp_localize_script('vue-poll', 'specialObj', 
		array(
			'appUser'	=> 'USERNAME',
			'appPass'	=> 'APPLICATION_PASSWORD_HERE',
			'security' 	=> wp_create_nonce('wp_rest')
		)
	);

	if ( is_active_widget( false, false, 'voting_widget', true ) ) {
		wp_enqueue_style( 'vue-poll', plugin_dir_url(__DIR__) . 'css/app.css', [], rand() );
		wp_enqueue_script('vue-poll', plugin_dir_url(__DIR__) . 'js/app.js', [], rand(), true);
	}
}
?>