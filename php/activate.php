<?php

/**
 * Summary: php file which implements the theme initialization tasks
 */

function gmuw_pf_plugin_activate(){

  // Create custom tables
    //gmuw_pf_create_custom_tables();

}

function gmuw_pf_create_custom_tables(){

  // Create custom tables needed for the plugin
    // table 1
      gmuw_pf_create_custom_table_1();

}

function gmuw_pf_create_custom_table_1(){

  // Get globals
    global $wpdb;

  // Include file that contains the dbDelta function
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  // Set table name, using the database prefix
    $table_name = $wpdb->prefix . "gmuw_pf_1";

  // Write SQL statement to create table
    $sql = "CREATE TABLE $table_name (
     ID int(11) NOT NULL AUTO_INCREMENT,
     when_created datetime NOT NULL,
     when_modified datetime NOT NULL,
     PRIMARY KEY  (ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

  // Execute SQL
    dbDelta($sql);

}
