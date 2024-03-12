<?php
/**
 * @package Events
 * @version 1.7.2
 */
/*
Plugin Name: Events
Description: This plugin will define a custom post type so you can manage events.
Version: 1.7.2
*/

// Custom post types

function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Events', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Events', 'text_domain' ),
		'name_admin_bar'        => __( 'Event', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Event', 'text_domain' ),
		'description'           => __( 'Events for planning', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'menu_icon'             => 'dashicons-calendar',
	);
	register_post_type( 'events', $args );

	// Add custom meta box for date, time, and location
	add_action( 'add_meta_boxes', 'add_event_meta_box' );
	function add_event_meta_box() {
		add_meta_box(
			'event_details_meta_box', // Meta box ID
			'Event Details', // Title
			'event_details_meta_box_callback', // Callback function
			'events', // Post type
			'normal', // Context
			'default' // Priority
		);
	}

	// Callback function to display fields in meta box
	function event_details_meta_box_callback( $post ) {
		wp_nonce_field( 'event_details_meta_box', 'event_details_meta_box_nonce' );

		$date = get_post_meta( $post->ID, 'event_date', true );
		$time = get_post_meta( $post->ID, 'event_time', true );
		$location = get_post_meta( $post->ID, 'event_location', true );

		echo '<label for="event_date">Event Date:</label>';
		echo '<input type="date" id="event_date" name="event_date" value="' . esc_attr( $date ) . '" /><br>';

		echo '<label for="event_time">Event Time:</label>';
		echo '<input type="time" id="event_time" name="event_time" value="' . esc_attr( $time ) . '" /><br>';

		echo '<label for="event_location">Event Location:</label>';
		echo '<input type="text" id="event_location" name="event_location" value="' . esc_attr( $location ) . '" />';
	}

	// Save meta box data
	add_action( 'save_post', 'save_event_meta_box_data' );
	function save_event_meta_box_data( $post_id ) {
		if ( ! isset( $_POST['event_details_meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['event_details_meta_box_nonce'], 'event_details_meta_box' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'events' != $_POST['post_type'] ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['event_date'] ) ) {
			update_post_meta( $post_id, 'event_date', sanitize_text_field( $_POST['event_date'] ) );
		}

		if ( isset( $_POST['event_time'] ) ) {
			update_post_meta( $post_id, 'event_time', sanitize_text_field( $_POST['event_time'] ) );
		}

		if ( isset( $_POST['event_location'] ) ) {
			update_post_meta( $post_id, 'event_location', sanitize_text_field( $_POST['event_location'] ) );
		}
	}
}
add_action( 'init', 'custom_post_type', 0 );


    


