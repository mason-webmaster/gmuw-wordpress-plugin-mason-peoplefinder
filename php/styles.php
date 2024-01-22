<?php

// Enqueue styles
add_action('wp_enqueue_scripts', 'gmuw_pf_styles');
function gmuw_pf_styles() { 
    wp_enqueue_style('gmuw_pf_default', plugin_dir_url( __DIR__ ).'css/default.css');
    wp_enqueue_style('gmuw_pf_search_form', plugin_dir_url( __DIR__ ).'css/pf-search-form.css');
    wp_enqueue_style('gmuw_pf_search_results', plugin_dir_url( __DIR__ ).'css/pf-search-results.css');
}
