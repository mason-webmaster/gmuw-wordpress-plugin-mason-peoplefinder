<?php
/**
 * Handle custom post type
 */

/**
 * Register a custom post type
 */
add_action('init', 'gmuw_register_custom_post_type_departments');
function gmuw_register_custom_post_type_departments() {

    $labels = array(
        'name'                  => 'Departments',
        'singular_name'         => 'Department',
        'menu_name'             => 'Departments',
        'name_admin_bar'        => 'Department',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Department',
        'new_item'              => 'New Department',
        'edit_item'             => 'Edit Department',
        'view_item'             => 'View Department',
        'all_items'             => 'All Departments',
        'search_items'          => 'Search Departments',
        'parent_item_colon'     => 'Parent Department:',
        'not_found'             => 'No Departments found.',
        'not_found_in_trash'    => 'No Departments found in Trash.',
        'featured_image'        => 'Department Image',
        'set_featured_image'    => 'Set department image',
        'remove_featured_image' => 'Remove department image',
        'use_featured_image'    => 'Use as department image',
        'archives'              => 'Departments archives',
        'insert_into_item'      => 'Insert into department',
        'uploaded_to_this_item' => 'Uploaded to this department',
        'filter_items_list'     => 'Filter department list',
        'items_list_navigation' => 'Department list navigation',
        'items_list'            => 'Department list',
    );

    // Set up arguments for the register_post_type function
    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'department'),
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'menu_position'     => 20,
        'menu_icon'         => gmuw_pf_get_cpt_icon('department'),
        'show_in_rest'      => true,
        'supports'          => array('title', 'editor', 'thumbnail'),
    );

    // Register department custom post type
    register_post_type('department', $args);

}

// Add additional columns to the admin list
add_filter ('manage_department_posts_columns', 'gmuw_pf_add_columns_department');
function gmuw_pf_add_columns_department ($columns) {

    return array_merge ( $columns, array ( 
        //ACF fields
		'phone_number' => 'Phone number',
		'fax_number' => 'Fax number',
		'mail_stop_number' => 'Mail stop number',
		'room_number' => 'Room number',
		'building' => 'Building',
		'contact_email' => 'Contact email',
		'acronym' => 'Acronym',
    ) );

}

// Generate field output for additional columns in the admin list
add_action ('manage_department_posts_custom_column', 'gmuw_pf_department_custom_column', 10, 2);
function gmuw_pf_department_custom_column ($column, $post_id) {

    switch ($column) {
        case '':
            break;
        default:
            echo get_post_meta($post_id, $column, true);
        	break;
    }

}
