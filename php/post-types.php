<?php

/**
 * Summary: php file which implements the custom post types
 */


/**
 * Return the appropriate dashicon for the custom post type
 */
function gmuw_pf_get_cpt_icon($post_type){

    // Initialize array
    $cpt_icons = array(
        'department'=>'dashicons-businesswoman',
    );

    //Return value
    return $cpt_icons[$post_type];

}

/**
 * Register custom post types
 */
require('post-type-department.php');
