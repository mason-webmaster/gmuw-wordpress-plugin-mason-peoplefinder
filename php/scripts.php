<?php

/**
 * Summary: php file which implements the javascripts
 */


/**
 * Enqueue javascript
 */
add_action('wp_enqueue_scripts','gmuw_pf_enqueue_scripts');
function gmuw_pf_enqueue_scripts() {

  // Enqueue the plugin default javascript
  wp_enqueue_script(
    'gmuw_pf_script_default', //script name
    plugin_dir_url( __DIR__ ).'js/default.js', //path to script
    array('jquery')
  );

}