<?php

/**
 * Summary: php file which implements the custom shortcodes
 */


// Add shortcodes on init
add_action('init', function(){

    // Add shortcodes. Add additional shortcodes here as needed.

    // Add example shortcode
    add_shortcode(
        'gmuw_pf_shortcode', //shortcode label (use as the shortcode on the site)
        'gmuw_pf_shortcode' //callback function
    );

    // peoplefinder search
        add_shortcode('gmuw_pf_search_form', 'gmuw_pf_search_form');

    // peoplefinder results
        add_shortcode('gmuw_pf_results', 'gmuw_pf_results');

});

// Define shortcode callback functions. Add additional shortcode functions here as needed.

// example shortcode
function gmuw_pf_shortcode(){

    // Determine return value
    $content='People Finder shortcode';

    // Return value
    return $content;

}

function gmuw_pf_search_form($atts = [], $content = null, $tag = ''){

  //Returns search form

  //Get global variables
    global $wpdb;

  // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );

  // override default attributes with user attributes
    $gmuw_pf_atts = shortcode_atts(
      array(
          'display_debug_info' => false,
          'display_mode' => 'list',
          'search' => '',
          'who' => '',
      ), $atts, $tag
    );

  // Set variables. If variables are specified in query string, use them. or else get values from shortcode
    if(isset($_GET['gmuw_pf_display_debug_info']) && $_GET['gmuw_pf_display_debug_info']!=''){
      $display_debug_info=$_GET['gmuw_pf_display_debug_info'];
    } else {
      $display_debug_info=$gmuw_pf_atts['display_debug_info'];
    }
    if(isset($_GET['gmuw_pf_display_mode']) && $_GET['gmuw_pf_display_mode']!=''){
      $display_mode=$_GET['gmuw_pf_display_mode'];
    } else {
      $display_mode=$gmuw_pf_atts['display_mode'];
    }
    if(isset($_GET['gmuw_pf_search']) && $_GET['gmuw_pf_search']!=''){
      $search=$_GET['gmuw_pf_search'];
    } else {
      $search=$gmuw_pf_atts['search'];
    }
    if(isset($_GET['gmuw_pf_who']) && $_GET['gmuw_pf_who']!=''){
      $who=$_GET['gmuw_pf_who'];
    } else {
      $who=$gmuw_pf_atts['who'];
    }

  // Fix boolean attributes
    // convert literal string 'false' to boolean false, so that it doesn't evaluate to true (being non-empty)
    if ($display_debug_info=='false') $display_debug_info=false;

  //Initialize output variable
    $content="";

  //Build output
    $content.="<div class='pf-search-form'>";

  //Display debug info
    if ($display_debug_info) {
      $content.="<div class='gmuw-pf-debug-info'><p>";
      $content.="Display debug info: ".esc_html($gmuw_pf_atts['display_debug_info']." / ".$_GET['gmuw_pf_display_debug_info']. " / " . $display_debug_info) ."<br />";
      $content.="Display mode: ".esc_html($gmuw_pf_atts['display_mode']." / ".$_GET['gmuw_pf_display_mode']. " / " . $display_mode) ."<br />";
      $content.="Search string: ".esc_html($gmuw_pf_atts['search']." / ".$_GET['gmuw_pf_search']. " / " . $search) ."<br />";
      $content.="Who: ".esc_html($gmuw_pf_atts['who']." / ".$_GET['gmuw_pf_who']. " / " . $who) ."<br />";
      $content.="</p></div>";
    }

    $content.="<form name='event-filter-form'>";

    // Hidden fields
    //display mode
    $content.="<input type='hidden' name='gmuw_pf_display_mode' value='".$display_mode."'>";

    // Start form controls div
    $content.="<div class='pf-search-form-controls'>";

    // Start search controls div
    $content.="<div class='pf-search-controls'>";

    //calendars
    $content.="<label for='input-gmuw_pf_who'>Who:</label>";
    $content.="<select name='gmuw_pf_who' id='input-gmuw_pf_who'>";
    $content.="<option value='everyone'>Everyone</option>";
    $content.="<option value='facstaff'>Faculty/Staff/Affiliates</option>";
    $content.="<option value='students'>Students Only</option>";
    $content.="</select>";

    //name search
    $content.="<label for='input-gmuw_pf_search'>Name:</label>";
    $content.="<input type='text' name='gmuw_pf_search' id='input-gmuw_pf_search' placeholder='SEARCH' value='".$search."' />";

    //submit button
    $content.='<button type="submit" class="submit">Search</button>';

    // End search controls div
    $content.="</div>";

    /*
    // Start view controls div
    $content.="<div class='view-controls'>";

    //view type buttons (include class of active if appropriate based on calendar display mode)
    $content.="<button class='view-selector".($display_mode=='grid' ? ' active' : '')."' id='grid-view'>GRID VIEW</button>";
    $content.="<button class='view-selector".($display_mode=='list' ? ' active' : '')."' id='list-view'>LIST VIEW</button>";

    // End view controls div
    $content.="</div>";
	*/

    // End form controls div
    $content.="</div>";

    $content.="</form>";

    $content.="</div>";


  //Return value
    return $content;

}

