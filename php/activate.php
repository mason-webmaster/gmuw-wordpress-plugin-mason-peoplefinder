<?php

/**
 * Summary: php file which implements the theme initialization tasks
 */

function gmuw_pf_plugin_activate(){

  // Create custom tables
    gmuw_pf_create_custom_tables();

}

function gmuw_pf_create_custom_tables(){

  // Create custom tables needed for the plugin
    // students
      gmuw_pf_create_custom_table_students();
    // new students
      gmuw_pf_create_custom_table_students('new');
    // old students
      gmuw_pf_create_custom_table_students('old');
}

function gmuw_pf_create_custom_table_students($table_name_suffix=''){

  // Get globals
    global $wpdb;

  // Include file that contains the dbDelta function
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

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

  // Write SQL statement to create table
    $sql = "CREATE TABLE $table_name (
     ID int(11) NOT NULL AUTO_INCREMENT,
     import_time int(11) NOT NULL,
     student_name nvarchar(255) DEFAULT NULL,
     student_major nvarchar(255) DEFAULT NULL,
     student_phone_number nvarchar(255) DEFAULT NULL,
     student_email nvarchar(255) DEFAULT NULL,
     student_pronouns nvarchar(255) DEFAULT NULL,
     when_created datetime NOT NULL,
     when_modified datetime NOT NULL,
     PRIMARY KEY  (ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

  // Execute SQL
    dbDelta($sql);

}
