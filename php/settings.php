<?php

/**
 * Summary: php file which sets up plugin settings
 */


/**
 * Register plugin settings
 */
add_action('admin_init', 'gmuw_pf_register_settings');
function gmuw_pf_register_settings() {
	
	/*
	Code reference:

	register_setting( 
		string   $option_group, // name of option group - should match the parameter used in the settings_fields function in the display_settings_page function
		string   $option_name, // name of the particular option
		callable $sanitize_callback = '' // function used to validate settings
	);

	add_settings_section( 
		string   $id, // section id
		string   $title, // title/heading of section
		callable $callback, // function that displays section
		string   $page // admin page (slug) on which this section should be displayed
	);

	add_settings_field(
    	string   $id, // setting id
		string   $title, // title of setting
		callable $callback, // outputs markup required to display the setting
		string   $page, // page on which setting should be displayed, same as menu slug of the menu item
		string   $section = 'default', // section id in which this setting is placed
		array    $args = [] // array the contains data to be passed to the callback function. by convention I pass back the setting id and label to make things easier
	);
	*/

	// Register serialized options setting to store this plugin's options
	register_setting(
		'gmuw_pf_options',
		'gmuw_pf_options',
		'gmuw_pf_callback_validate_options'
	);

	// Add section: general settings
	add_settings_section(
		'gmuw_pf_section_settings_general',
		'General',
		'gmuw_pf_callback_section_settings_general',
		'gmuw_pf'
	);

	// Add field: setting one
	add_settings_field(
		'setting_one',
		'Setting One',
		'gmuw_pf_callback_field_text',
		'gmuw_pf',
		'gmuw_pf_section_settings_general',
		['id' => 'setting_one', 'label' => 'Setting one']
	);

} 
