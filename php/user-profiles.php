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
        //type of field,field name,field title,field description,needs approval
        array('heading','', 'General People Finder Settings', '',''),
        array('yesno','pf_hide', 'Hide this entry completely?', 'Prevent this entry from appearing in the search results?',false),
        array('yesno','pf_hide_location', 'Hide location?', 'Do not display location information in listing?',false),
        array('yesno','pf_hide_email', 'Hide email address?', 'Do not display email address in listing?',false),
        array('yesno','pf_hide_phonenumbers', 'Hide phone/fax numbers?', 'Do not display phone/fax numbers in listing?',false),

        array('heading','', 'Personal Information', '',''),
        array('text','pf_name', 'Name', 'Please enter your name as you would like it to appear.',true),
        array('text','pf_pronouns', 'Pronouns', 'Your preferred pronouns.',true),

        array('heading','', 'Contact Information', '',''),
        array('text','pf_phone', 'Phone Number', 'Your phone number.',true),
        array('text','pf_fax', 'Fax Number', 'Your fax number.',true),
        array('text','pf_email', 'Email Address/NetID', 'Just the part before the gmu.edu, please.',true),

        array('heading','', 'Please enter information about your first role.', '',''),
        array('text','pf_title', 'Title 1', 'Please enter your title.',true),
        array('department','pf_department', 'Department 1', 'Please enter your department.',true),
        array('department','pf_affiliation', 'Affiliation 1', 'Please enter your professional affiliation.',true),
        array('building','pf_building', 'Building 1', 'Your building.',true),
        array('text','pf_room', 'Room 1', 'Your room number.',true),
        array('text','pf_mailstop', 'Mailstop 1', 'Your mailstop number (MSN).',true),

        array('heading','', 'Please enter information about your second role.', '',''),
        array('text','pf_title_2', 'Title 2', 'Please enter your title.',true),
        array('department','pf_department_2', 'Department 2', 'Please enter your department.',true),
        array('department','pf_affiliation_2', 'Affiliation 2', 'Please enter your professional affiliation.',true),
        array('building','pf_building_2', 'Building 2', 'Your building.',true),
        array('text','pf_room_2', 'Room 2', 'Your room number.',true),
        array('text','pf_mailstop_2', 'Mailstop 2', 'Your mailstop number (MSN).',true),

    );

    //return value
    return $pf_fields;

}


