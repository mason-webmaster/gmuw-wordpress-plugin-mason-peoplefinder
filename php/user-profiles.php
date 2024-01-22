<?php

/**
 * Summary: php file which modifies the user profile system
 */


/**
 * Removes ability to change Theme color for the users
 */
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

/**
 * add custom css to the admin profile page to hide some fields
 * https://wordpress.stackexchange.com/questions/94963/removing-website-field-from-the-contact-info
 */
add_action( 'admin_head-user-edit.php', 'gmuw_pf_admin_user_profile_screen_custom_css' );
add_action( 'admin_head-profile.php',   'gmuw_pf_admin_user_profile_screen_custom_css' );
function gmuw_pf_admin_user_profile_screen_custom_css() {
    echo '<style>';
    echo 'tr.user-admin-color-wrap{ display: none; }';
    echo 'tr.user-admin-bar-front-wrap{ display: none; }';
    echo 'tr.user-first-name-wrap{ display: none; }';
    echo 'tr.user-last-name-wrap{ display: none; }';
    echo 'tr.user-nickname-wrap{ display: none; }';
    echo 'tr.user-display-name-wrap{ display: none; }';
    echo 'tr.user-description-wrap{ display: none; }';
    echo 'tr.user-profile-picture{ display: none; }';
    echo 'tr.user-url-wrap{ display: none; }';
    echo 'tr.user-sessions-wrap{ display: none; }';
    echo 'div.create-application-password{ display: none; }';
    echo '</style>';
    
}


/**
 * Remove fields from Admin profile page
 * https://wordpress.stackexchange.com/questions/397816/wordpress-5-8-hide-or-remove-personal-fields-from-admin-profile-page
 */

// Remove fields from Admin profile page
add_action( 'admin_head', 'gmuw_pf_profile_subject_start' );
function gmuw_pf_profile_subject_start() {
    //if ( ! current_user_can('manage_options') ) {
        ob_start( 'gmuw_pf_remove_personal_options' );
    //}
}

function gmuw_pf_remove_personal_options( $subject ) {
    $subject = preg_replace('#<h2>'.__("Personal Options").'</h2>#s', '', $subject, 1); // Remove the "Personal Options" title
    /*
    $subject = preg_replace('#<tr class="user-rich-editing-wrap(.*?)</tr>#s', '', $subject, 1); // Remove the "Visual Editor" field
    $subject = preg_replace('#<tr class="user-comment-shortcuts-wrap(.*?)</tr>#s', '', $subject, 1); // Remove the "Keyboard Shortcuts" field
    $subject = preg_replace('#<tr class="show-admin-bar(.*?)</tr>#s', '', $subject, 1); // Remove the "Toolbar" field
    $subject = preg_replace('#<h2>'.__("Name").'</h2>#s', '', $subject, 1); // Remove the "Name" title
    // $subject = preg_replace('#<tr class="user-display-name-wrap(.*?)</tr>#s', '', $subject, 1); // "Display name publicly as" field (not working ok when editing user. Better remove and manage with "Force First and Last Name as Display Name" plugin
    $subject = preg_replace('#<h2>'.__("Contact Info").'</h2>#s', '', $subject, 1); // Remove the "Contact Info" title
    $subject = preg_replace('#<tr class="user-url-wrap(.*?)</tr>#s', '', $subject, 1); // Remove the "Website" field in EDIT USER
    $subject = preg_replace('#<th scope="row"><label for="url(.*?)</th>#s', '', $subject, 1); // Remove the "Website" field in NEW USER (part 1)
    $subject = preg_replace('#<td><input name="url"(.*?)</td>#s', '', $subject, 1); // Remove the "Website" field SUKINOZ in NEW USER (part 2)  
    */
    $subject = preg_replace('#<h2>'.__("About Yourself").'</h2>#s', '', $subject, 1); // Remove the "About Yourself" title
    $subject = preg_replace('#<h2>'.__("Application Passwords").'</h2>#s', '', $subject, 1); // Remove the "Application Passwords" title
    $subject = preg_replace('#<p>'.__("Application passwords allow authentication via non-interactive systems, such as XML-RPC or the REST API, without providing your actual password. Application passwords can be easily revoked. They cannot be used for traditional logins to your website.").'</p>#s', '', $subject, 1); // Remove the "Application Passwords" title
    $subject = preg_replace('#<h2>'.__("About the user").'</h2>#s', '', $subject, 1); // Remove the "About the user" title      
    /*
    $subject = preg_replace('#<tr class="user-description-wrap(.*?)</tr>#s', '', $subject, 1); // Remove the "Biographical Info" field
    $subject = preg_replace('#<tr class="user-profile-picture(.*?)</tr>#s', '', $subject, 1); // Remove the "Profile Picture" field
    */
    return $subject;
}

