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

    // mason network status?
        add_shortcode('gmuw_pf_mason_network_status_message', 'gmuw_pf_mason_network_status_message');

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

function gmuw_pf_mason_network_status_message(){

  // are we in the Mason IP range?
  if (gmuw_pf_ip_in_mason_range($_SERVER['REMOTE_ADDR'])) {
    $content='<p>You are on the Mason network.</p>';
  } else {
    $content='<p>You are not on the Mason network.</p>';
  }

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

    $content.="<form name='pf-search-form'>";

    // Hidden fields
    //display mode
    $content.="<input type='hidden' name='gmuw_pf_display_mode' value='".$display_mode."'>";

    // Start form controls div
    $content.="<div class='pf-search-form-controls'>";

    // Start search controls div
    $content.="<div class='pf-search-controls'>";

    //name search
    $content.="<label for='input-gmuw_pf_search'>Name:</label>";
    $content.="<input type='text' name='gmuw_pf_search' id='input-gmuw_pf_search' placeholder='Search...' value='".$search."' />";

    //person type
    $content.="<label for='input-gmuw_pf_who'>Who:</label>";
    $content.="<select name='gmuw_pf_who' id='input-gmuw_pf_who'>";
    $content.="<option value='everyone'".($who=='everyone'?' selected':'').">Everyone</option>";
    $content.="<option value='facstaff'".($who=='facstaff'?' selected':'').">Faculty/Staff/Affiliates</option>";
    $content.="<option value='students'".($who=='students'?' selected':'').">Students Only</option>";
    $content.="<option value='departments'".($who=='departments'?' selected':'').">Departments Only</option>";
    $content.="</select>";

    //submit button
    $content.='<button type="submit" class="submit">Search</button>';

    // End search controls div
    $content.="</div>";

    // End form controls div
    $content.="</div>";

    $content.="</form>";

    $content.="</div>";


  //Return value
    return $content;

}

