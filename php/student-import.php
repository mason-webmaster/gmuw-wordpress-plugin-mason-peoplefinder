<?php

/**
 * Summary: php file which implements the student import process
 */


/**
 * Generates the plugin settings page
 */
function gmuw_pf_display_page_import_students() {
	
	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	//student import

	// get parameters
	$gmuw_attachment_post_id='';
	if (isset($_POST['gmuw_attachment_post_id'])) { $gmuw_attachment_post_id=(int)$_POST['gmuw_attachment_post_id']; }

	// input form
	echo '<form method="post" action="admin.php?page=gmuw_pf_import_students" />';

	echo '<p>Select import file: ';
	echo '<select name="gmuw_attachment_post_id">';
	echo '<option value="">-</option>';
  //get attachments that are text files
  $args = array(
      'post_type'  => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'text/plain',
			'post_parent'    => null,
      'nopaging' => true,
  );
  $attachment_posts = get_posts($args);

	foreach ($attachment_posts as $attachment_post) {
		echo '<option value="'.$attachment_post->ID.'">' . $attachment_post->post_title . '</p>';
	}
	echo '</select>';
	echo '</p>';

	echo '<p><input name="submit" type="submit" value="Import" /></p>';

	echo '</form>';

	echo '<br />';

	if (empty($gmuw_attachment_post_id)) { echo '</div>'; return; }

	//We have a file specified. Proceed...

	//echo "<p>Attachment Post ID: $gmuw_attachment_post_id</p>";
	$attachment_path=get_attached_file($gmuw_attachment_post_id);
	//echo "<p>Attachment Path: $attachment_path</p>";
	$attachment_url=wp_get_attachment_url($gmuw_attachment_post_id);
	echo "<p>Import file: $attachment_url</p>";

	// Get globals
	global $wpdb;

  //Get the current timestamp
  $currentTime = time()-18000; // 5 hours in seconds
  $import_time_mysql_format = date('Y-m-d H:i:s', $currentTime); //Store it out in a MySQL format

    //loop through student file lines
	foreach(file($attachment_path) as $index=>$line) {
	    echo '<p>' . $index+1 . ': ' . $line . '</p>';
	    $student_name = trim(substr($line,0,45));
	    //echo "<p>Student Name: $student_name</p>";
	    $student_major = trim(substr($line,45,8));
	    //echo "<p>Student major: $student_major</p>";
	    $student_phone = trim(substr($line,53,33));
	    //echo "<p>Student phone: $student_phone</p>";
	    $student_pronouns = trim(substr($line,86));
	    //echo "<p>Student pronouns: $student_pronouns</p>";

		//Insert student record into database
		$wpdb->insert( 
			'wp_gmuw_pf_students_new',
			array( 
				'import_time' => time(),
				'student_name' => $student_name,
				'student_major' => $student_major,
				'student_phone_number' => $student_phone,
				'student_pronouns' => $student_pronouns,
				'when_created' => $import_time_mysql_format,
				'when_modified' => $import_time_mysql_format,
			)
		);

	}

	//juggle tables

	// drop old table
	if ($wpdb->query("DROP TABLE IF EXISTS wp_gmuw_pf_students_old")===false) {
	echo $wpdb->print_error();
	} else {
	//echo "<p>Old students table removed.</p>";
	}

	// rename current table to old table
	if ($wpdb->query("rename table wp_gmuw_pf_students to wp_gmuw_pf_students_old")===false) {
	echo $wpdb->print_error();
	} else {
	//echo "<p>Current students table renamed to old students table.</p>";
	}

	// rename new table to current table
	if ($wpdb->query("rename table wp_gmuw_pf_students_new to wp_gmuw_pf_students")===false) {
	echo $wpdb->print_error();
	} else {
	//echo "<p>New students table renamed to current students table.</p>";
	}

	// create another new table for next time
	gmuw_pf_create_custom_table_students('new');
	//echo "<p>New students table created.</p>"; 

	// Finish HTML output
	echo "</div>";
	
}