add_action( 'admin_footer', 'gmuw_pf_profile_subject_end' );
function gmuw_pf_profile_subject_end() {
    //if ( ! current_user_can('manage_options') ) {
        ob_end_flush();
    //}
}

function gmuw_pf_get_custom_usermeta($user) {

	//initialize variables
	$pf_field_values = [];

	//get field values
	$pf_field_values['title'] = array(
		get_user_meta( $user->ID, 'pf_title', true ),
		get_user_meta( $user->ID, 'pf_title_approved', true )
	);

	//return
	return $pf_field_values;

}

function gmuw_pf_field_is_approved($user_id,$user_meta_key) {

	//initialize variables
	$return_value = false;

	//get field values
	$user_field = get_user_meta( $user_id, $user_meta_key, true );
	$user_field_approved = get_user_meta( $user_id, $user_meta_key.'_approved', true );

	//do we have an approved field value?
	if (!empty($user_field_approved)) {
		//do the fields match?
		if ($user_field==$user_field_approved) { $return_value = true; }
	}

	//return
	return $return_value;

}

//set up master aray of custom peoplefinder fields
function gmuw_pf_custom_fields_array() {

    //set up array
    $pf_fields = array(
        array('pf_name', 'Name', 'Please enter your name as you would like it to appear.'),
        array('pf_title', 'Title', 'Please enter your title.'),
        array('pf_affiliation', 'Affiliation', 'Please enter your professional affiliation.'),
        array('pf_building', 'Building', 'Your building.'),
        array('pf_room', 'Room', 'Your room number.'),
        array('pf_mailstop', 'Mailstop', 'Your mailstop number (MSN).'),
        array('pf_phone', 'Phone Number', 'Your phone number.'),
        array('pf_fax', 'Fax Number', 'Your fax number.'),
        array('pf_email', 'Email Address/NetID', 'Just the part before the gmu.edu, please.'),
        array('pf_pronouns', 'Pronouns', 'Your preferred pronouns.'),
    );

    //return value
    return $pf_fields;

}