function gmuw_pf_results($atts = [], $content = null, $tag = ''){

  //Returns search results

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

  //if we don't we even have a search, return early
  if (!isset($_GET['gmuw_pf_search'])) {
    return;
  }

  //Build output
    $content.="<div class='pf-search-results'>";

  //Display debug info
    if ($display_debug_info) {
      $content.="<div class='gmuw-pf-debug-info'><p>";
      $content.="Display debug info: ".esc_html($gmuw_pf_atts['display_debug_info']." / ".$_GET['gmuw_pf_display_debug_info']. " / " . $display_debug_info) ."<br />";
      $content.="Display mode: ".esc_html($gmuw_pf_atts['display_mode']." / ".$_GET['gmuw_pf_display_mode']. " / " . $display_mode) ."<br />";
      $content.="Search string: ".esc_html($gmuw_pf_atts['search']." / ".$_GET['gmuw_pf_search']. " / " . $search) ."<br />";
      $content.="Who: ".esc_html($gmuw_pf_atts['who']." / ".$_GET['gmuw_pf_who']. " / " . $who) ."<br />";
      $content.="</p></div>";
    }

  //we have a search parameter, but it is empty
  if (empty($_GET['gmuw_pf_search'])) {
    $content .= '<p class="pf-search-results-note">No search term entered.</p>';
    $content .= '</div>';
    return $content;
  }

  //search results header
  $content .= '<h3>Results</h3>';

  $content .= '<p class="pf-search-results-note">';
  $content .= 'Your searched for &ldquo;<em>'.$search.'</em>&rdquo; in &ldquo;<em>'.$who.'</em>&rdquo;.';
  $content .= '</p>';

  //should we show faculty results?
  if ($who=='everyone' || $who=='facstaff') {

    $content .= '<h4>Faculty/Staff</h4>';

    //get users who match this search term...

    //begin to define define get_users meta query array
    $users_meta_query_array=array();
    //its going to be an OR
    $users_meta_query_array['relation']= 'OR';
    //between whether the search text matches the search key
    $users_meta_query_array[]=array(
      'key' => 'pf_search_key',
      'value' => $search,
      'compare' => 'LIKE'
    );
    //print_r($users_meta_query_array);

    /* --disable this section if search performance is too bad-- */
    //...and get any departments the names of which match the search term, and the ids of which occur in any of the user meta fields which link to departments...

    //get the ID numbers of departments taxonomy terms whose names match this search term
    $my_department_result_term_ids = get_terms([
        'taxonomy' => 'department',
        'hide_empty' => false,
        'name__like' => $search,
        'fields' => 'ids'

    ]);
    //print_r($my_department_result_term_ids);

    //do we have matching department taxonomy terms?
    if ($my_department_result_term_ids) {

      //loop through the matching department IDs
      foreach($my_department_result_term_ids as $my_department_result_term_id) {

        //add items to the meta query array for each field which could have a department ID
        $users_meta_query_array[]=array(
          'key' => 'pf_affiliation_approved',
          'value' => $my_department_result_term_id,
          'compare' => 'LIKE'
        );
        $users_meta_query_array[]=array(
          'key' => 'pf_affiliation_2_approved',
          'value' => $my_department_result_term_id,
          'compare' => 'LIKE'
        );
        $users_meta_query_array[]=array(
          'key' => 'pf_department_approved',
          'value' => $my_department_result_term_id,
          'compare' => 'LIKE'
        );
        $users_meta_query_array[]=array(
          'key' => 'pf_department_2_approved',
          'value' => $my_department_result_term_id,
          'compare' => 'LIKE'
        );

      }

    }
    //echo '<pre>';
    //print_r($users_meta_query_array);
    //echo '</pre>';
    /* --end section to disable if search performance is too bad-- */

    //get users
    $myusers = get_users(
      array(
        'role' => 'subscriber',
        'meta_query' => $users_meta_query_array,
      )
    );

    //loop through users
    foreach ($myusers as $myuser) {

      $content.='<p>';

      //$content.='ID: ' . $myuser->ID . '<br />';

      $content.='<span class="pf-search-results-name">' . $myuser->pf_name . '</span><br />';

      if (!empty($myuser->pf_title_approved)) {
        $content.=$myuser->pf_title_approved . '<br />';
      }

      if (!empty($myuser->pf_department_approved)) {
        $content.=get_term_by('id', $myuser->pf_department_approved, 'department')->name . '<br />';
      }

      if (!empty($myuser->pf_affiliation_approved)) {
        $content.=get_term_by('id', $myuser->pf_affiliation_approved, 'department')->name . '<br />';
      }

      //location
      if (!empty($myuser->pf_room_approved) || !empty($myuser->pf_building_approved) || !empty($myuser->pf_mailstop_approved)) {
        if (!empty($myuser->pf_room_approved)) {
          $content.=$myuser->pf_room_approved . ' ';
        }
        if (!empty($myuser->pf_building_approved)) {
          $content.=get_term_by('id', $myuser->pf_building_approved, 'building')->name;
        }
        if (!empty($myuser->pf_mailstop_approved)) {
          $content.=', MSN '.$myuser->pf_mailstop_approved;
        }
        $content.='<br />';
      }

      if (!empty($myuser->pf_title_2_approved)) {
        $content.='<br />';
        $content.=$myuser->pf_title_2_approved . '<br />';
      }

      if (!empty($myuser->pf_department_2_approved)) {
        $content.=get_term_by('id', $myuser->pf_department_2_approved, 'department')->name . '<br />';
      }

      if (!empty($myuser->pf_affiliation_2_approved)) {
        $content.=get_term_by('id', $myuser->pf_affiliation_2_approved, 'department')->name . '<br />';
      }

      //location2
      if (!empty($myuser->pf_room_2_approved) || !empty($myuser->pf_building_2_approved) || !empty($myuser->pf_mailstop_2_approved)) {
        if (!empty($myuser->pf_room_2_approved)) {
          $content.=$myuser->pf_room_2_approved . ' ';
        }
        if (!empty($myuser->pf_building_2_approved)) {
          $content.=get_term_by('id', $myuser->pf_building_2_approved, 'building')->name;
        }
        if (!empty($myuser->pf_mailstop_2_approved)) {
          $content.=', MSN '.$myuser->pf_mailstop_2_approved;
        }
        $content.='<br />';
      }

      if (!empty($myuser->pf_phone_approved)) {
          $content.='Phone: ' . $myuser->pf_phone_approved . '<br />';
      }

      if (!empty($myuser->pf_fax_approved)) {
        $content.='Fax: ' . $myuser->pf_fax_approved . '<br />';
      }

      // if we are in the Mason IP range, show the email address
      if (gmuw_pf_ip_in_mason_range($_SERVER['REMOTE_ADDR'])) {
        if (!empty($myuser->pf_email_approved)) {
          $content.='Email: <a href="mailto:'.$myuser->pf_email_approved.'@gmu.edu">' . $myuser->pf_email_approved . '@gmu.edu</a><br />';
        }
      }

      if (!empty($myuser->pf_pronouns_approved)) {
        $content.='Pronouns: ' . $myuser->pf_pronouns_approved . '<br />';
      }

      $content.='</p>';

    }

  }

  //should we show department results?
  if ($who=='everyone' || $who=='departments') {

    $content .= '<h4>Departments</h4>';

    //get posts which match this search term
    $myposts = get_posts(
      array(
        'post_type'  => 'department',
        'meta_query' => array(
          array(
            'key' => 'search_key',
            'value' => $search,
            'compare' => 'LIKE'
          )
        )
      )
    );

    //loop through posts
    foreach ($myposts as $mypost) {

      $content.='<p>';

      //$content.='ID: ' . $mypost->ID . '<br />';

      $content.='<span class="pf-search-results-name">' . $mypost->post_title . '</span><br />';

      if (!empty($mypost->acronym)) {
        $content.=$mypost->acronym . '<br />';
      }


      //location
      if (!empty($mypost->room_number) || !empty($mypost->building) || !empty($mypost->pf_mailstop_approved)) {
        if (!empty($mypost->room_number)) {
          $content.=$mypost->room_number . ' ';
        }
        if (!empty($mypost->building_id)) {
            if (ctype_digit($mypost->building_id)) {
                $content.=get_term($mypost->building_id)->name;
            }
        }
        if (!empty($mypost->mail_stop_number)) {
          $content.=', MSN '.$mypost->mail_stop_number;
        }
        $content.='<br />';
      }

      if (!empty($mypost->phone_number)) {
          $content.='Phone: ' . $mypost->phone_number . '<br />';
      }

      if (!empty($mypost->fax_number)) {
        $content.='Fax: ' . $mypost->fax_number . '<br />';
      }

      // if we are in the Mason IP range, show the email address
      if (gmuw_pf_ip_in_mason_range($_SERVER['REMOTE_ADDR'])) {
        if (!empty($mypost->contact_email)) {
          $content.='Email: <a href="mailto:'.$mypost->contact_email.'">' . $mypost->contact_email . '</a><br />';
        }
      }

      $content.='</p>';

    }

  }

  //should we show student results?
  if ($who=='everyone' || $who=='students') {

    $content .= '<h4>Students</h4>';

    //get globals
    global $wpdb;

    //set student table name
    $student_table_name = $wpdb->prefix . 'gmuw_pf_students';

    //get student results
    $student_results = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$student_table_name." WHERE student_name LIKE '%".$search."%' ORDER BY student_name;" ) );

    //do we have student reults?
    if ( $student_results ) {

      //loop through student results
      foreach($student_results as $student_result){

        $content.='<p>';

        $content.='<span class="pf-search-results-name">' . $student_result->student_name . '</span><br />';

        // if we are in the Mason IP range, show the email address
        if (gmuw_pf_ip_in_mason_range($_SERVER['REMOTE_ADDR'])) {
          if (!empty($student_result->student_email)) {
            $content.='Email: <a href="mailto:'.$student_result->student_email.'@gmu.edu">'.$student_result->student_email . '@gmu.edu</a><br />';
          }
        }

        if (!empty($student_result->student_major)) {
          $content.='Major: '.$student_result->student_major . '<br />';
        }

        /*
        if (!empty($student_result->student_phone_number)) {
          $content.=$student_result->student_phone_number . '<br />';
        }
        */

        if (!empty($student_result->student_pronouns)) {
          $content.='Pronouns: '.$student_result->student_pronouns . '<br />';
        }

        $content.='</p>';

      }

    }

  }

  //finish output
  $content.="</div>";

  //Return value
  return $content;

}
