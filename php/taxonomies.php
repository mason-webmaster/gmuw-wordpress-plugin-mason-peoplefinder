<?php

/**
 * Summary: php file which implements the custom taxonomies
 */


// Register taxonomies
add_action('init', function(){

	// Register taxonomies. Register additional taxonomies here as needed.

	// Register taxonomy: buildings
		register_taxonomy(
			'building',
			'post',
			array(
			'hierarchical' => false,
			'labels' => array(
				'name' => 'Buildings',
				'singular_name' => 'Building',
				'search_items' =>  'Search Buildings',
				'all_items' => 'All Buildings',
				'parent_item' => 'Parent Building',
				'parent_item_colon' => 'Parent Building:',
				'edit_item' => 'Edit Building',
				'update_item' => 'Update Building',
				'add_new_item' => 'Add New Building',
				'new_item_name' => 'New Building',
				'menu_name' => 'Buildings',
				),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'building' ),
			)
		);

	// Register taxonomy: departments
		register_taxonomy(
			'department',
			'post',
			array(
			'hierarchical' => false,
			'labels' => array(
				'name' => 'Departments',
				'singular_name' => 'Department',
				'search_items' =>  'Search Departments',
				'all_items' => 'All Departments',
				'parent_item' => 'Parent Department',
				'parent_item_colon' => 'Parent Department:',
				'edit_item' => 'Edit Department',
				'update_item' => 'Update Department',
				'add_new_item' => 'Add New Department',
				'new_item_name' => 'New Department',
				'menu_name' => 'Departments',
				),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'department' ),
			)
		);

});