add_action( 'show_user_profile', 'gmuw_pf_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'gmuw_pf_extra_user_profile_fields' );

function gmuw_pf_extra_user_profile_fields($user) { 
	
	echo '<h3>People Finder Information</h3>';

    echo '<table class="form-table">';
    
    //show approve/disapprove all fields if user is an admin
    if (current_user_can('manage_options')) {
        echo '<a class="pf_approve_all button-primary" href="#">Approve All &#10003;</a>';
        echo '&nbsp;';
        echo '<a class="pf_disapprove_all button-primary" href="#">&#10006; Disapprove All</a>';
    }

    //set list of fields and data
    $pf_fields = gmuw_pf_custom_fields_array();

    //output fields
    foreach ($pf_fields as $pf_field) {
        if ($pf_field[0]=='heading') {
            echo '<tr><th colspan="2"><h4>'.$pf_field[2].' '.$pf_field[3].'</h4></th></tr>';
        } else {
            echo gmuw_pf_user_profile_people_finder_field($user,$pf_field[0],$pf_field[1], $pf_field[2], $pf_field[3], $pf_field[4]);
        }
    }

    echo '</table>';
	
}

// return user profile screen people finder field
function gmuw_pf_user_profile_people_finder_field($user, $field_type, $field_name, $field_title, $field_desc, $needs_approval) {

    //initialize variables
    $return_value='';

    $return_value.='<tr>';
    $return_value.='<th><label for="'.$field_name.'">'.$field_title.'</label></th>';
    $return_value.='<td>';

    $return_value.='<table class="pf-profile-layout">';
    $return_value.='<tr>';

    $return_value.='<td>';

    //user-entered field
    if ($field_type=='text') {
        $return_value.='<input type="text" name="'.$field_name.'" id="'.$field_name.'" value="' . esc_attr( get_user_meta( $user->ID, $field_name, true ) ) . '" class="regular-text" /><br />';
    }
    if ($field_type=='yesno') {
        $return_value.='<select name="'.$field_name.'" id="'.$field_name.'">';
        $return_value.='<option value="0">No</option>';
        $return_value.='<option value="1" ';
        $return_value.=get_user_meta($user->ID, $field_name, true)==1 ? 'selected' : '';
        $return_value.='>Yes</option>';
        $return_value.='</select><br />';
    }
    if ($field_type=='building' || $field_type=='department') {

        //get current field value
        $current_field_value = get_user_meta( $user->ID, $field_name, true );

        //display select field
        $return_value.='<select name="'.$field_name.'" id="'.$field_name.'">';
        $return_value.='<option value="">Select '.$field_type.'...</option>';
        foreach (get_terms(array('taxonomy'=>$field_type,'hide_empty'=>false)) as $term) {
        $return_value.='<option value="'.$term->term_id.'"';
        $term->term_id==$current_field_value ? $return_value.=' selected ' : '';
        $return_value.='>' . $term->name . '</option>';
        }
        $return_value.='</select><br />';
    }

    $return_value.='<p><span class="description">'.$field_desc.'</span></p>';

    //does this field need approval?
    if ($needs_approval) {
        //approved hidden field
        if (current_user_can('manage_options')) {
            $return_value.='<input type="hidden" name="'.$field_name.'_approved" id="'.$field_name.'_approved" value="' . esc_attr( get_user_meta( $user->ID, ''.$field_name.'_approved', true ) ) . '" class="regular-text" />';
        }
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

    //approval interface, if we need approval for this field
    if ($needs_approval) {
        //are we an admin?
        if (current_user_can('manage_options')) {
            //is this field approved
            if (gmuw_pf_field_is_approved($user->ID,$field_name)) {
                $return_value.='<p>';
                $return_value.='<span class="pf-field-status pf-field-status-approved">&#10003; Approved</span>';
                $return_value.='<a class="pf_disapprove button-primary" data-pf-field="'.$field_name.'" href="#">&#10006; Disapprove</a>';
                $return_value.='</p>';
            } else {
                if (!empty(get_user_meta( $user->ID, $field_name, true ))) {
                    $return_value.='<p>';
                    $return_value.='<span class="pf-field-status pf-field-status-pending" title="Current value: '. get_user_meta( $user->ID, $field_name.'_approved', true ) .'"">Not approved</span>';
                    $return_value.='<a class="pf_approve button-primary" data-pf-field="'.$field_name.'" href="#">Mark for Approval &#10003;</a>';
                    $return_value.='</p>';
                }
            }
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
        if ($pf_field[0]!='heading') {
            gmuw_pf_save_extra_user_profile_field($user_id, $pf_field[1], $pf_field[4]);
        }
    }

    //update user last updated date
    update_user_meta( $user_id, 'pf_last_updated', date('YmdHis') );

    //handle search key field
    //only if we're an admin
    if (current_user_can('manage_options')) {

        //initialize variables
        $search_key_value='';
        
        //set array of fields not to include in search key
        $exclude_from_search_key=array(
            'pf_building',
            'pf_room',
            'pf_building_2',
            'pf_room_2',
            'pf_pronouns',
        );
        
        //generate search key field
        foreach ($pf_fields as $pf_field) {
            //should we include this field in the search key?
            if (!in_array($pf_field[1],$exclude_from_search_key)) {
                //add to search key based on type of field
                switch ($pf_field[1]) {
                    case 'pf_name':
                        //add approved name field value in a couple formate
                        $myname=$_POST[$pf_field[1].'_approved'];
                        $myname_normalized_array=explode(",", $myname);
                        $myname_normalized=trim($myname_normalized_array[1]) . ' ' . trim($myname_normalized_array[0]);
                        $search_key_value .= $myname . ' ';
                        $search_key_value .= $myname_normalized . ' ';
                        break;
                    case 'pf_mailstop':
                    case 'pf_mailstop_2':
                        //if not empty, add approved field value with MSN prefix
                        if (!empty($_POST[$pf_field[1].'_approved'])) {
                            $search_key_value .= 'MSN'.$_POST[$pf_field[1].'_approved'] . ' ' . 'MSN '.$_POST[$pf_field[1].'_approved'] . ' ';
                        }
                        break;
                    case 'pf_department':
                    case 'pf_department_2':
                    case 'pf_affiliation':
                    case 'pf_affiliation_2':
                        //get department term name
                        $department_term_name = get_term($_POST[$pf_field[1].'_approved'])->name;
                        //add approved field value
                        $search_key_value .= $department_term_name . ' ';
                        break;
                    default:
                        //add approved field value
                        $search_key_value .= $_POST[$pf_field[1].'_approved'] . ' ';
                        break;

                }
            }
        }
        
        //save search key field
        update_user_meta( $user_id, 'pf_search_key', $search_key_value );

    }

}

function gmuw_pf_save_extra_user_profile_field( $user_id, $field_name, $needs_approval ) {

    //update user-entered field
    update_user_meta( $user_id, $field_name, $_POST[$field_name] );
    
    if ($needs_approval) {

        //if we're an admin, also update the approved field
        if (current_user_can('manage_options')) {
            update_user_meta( $user_id, $field_name.'_approved', $_POST[$field_name.'_approved'] );
        }

        //if the user-entered field has been changed to blank, also clear the approved field
        if (empty($_POST[$field_name])) {
            update_user_meta( $user_id, $field_name.'_approved', '' );
        }

    }

}

/**
 * Send notification email when a user profile is edited (by a non-administrator)
 */
add_action( 'personal_options_update', 'gmuw_pf_notify_admin_on_user_update' );
add_action( 'edit_user_profile_update','gmuw_pf_notify_admin_on_user_update');
function gmuw_pf_notify_admin_on_user_update(){

    //get current user info
    global $current_user;
    get_currentuserinfo();

    //if user is not an admin...
    if (!current_user_can( 'administrator' )){

        $to = 'masondir@gmu.edu';
        $subject = 'user updated profile';
        $message = "the user : " .$current_user->display_name . " has updated his profile with:\n";
        foreach($_POST as $key => $value){
            $message .= $key . ": ". $value ."\n";
        }
        wp_mail( $to, $subject, $message);

    }

}

add_filter('manage_users_columns', 'gmuw_pf_add_columns_users');
function gmuw_pf_add_columns_users($columns) {

    $columns['pf_last_updated'] = 'Last Updated';
    $columns['pf_search_key'] = 'Search Key';
    return $columns;

}

add_action('manage_users_custom_column',  'gmuw_pf_add_columns_users_content', 10, 3);
function gmuw_pf_add_columns_users_content($value, $column_name, $user_id) {

    //create return variable
    $return_value='';

    //set return value based on column
    switch ($column_name) {
        case 'pf_last_updated':
            //get date value from user meta
            $return_value=gmuw_pf_display_last_modified_date(get_user_meta($user_id,'pf_last_updated',true));
            break;
        case 'pf_search_key':
            $return_value=get_user_meta($user_id,'pf_search_key',true);
            break;
        default:
            break;
    }

    //return value
    return $return_value;

}

/* set up dashboard display config for new administrators when a new administrator user is created */
add_action( 'user_register', 'gmuw_pf_save_user_meta_dashboard_info', 10, 1 );
function gmuw_pf_save_user_meta_dashboard_info( $user_id ) {

    //set default values for usermeta fields which control the dashboard layout
    //what boxes are where
    $meta_box_order_dashboard_value='a:4:{s:6:"normal";s:269:"wpforms_reports_widget_lite,dashboard_site_health,dashboard_right_now,dashboard_activity,gmuw_pf_custom_dashboard_meta_box_students,gmuj_custom_dashboard_meta_box_theme_support,gmuw_pf_custom_dashboard_meta_box_facultystaff,gmuw_pf_custom_dashboard_meta_box_departments";s:4:"side";s:290:"dashboard_quick_press,dashboard_primary,gmuj_custom_dashboard_meta_box_mason_recommended_plugins,gmuj_custom_dashboard_meta_box_mason_resources,gmuj_custom_dashboard_meta_box_mason_configuration_messages,gmuw_pf_custom_dashboard_meta_box_taxonomies,gmuw_pf_custom_dashboard_meta_box_reports";s:7:"column3";s:31:"simple_history_dashboard_widget";s:7:"column4";s:0:"";}';
    //what boxes are hidden
    $metaboxhidden_dashboard_value='a:10:{i:0;s:27:"wpforms_reports_widget_lite";i:1;s:21:"dashboard_site_health";i:2;s:19:"dashboard_right_now";i:3;s:18:"dashboard_activity";i:4;s:44:"gmuj_custom_dashboard_meta_box_theme_support";i:5;s:21:"dashboard_quick_press";i:6;s:17:"dashboard_primary";i:7;s:56:"gmuj_custom_dashboard_meta_box_mason_recommended_plugins";i:8;s:46:"gmuj_custom_dashboard_meta_box_mason_resources";i:9;s:59:"gmuj_custom_dashboard_meta_box_mason_configuration_messages";}';

    //if this user is an administrator, set the dashboard display by adding the appropriate usermeta values
    if ($_POST['role']=='administrator') {
        //add user meta
        add_user_meta( $user_id, 'metaboxhidden_dashboard', maybe_unserialize($metaboxhidden_dashboard_value) );
        add_user_meta( $user_id, 'meta-box-order_dashboard', maybe_unserialize($meta_box_order_dashboard_value) );
    }

    return;

}
