<?php

/**
 * Summary: php file which implements the plugin WP admin page interface
 */


/**
 * Generates the plugin settings page
 */
function gmuw_pf_display_settings_page() {
	
	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	// Output basic plugin info
	echo "<p>This plugin provides People Finder setup.</p>";

	// Begin form
	echo "<form action='options.php' method='post'>";

	// output settings fields - outputs required security fields - parameter specifes name of settings group
	settings_fields('gmuw_pf_options');

	// output setting sections - parameter specifies name of menu slug
	do_settings_sections('gmuw_pf');

	// submit button
	submit_button();

	// Close form
	echo "</form>";

	// Finish HTML output
	echo "</div>";
	
}

/**
 * Generates content for caching settings section
 */
function gmuw_pf_callback_section_settings_general() {

	// Get plugin options
	$gmuw_pf_options = get_option('gmuw_pf_options');

	// Provide section introductory information
	echo '<p>This section contains general settings.</p>';

}

/**
 * Generates text field for plugin settings option
 */
function gmuw_pf_callback_field_text($args) {
	
	//Get array of options. If the specified option does not exist, get default options from a function
	$options = get_option('gmuw_pf_options', gmuw_pf_options_default());
	
	//Extract field id and label from arguments array
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	
	//Get setting value
	$value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';
	
	//Output field markup
	echo '<input id="gmuw_pf_options_'. $id .'" name="gmuw_pf_options['. $id .']" type="text" size="40" value="'. $value .'">';
	echo "<br />";
	echo '<label for="gmuw_pf_options_'. $id .'">'. $label .'</label>';
	
}

/**
 * Sets default plugin options
 */
function gmuw_pf_options_default() {

	return array(
		'setting_one'   => '',
	);

}

/**
 * Validate plugin options
 */
function gmuw_pf_callback_validate_options($input) {
	
	// setting_one

	// Filter the resulting value as a clean URL
	//$input['cache_clear_url'] = filter_var($input['cache_clear_url'], FILTER_SANITIZE_URL);

	// Return value
	return $input;
	
}

/**
 * Generates the search page
 */
function gmuw_pf_display_page_admin_search() {

	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	echo '<br />';

	// building form
	echo '<form method="post" action="admin.php?page=gmuw_pf_admin_search" />';

	echo '<p>';
	echo '<select name="gmuw_term_id_building">';
	echo '<option value="">Select building...</option>';
	foreach (get_terms(array('taxonomy'=>'building','hide_empty'=>false)) as $term) {
		echo '<option value="'.$term->term_id.'"';
		if (isset($_POST['gmuw_term_id_building'])) {
			if ($term->term_id==$_POST['gmuw_term_id_building']) {
				echo ' selected';
			}
		}
		echo '>' . $term->name . '</option>';
	}
	echo '</select>';
	echo '</p>';

	//submit button
	echo '<p><button name="submit" type="submit" value="building" />Building Search</button></p>';

	echo '</form>';

	echo '<br />';

	// department form
	echo '<form method="post" action="admin.php?page=gmuw_pf_admin_search" />';

	echo '<p>';
	echo '<select name="gmuw_term_id_department">';
	echo '<option value="">Select department...</option>';
	foreach (get_terms(array('taxonomy'=>'department','hide_empty'=>false)) as $term) {
		echo '<option value="'.$term->term_id.'"';
		if (isset($_POST['gmuw_term_id_department'])) {
			if ($term->term_id==$_POST['gmuw_term_id_department']) {
				echo ' selected';
			}
		}		echo '>' . $term->name . '</option>';
	}
	echo '</select>';
	echo '</p>';

	//submit button
	echo '<p><button name="submit" type="submit" value="department" />Department Search</button></p>';

	echo '</form>';

	echo '<br />';

	//process form if submitted
	if (isset($_POST['submit'])) {

		//We have a form submission. Proceed...

		if ($_POST['submit']=='building') {
			echo '<h2>Results: Building</h2>';

			//assume no building
			$building_id='';

			// do we have a building?
			if (preg_match('/^-?\d+$/', $_POST['gmuw_term_id_building']) ) {
				$building_id = (int)$_POST['gmuw_term_id_building'];
			}

			if (empty($building_id)) {
				echo '<p>No building selected.</p>';
			} else {

				$building_name=get_term_by('id', $building_id, 'building')->name;

				echo '<p>Results for: '.$building_name.' ('.$building_id.')</p>';

				//get users
				$myusers = gmuw_pf_user_search_get_users('building',$building_id);

				//show results
				if (!$myusers) {
					echo '<p>No results</p>';
				} else {
					echo gmuw_pf_show_admin_users_search_results($myusers);
				}

			}

		}

		if ($_POST['submit']=='department') {
			echo '<h2>Results: Department</h2>';

			//assume no department
			$department_id='';

			// do we have a department?
			if (preg_match('/^-?\d+$/', $_POST['gmuw_term_id_department']) ) {
				$department_id = (int)$_POST['gmuw_term_id_department'];
			}

			if (empty($department_id)) {
				echo '<p>No department selected.</p>';
			} else {

				$department_name=get_term_by('id', $department_id, 'department')->name;

				echo '<p>Results for: '.$department_name.' ('.$department_id.')</p>';

				//get users
				$myusers = gmuw_pf_user_search_get_users('department',$department_id);

				//show results
				if (!$myusers) {
					echo '<p>No results</p>';
				} else {
					echo gmuw_pf_show_admin_users_search_results($myusers);
				}

			}

		}

	}

	// Finish HTML output
	echo "</div>";

}
