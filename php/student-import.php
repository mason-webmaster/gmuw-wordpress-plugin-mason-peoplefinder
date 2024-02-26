<?php

/**
 * Summary: php file which implements the student import process
 */


/**
 * Generates the student import page
 */
function gmuw_pf_display_page_import_students() {
	
	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	//get info
	$number_of_students=gmuw_pf_get_student_count();
	$number_of_students_queued=gmuw_pf_get_student_count('new');

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	//process form if submitted
	//if we don't have a form submission, be done
	if (isset($_POST['submit'])) {

		//We have a form submission. Proceed...
		echo '<h2>Results</h2>';

		//what type of form submission?
		//echo '<p>Action: '.$_POST['submit'].'</p>';

		// Get globals
		global $wpdb;

		//should we delete queued records?
		if ($_POST['submit']=='delete') {

		  // Set table name, using the database prefix
		  $table_name = $wpdb->prefix . "gmuw_pf_students_new";

		  //delete records
		  $delete = $wpdb->query("TRUNCATE TABLE $table_name");

		  //output
		  if ($delete) { echo '<p>Queued records deleted.</p>'; }

		}

		//should we queue?
		if ($_POST['submit']=='queue' || $_POST['submit']=='queue+import') {

			// get parameters
			if (isset($_POST['gmuw_attachment_post_id'])) { $gmuw_attachment_post_id=(int)$_POST['gmuw_attachment_post_id']; }

			//if have an import file specified, do the queue for import
			if (!empty($gmuw_attachment_post_id)) {

				//We have a file specified. Proceed...

				//echo "<p>Attachment Post ID: $gmuw_attachment_post_id</p>";
				$attachment_path=get_attached_file($gmuw_attachment_post_id);
				//echo "<p>Attachment Path: $attachment_path</p>";
				$attachment_url=wp_get_attachment_url($gmuw_attachment_post_id);
				//echo "<p>Import file: $attachment_url</p>";

			  //Get the current timestamp
			  $currentTime = time()-18000; // 5 hours in seconds
			  $import_time_mysql_format = date('Y-m-d H:i:s', $currentTime); //Store it out in a MySQL format

			  //begin output
			  echo '<p>Student records queued for import from '.$attachment_url.':</p>';
			  echo '<textarea style="width:100%">';

			  //loop through student file lines
				foreach(file($attachment_path) as $index=>$line) {
				    echo $index+1 . ': ' . $line;
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

			  //finish output

			  echo '</textarea>';
			  echo '<p>Total records queued: ' . $index+1 . '</p>';

			} else {
				//no import file specified, but we were expecting one. Exit the function.
				echo '<p>No import file specified. Nothing done.</p>';
			}
		}

		//should we import?
		if ($_POST['submit']=='import' || ($_POST['submit']=='queue+import') && !empty($gmuw_attachment_post_id)) {

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

			echo '<p>New students in queue imported!</p>';

		}

	echo '<br />';

	//update counts
	$number_of_students=gmuw_pf_get_student_count();
	$number_of_students_queued=gmuw_pf_get_student_count('new');

	}

	//current status
	echo '<h2>Current Statistics</h2>';
	echo '<p>Current student records: '.$number_of_students.'</p>';
	echo '<p>Records queued for import: '.$number_of_students_queued.'</p>';
	echo '<br />';

	//student import form
	echo '<form method="post" action="admin.php?page=gmuw_pf_import_students" />';

	if ($number_of_students_queued>0) {
		echo '<h2>Import Queued Student Data</h2>';
		echo '<p><button name="submit" type="submit" value="import" />Import Queued Students ('.$number_of_students_queued.')</button></p>'; 
		echo '<br />';
		echo '<h2>Queue Additional Student Data</h2>';
	}
	if ($number_of_students_queued==0) {
		echo '<h2>Queue/Import Student Data</h2>';
	}

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

	echo '<br />';

	//display processing options
	if ($number_of_students_queued==0) {
		echo '<p><button name="submit" type="submit" value="queue" />Queue Student Records for Import</button></p>';
		echo '<p><button name="submit" type="submit" value="queue+import" />Queue and Import</button></p>';
	}
	if ($number_of_students_queued>0) {
		echo '<p><button name="submit" type="submit" value="queue" />Queue More Students</button></p>';
		echo '<p><button name="submit" type="submit" value="delete" onclick="return confirm(\'Do you want to clear queued records?\');" />Delete Queued Students</button></p>';
	}

	echo '</form>';

	// Finish HTML output
	echo "</div>";

}

function gmuw_pf_get_student_count($table_name_suffix=''){

	//initialize variables
	$return_value='';

	//get globals
	global $wpdb;

  // Set table name, using the database prefix
  $table_name = $wpdb->prefix . "gmuw_pf_students";

  // Add suffix to events table name if necessary
  switch ($table_name_suffix) {
    case 'new':
      $table_name.='_new';
      break;
    case 'old':
      $table_name.='_old';
      break;
  }

	//get record count
	$count_query = "select count(*) from $table_name";
	$return_value = $wpdb->get_var($count_query);

	//return value
	return (int)$return_value;

}
