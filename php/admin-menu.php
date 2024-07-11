<?php

/**
 * Summary: php file which implements the plugin WP admin menu changes
 */


/**
 * Adds Mason admin menu item to Wordpress admin menu as a top-level item
 */
add_action('admin_menu', 'gmuj_add_admin_menu_mason');

// Function to add Mason admin menu item. If this shared function does not exist already, define it now.
if (!function_exists('gmuj_add_admin_menu_mason')) {

	function gmuj_add_admin_menu_mason() {

		// Add Wordpress admin menu item for mason stuff

		// If the Mason top-level admin menu item does not exist already, add it.
		if (menu_page_url('gmuw', false) == false) {

			// Add top admin menu page
			add_menu_page(
				'Mason WordPress',
				'Mason WordPress',
				'manage_options',
				'gmuw',
				function(){
					echo "<div class='wrap'>";
					echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
					echo '<p>Please use the links at left to access Mason WordPress platform features.</p>';
					echo "</div>";
				},
				gmuj_mason_svg_icon(),
				1
			);

		}

	}

}

/**
 * Adds link to plugin settings page to Wordpress admin menu as a sub-menu item under Mason
 */
add_action('admin_menu', 'gmuw_pf_add_sublevel_menu');
function gmuw_pf_add_sublevel_menu() {
	
	// Add Wordpress admin menu item under Mason for this plugin's settings
	add_submenu_page(
		'gmuw',
		'Mason People Finder',
		'Mason People Finder',
		'manage_options',
		'gmuw_pf',
		'gmuw_pf_display_settings_page',
		3
	);
	
}

/**
 * Adds link to plugin import students page to Wordpress admin menu as a sub-menu item under Mason
 */
add_action('admin_menu', 'gmuw_pf_add_sublevel_menu_item_import_students');
function gmuw_pf_add_sublevel_menu_item_import_students() {

	// Add Wordpress admin menu item under Mason for this plugin's settings
	add_submenu_page(
		'gmuw',
		'People Finder: Import Students',
		'Import Students',
		'manage_options',
		'gmuw_pf_import_students',
		'gmuw_pf_display_page_import_students',
		3
	);

}

/**
 * Adds link to plugin admin search page to Wordpress admin menu as a sub-menu item under Mason
 */
add_action('admin_menu', 'gmuw_pf_add_sublevel_menu_item_admin_search');
function gmuw_pf_add_sublevel_menu_item_admin_search() {

	add_submenu_page(
		'gmuw',
		'People Finder: Search',
		'People Finder Search',
		'manage_options',
		'gmuw_pf_admin_search',
		'gmuw_pf_display_page_admin_search',
		4
	);

}

/**
 * Adds link to plugin regenerate search keys page to Wordpress admin menu as a sub-menu item under Mason
 */
//add_action('admin_menu', 'gmuw_pf_add_sublevel_menu_item_admin_user_search_keys');
function gmuw_pf_add_sublevel_menu_item_admin_user_search_keys() {

	add_submenu_page(
		'gmuw',
		'People Finder: Generate User Search Keys',
		'People Finder Generate User Search Keys',
		'manage_options',
		'gmuw_pf_admin_user_search_keys',
		'gmuw_pf_display_page_admin_user_search_keys',
		5
	);

}
