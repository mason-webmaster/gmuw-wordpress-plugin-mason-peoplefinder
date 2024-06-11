<?php

/**
 * php file to handle WordPress dashboard customizations
 */


/**
 * Adds meta boxes to WordPress admin dashboard
 *
 */
add_action('wp_dashboard_setup', 'gmuw_pf_custom_dashboard_meta_boxes');
function gmuw_pf_custom_dashboard_meta_boxes() {

  // Declare global variables
  global $wp_meta_boxes;

  /* Add 'faculty/staff' meta box */
  add_meta_box("gmuw_pf_custom_dashboard_meta_box_facultystaff", "People Finder: Faculty/Staff Entries", "gmuw_pf_custom_dashboard_meta_box_facultystaff", "dashboard","normal");

  /* Add 'departments' meta box */
  add_meta_box("gmuw_pf_custom_dashboard_meta_box_departments", "People Finder: Department Entries", "gmuw_pf_custom_dashboard_meta_box_departments", "dashboard","normal");

  /* Add 'students' meta box */
  add_meta_box("gmuw_pf_custom_dashboard_meta_box_students", "People Finder: Student Entries", "gmuw_pf_custom_dashboard_meta_box_students", "dashboard","normal");

  /* Add 'taxonomies' meta box */
  add_meta_box("gmuw_pf_custom_dashboard_meta_box_taxonomies", "People Finder: Lookup Tables", "gmuw_pf_custom_dashboard_meta_box_taxonomies", "dashboard","normal");

  /* Add 'reports' meta box */
  add_meta_box("gmuw_pf_custom_dashboard_meta_box_reports", "Reports", "gmuw_pf_custom_dashboard_meta_box_reports", "dashboard","normal");

}


/**
 * Provides content for the dashboard 'faculty/staff' meta box
 */
function gmuw_pf_custom_dashboard_meta_box_facultystaff() {

  //Output content

  echo '<h3>Faculty/Staff</h3>';
  echo '<p>Faculty/Staff entries in People Finder are WordPress users.</p>';
  echo '<p><a href="/wp-admin/users.php">Click here to edit users</a></p>';

}

/**
 * Provides content for the dashboard 'departments' meta box
 */
function gmuw_pf_custom_dashboard_meta_box_departments() {

  //Output content

  echo '<h3>Departments</h3>';
  echo '<p>Department entries in People Finder are stored in the "Departments" custom post type.</p>';
  echo '<p><a href="/wp-admin/edit.php?post_type=department">Click here to edit department entries</a></p>';

}

/**
 * Provides content for the dashboard 'students' meta box
 */
function gmuw_pf_custom_dashboard_meta_box_students() {

  //Output content

  echo '<h3>Students</h3>';
  echo '<p>Student entries in People Finder are stored in a custom table in WordPress, and are imported from a text file provided by the Office of the Registrar, which text file must first be uploaded to the WordPress media library.</p>';
  echo '<p><a href="/wp-admin/media-new.php">Click here to upload a new student import file</a></p>';
  echo '<p><a href="/wp-admin/admin.php?page=gmuw_pf_import_students">Click here to import student entries from an uploaded file</a></p>';

}

/**
 * Provides content for the dashboard 'taxonomies' meta box
 */
function gmuw_pf_custom_dashboard_meta_box_taxonomies() {

  //Output content

  echo '<p>Departments and buildings that are selected from a list in faculty/staff and department records are custom WordPress taxonomies. (Since we can\'t assign taxonomies to users in WordPress these custom taxonomies are attached to the generic \'post\' post type.)</p>';
  echo '<h3>Departments</h3>';
  echo '<p>This is the list of departments which can be selected in faculty/staff department and affiliation fields.</p>';
  echo '<p><a href="/wp-admin/edit-tags.php?taxonomy=department">Click here to edit department lookup</a></p>';
  echo '<p>(This is not the same as the <a href="/wp-admin/edit.php?post_type=department">list of public department entries in People Finder</a>.)</p>';
  echo '<h3>Buildings</h3>';
  echo '<p>This is the list of buildings which can be selected in the faculty/staff and department building field.</p>';
  echo '<p><a href="/wp-admin/edit-tags.php?taxonomy=building">Click here to edit building lookup</a></p>';

}

/**
 * Provides content for the dashboard 'reports' meta box
 */
function gmuw_pf_custom_dashboard_meta_box_reports() {

  //Output content

  echo '<h3>Reports</h3>';
  echo '<p>Some reports are available to administrators.</p>';
  echo '<p><a href="/wp-admin/admin.php?page=gmuw_pf_admin_search">Click here to access reports</a></p>';


}
