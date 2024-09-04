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

	// recently_updated form
	echo '<form method="get">';
	echo '<input name="page" value="gmuw_pf_admin_search" type="hidden" />';

	//submit button
	echo '<p><button name="submit" type="submit" value="recently_updated" />Recently Updated</button></p>';

	echo '</form>';

	echo '<br />';

	// building form
	echo '<form method="get">';
	echo '<input name="page" value="gmuw_pf_admin_search" type="hidden" />';

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
	echo '<form method="get">';
	echo '<input name="page" value="gmuw_pf_admin_search" type="hidden" />';

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

	// kioskreport_facstaff report form
	echo '<form method="get">';
	echo '<input name="page" value="gmuw_pf_admin_search" type="hidden" />';
	echo '<input name="gmuw_pf_page" value="1" type="hidden" />';

	//submit button
	echo '<p><button name="submit" type="submit" value="kioskreport_facstaff" />Kiosk Report - Faculty/Staff</button></p>';

	echo '</form>';

	echo '<br />';

	// kioskreport_departments report form
	echo '<form method="get">';
	echo '<input name="page" value="gmuw_pf_admin_search" type="hidden" />';

	//submit button
	echo '<p><button name="submit" type="submit" value="kioskreport_departments" />Kiosk Report - Departments</button></p>';

	echo '</form>';

	echo '<br />';

	//process form if submitted
	if (isset($_GET['submit'])) {

		//We have a form submission. Proceed...

		if ($_GET['submit']=='recently_updated') {
			echo '<h2>Results: Recently Updated (Top 300 Most Recent)</h2>';

			//get users
			$myusers = gmuw_pf_user_search_get_users('recently_updated');

			//show results
			if (!$myusers) {
				echo '<p>No results</p>';
			} else {
				echo gmuw_pf_show_admin_users_search_results($myusers);
			}


		}

		if ($_GET['submit']=='building') {
			echo '<h2>Results: Building</h2>';

			//assume no building
			$building_id='';

			// do we have a building?
			if (preg_match('/^-?\d+$/', $_GET['gmuw_term_id_building']) ) {
				$building_id = (int)$_GET['gmuw_term_id_building'];
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

		if ($_GET['submit']=='department') {
			echo '<h2>Results: Department</h2>';

			//assume no department
			$department_id='';

			// do we have a department?
			if (preg_match('/^-?\d+$/', $_GET['gmuw_term_id_department']) ) {
				$department_id = (int)$_GET['gmuw_term_id_department'];
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

		if ($_GET['submit']=='kioskreport_facstaff') {
			echo '<h2>Report: Faculty/Staff</h2>';

			echo gmuw_pf_kioskreport_facstaff();

		}

		if ($_GET['submit']=='kioskreport_departments') {
			echo '<h2>Report: Departments</h2>';

			echo gmuw_pf_kioskreport_departments();

		}

	}

	// Finish HTML output
	echo "</div>";

}

/**
 * Generates the plugin regenerate user search keys page
 */
function gmuw_pf_display_page_admin_user_search_keys() {

	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	// Output basic plugin info
	echo "<p>This plugin provides People Finder setup.</p>";

	//loop through all records and generate and save search keys

    //get users
    $myusers = get_users(
      array(
        'role' => 'subscriber',
        'number' => 500,
        'offset' => 0, //increase this to do batches
        'paged' => 1,
        'orderby' => 'ID',
      )
    );

    //set list of people finder fields
    $pf_fields = gmuw_pf_custom_fields_array();

    //set array of fields not to include in search key
    $exclude_from_search_key=array(
        'pf_building',
        'pf_room',
        'pf_building_2',
        'pf_room_2',
        'pf_pronouns',
    );

    //loop through users
    foreach ($myusers as $myuser) {
		//initialize variables
		$search_key_value='';

		//save user to update search key
		//echo "<p>".$myuser->ID."</p>";

        //generate search key field
        foreach ($pf_fields as $pf_field) {
            //should we include this field in the search key?
            if (!in_array($pf_field[1],$exclude_from_search_key)) {
                //add to search key based on type of field
                switch ($pf_field[1]) {
                    case 'pf_name':
                        //add approved name field value in a couple formate
                        $myname=get_user_meta($myuser->ID, $pf_field[1].'_approved', true );
                        $myname_normalized_array=explode(",", $myname);
                        $myname_normalized=trim($myname_normalized_array[1]) . ' ' . trim($myname_normalized_array[0]);
                        $search_key_value .= $myname . ' ';
                        $search_key_value .= $myname_normalized . ' ';
						break;
                    case 'pf_mailstop':
                    case 'pf_mailstop_2':
                        //if not empty, add approved field value with MSN prefix
                        if (!empty(get_user_meta($myuser->ID, $pf_field[1].'_approved', true ))) {
                            $search_key_value .= 'MSN'.get_user_meta($myuser->ID, $pf_field[1].'_approved', true ) . ' ' . 'MSN '.get_user_meta($myuser->ID, $pf_field[1].'_approved', true ) . ' ';
                        }
                        break;
                    case 'pf_department':
                    case 'pf_department_2':
                    case 'pf_affiliation':
                    case 'pf_affiliation_2':
                        //get department term name
                        $department_term_name = get_term(get_user_meta($myuser->ID, $pf_field[1].'_approved', true ))->name;
                        //add approved field value
                        $search_key_value .= $department_term_name . ' ';
                        break;
                    default:
                        //add approved field value
                        $search_key_value .= get_user_meta($myuser->ID, $pf_field[1].'_approved', true ) . ' ';
                        break;

                }
            }
        }
        //output
        echo "<p>".$myuser->ID.": ".$search_key_value."</p>";

        //save search key field
        update_user_meta( $myuser->ID, 'pf_search_key', $search_key_value );


    }

	// Finish HTML output
	echo "</div>";

}

function gmuw_pf_kioskreport_facstaff(){

  //Returns kiosk report results

  //Get global variables
    global $wpdb;

  //Initialize output variable
    $content="";

  //Build output
    $content.="<div class='pf-report-results'>";

  //get users

  //get page
  $page = 1;
  if (isset($_GET['gmuw_pf_page']) && (int)$_GET['gmuw_pf_page']>1) {
    $page = (int)$_GET['gmuw_pf_page'];
  }

  //get users
  $myusers = get_users(
    array(
      'number' => 2000,
      'orderby' => 'meta_value',
      'order' => 'ASC',
      'meta_key' => 'pf_name',
      'paged' => $page,
    )
  );

  //do we have users to display?
  if ($myusers) {

    //total users
    //$content.='<p>Total users: '.sizeof($myusers).'</p>';

    //pagination
    $content .= '<p>';
    //prev
    if ($page>1) {
      $content .= '<a href="'.preg_replace('/gmuw_pf_page=\d{1,}/i','gmuw_pf_page='.$page-1,$_SERVER['REQUEST_URI']).'"><< prev</a>';
      $content .= ' | ';
    }
    //next
    $content .= '<a href="'.preg_replace('/gmuw_pf_page=\d{1,}/i','gmuw_pf_page='.$page+1,$_SERVER['REQUEST_URI']).'">next >></a>';
    $content .= '</p>';

		//show results
		$content .= gmuw_pf_show_admin_users_search_results($myusers,true);

  } else {
    $content.='<p>No records to display</p>';
  }

  //finish output
  $content.="</div>";

  //Return value
  return $content;

}

function gmuw_pf_kioskreport_departments(){

  //Returns kiosk report results

  //Get global variables
    global $wpdb;

  //Initialize output variable
    $content="";

  //Build output
    $content.="<div class='pf-report-results'>";

	//get posts
	$myposts = get_posts(
	  array(
	    'numberposts' => -1,
	    'post_type'  => 'department',
	    'order' => 'ASC',
	    'orderby' => 'title',
	  )
	);

	//do we have posts?
	if ($myposts) {

		//begin table
		$content.='<table class="data_table">';

		//table header
		$content.='<thead>';
		$content.='<tr>';
		$content.='<th>post_id</th>';
		$content.='<th>department</th>';
		$content.='<th>acronym</th>';
		$content.='<th>room</th>';
		$content.='<th>building</th>';
		$content.='<th>mailstop</th>';
		$content.='<th>phone</th>';
		$content.='<th>fax</th>';
		$content.='<th>email</th>';
		$content.='</tr>';
		$content.='</thead>';

		//table body
		$content.='<tbody>';

	  //loop through posts
	  foreach ($myposts as $mypost) {

			//output data row
	    $content.='<tr>';
	    $content.='<td>' . $mypost->ID . '</td>';
	    $content.='<td>' . sanitize_text_field($mypost->post_title) . '</td>';
	    $content.='<td>' . sanitize_text_field($mypost->acronym) . '</td>';
			$content.='<td>' . sanitize_text_field($mypost->room_number) . '</td>';
			$content.='<td>' . sanitize_text_field(get_term($mypost->building_id)->name) . '</td>';
			$content.='<td>' . sanitize_text_field($mypost->mail_stop_number) . '</td>';
			$content.='<td>' . gmuw_pf_format_phone_number(sanitize_text_field($mypost->phone_number)) . '</td>';
			$content.='<td>' . gmuw_pf_format_phone_number(sanitize_text_field($mypost->fax_number)) . '</td>';
			$content.='<td>' . sanitize_email($mypost->contact_email) . '</a></td>';
	    $content.='</tr>';

	  }

		$content.='</tbody>';

		//end table
		$content.='</table>';

  }

  //finish output
  $content.="</div>";

  //Return value
  return $content;

}
