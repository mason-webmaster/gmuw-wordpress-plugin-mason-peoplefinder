<?php

/**
 * Summary: php file which implements the admin interface scripts
 */


/**
 * Enqueue admin javascript
 */
add_action('admin_enqueue_scripts','gmuw_pf_enqueue_scripts_admin');
function gmuw_pf_enqueue_scripts_admin() {

  // Enqueue datatables javascript
  wp_enqueue_script(
    'gmuw_pf_script_admin_datatables', //script name
    plugin_dir_url( __DIR__ ).'datatables/datatables.min.js' //path to script
  );

  // Enqueue the plugin admin javascript
  wp_enqueue_script(
    'gmuw_pf_script_admin', //script name
    plugin_dir_url( __DIR__ ).'js/admin.js' //path to script
  );

}