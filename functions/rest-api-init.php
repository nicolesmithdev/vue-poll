<?php
/**
 * Add meta data to REST API response object
 */
add_action('rest_api_init', 'adding_post_meta_rest');
function adding_post_meta_rest() {
	register_rest_field(
		'post',
		'votes',
		array(
			'get_callback'		=> 'post_meta_callback',
			'update_callback'	=> function($value, $object) {
				$ret = update_post_meta( $object->ID, 'votes', $value );

				if ( false === $ret ) {
					return new WP_Error(
						'rest_post_votes_failed',
						__('Failed to update post votes'),
						array( 'status'	=> 500 )
					);
				}
				return true;
			},
			'schema' => array('type' => 'integer')
		)
	);

	function post_meta_callback($post) {
		return get_post_meta( $post['id'], 'votes', true ) ?: 0;
	}
}
?>