function gmuw_pf_results($atts = [], $content = null, $tag = ''){

  //Returns search form

  //Get global variables
    global $wpdb;

  // normalize attribute keys, lowercase
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );

  // override default attributes with user attributes
    $gmuw_pf_atts = shortcode_atts(
      array(
          'display_debug_info' => false,
          'display_mode' => 'list',
          'search' => '',
          'who' => '',
      ), $atts, $tag
    );

  // Set variables. If variables are specified in query string, use them. or else get values from shortcode
    if(isset($_GET['gmuw_pf_display_debug_info']) && $_GET['gmuw_pf_display_debug_info']!=''){
      $display_debug_info=$_GET['gmuw_pf_display_debug_info'];
    } else {
      $display_debug_info=$gmuw_pf_atts['display_debug_info'];
    }
    if(isset($_GET['gmuw_pf_display_mode']) && $_GET['gmuw_pf_display_mode']!=''){
      $display_mode=$_GET['gmuw_pf_display_mode'];
    } else {
      $display_mode=$gmuw_pf_atts['display_mode'];
    }
    if(isset($_GET['gmuw_pf_search']) && $_GET['gmuw_pf_search']!=''){
      $search=$_GET['gmuw_pf_search'];
    } else {
      $search=$gmuw_pf_atts['search'];
    }
    if(isset($_GET['gmuw_pf_who']) && $_GET['gmuw_pf_who']!=''){
      $who=$_GET['gmuw_pf_who'];
    } else {
      $who=$gmuw_pf_atts['who'];
    }

  // Fix boolean attributes
    // convert literal string 'false' to boolean false, so that it doesn't evaluate to true (being non-empty)
    if ($display_debug_info=='false') $display_debug_info=false;

  //Initialize output variable
    $content="";

  //Build output
    $content.="<div class='pf-results'>";

  //Display debug info
    if ($display_debug_info) {
      $content.="<div class='gmuw-pf-debug-info'><p>";
      $content.="Display debug info: ".esc_html($gmuw_pf_atts['display_debug_info']." / ".$_GET['gmuw_pf_display_debug_info']. " / " . $display_debug_info) ."<br />";
      $content.="Display mode: ".esc_html($gmuw_pf_atts['display_mode']." / ".$_GET['gmuw_pf_display_mode']. " / " . $display_mode) ."<br />";
      $content.="Search string: ".esc_html($gmuw_pf_atts['search']." / ".$_GET['gmuw_pf_search']. " / " . $search) ."<br />";
      $content.="Who: ".esc_html($gmuw_pf_atts['who']." / ".$_GET['gmuw_pf_who']. " / " . $who) ."<br />";
      $content.="</p></div>";
    }

    //search results header
    //search term
    $content.="<p>Search: ".$search."</p>";
    //who
    $content.="<p>Who: ".$who."</p>";

    //get users who match this search term
	$myusers = get_users(
	    array(
	        'role' => 'subscriber',

			'meta_query' => array(
			    array(
			        'key' => 'pf_search_key',
			        'value' => $search,
			        'compare' => 'LIKE'
			    )
			)
	        
	    )
	);

	//loop through users
	foreach ($myusers as $myuser) {
		$content.='<p>';
		$content.='ID: ' . $myuser->ID . '<br />';
		$content.='Name: ' . $myuser->pf_name . '<br />';
		$content.='Title: ' . $myuser->pf_title . '<br />';
		$content.='Phone: ' . $myuser->pf_phone . '<br />';
		$content.='Fax: ' . $myuser->pf_fax . '<br />';
		$content.='</p>';
	}

  //Return value
    return $content;

}