add_action( 'show_user_profile', 'gmuw_pf_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'gmuw_pf_extra_user_profile_fields' );

function gmuw_pf_extra_user_profile_fields($user) { 
	
	echo '<h3>People Finder Information</h3>';

    echo '<table class="form-table">';
    
    echo '<a class="pf_approve_all button-primary" href="#">Approve All &#10003;</a>';
    echo '&nbsp;';
    echo '<a class="pf_disapprove_all button-primary" href="#">&#10006; Disapprove All</a>';

    //set list of fields and data
    $pf_fields = gmuw_pf_custom_fields_array();

    //output fields
    foreach ($pf_fields as $pf_field) {
        echo gmuw_pf_user_profile_people_finder_field($user,$pf_field[0], $pf_field[1], $pf_field[2]);
    }

    echo '</table>';
	
}

// return user profile screen people finder field
function gmuw_pf_user_profile_people_finder_field($user,$field_name, $field_title, $field_desc) {

    //initialize variables
    $return_value='';

    //pf_title
    $return_value.='<tr>';
    $return_value.='<th><label for="'.$field_name.'">'.$field_title.'</label></th>';
    $return_value.='<td>';

    $return_value.='<table class="pf-profile-layout">';
    $return_value.='<tr>';
    
    $return_value.='<td>';
    //user-entered field
    $return_value.='<input type="text" name="'.$field_name.'" id="'.$field_name.'" value="' . esc_attr( get_user_meta( $user->ID, $field_name, true ) ) . '" class="regular-text" /><br />';
    $return_value.='<p><span class="description">'.$field_desc.'</span></p>';
    //approved hidden field
    if (current_user_can('manage_options')) {
        $return_value.='<input type="hidden" name="'.$field_name.'_approved" id="'.$field_name.'_approved" value="' . esc_attr( get_user_meta( $user->ID, ''.$field_name.'_approved', true ) ) . '" class="regular-text" />';
    }
    $return_value.='</td>';

    $return_value.='<td>';
    //are we a regular user?
    if (!current_user_can('manage_options')) {
        //do we have a value in this field?
        if (!empty(get_user_meta( $user->ID, $field_name, true ))) {
            //output message about the status of this field based on whether it is approved
            $return_value.=gmuw_pf_field_is_approved($user->ID,$field_name) ? '<span class="pf-field-status pf-field-status-approved">&#10003; Approved</span>' : '<span class="pf-field-status pf-field-status-pending">Pending approval</span>';
        }
    }
    //are we an admin?
    if (current_user_can('manage_options')) {
        //is this field approved
        if (gmuw_pf_field_is_approved($user->ID,$field_name)) { 
            $return_value.='<p>';
            $return_value.='<span class="pf-field-status pf-field-status-approved">&#10003; Approved</span>';
            $return_value.='<a class="pf_disapprove button-primary" data-pf-field="'.$field_name.'" href="#">&#10006; Disapprove</a>';
            $return_value.='</p>';
        } else {
            $return_value.='<p>';
            if (!empty(get_user_meta( $user->ID, $field_name, true ))) {
                $return_value.='<span class="pf-field-status pf-field-status-pending">Not approved</span>';
                $return_value.='<a class="pf_approve button-primary" data-pf-field="'.$field_name.'" href="#">Mark for Approval &#10003;</a>';
            }
            $return_value.='</p>';
        }       
    }    

    $return_value.='</td>';

    $return_value.='</tr>';
    $return_value.='</table>';


    $return_value.='</td>';
    $return_value.='</tr>';

    //return value
    return $return_value;

}

add_action( 'personal_options_update', 'gmuw_pf_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'gmuw_pf_save_extra_user_profile_fields' );

function gmuw_pf_save_extra_user_profile_fields( $user_id ) {

    //check for nonce
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    //check for capabilities
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }

    //set list of people finder fields
    $pf_fields = gmuw_pf_custom_fields_array();

    //save fields
    foreach ($pf_fields as $pf_field) {
        gmuw_pf_save_extra_user_profile_field($user_id, $pf_field[0]);
    }

    //handle search key field
    //only if we're an admin
    if (current_user_can('manage_options')) {

        //initialize variables
        $search_key_value='';
        
        //set array of fields not to include in search key
        $exclude_from_search_key=array(
            'pf_affiliation',
            'pf_building',
            'pf_room',
            'pf_mailstop',
            'pf_pronouns',
        );
        
        //generate search key field
        foreach ($pf_fields as $pf_field) {
            //should we include this field in the search key?
            if (!in_array($pf_field[0],$exclude_from_search_key)) {
                //add approved field value
                $search_key_value .= $_POST[$pf_field[0].'_approved'] . ' ';    
            }
        }
        
        //save search key field
        update_user_meta( $user_id, 'pf_search_key', $search_key_value );

    }

}

function gmuw_pf_save_extra_user_profile_field( $user_id, $field_name ) {

    //update user-entered field
    update_user_meta( $user_id, $field_name, $_POST[$field_name] );
    
    //if we're an admin, also update the approved field
    if (current_user_can('manage_options')) {
        update_user_meta( $user_id, $field_name.'_approved', $_POST[$field_name.'_approved'] );
    }  

    //if the user-entered field has been changed to blank, also clear the approved field
    if (empty($_POST[$field_name])) {
        update_user_meta( $user_id, $field_name.'_approved', '' );
    }  

}
