<?php

/**
 * Main plugin file for the Mason WordPress: People Finder
 */

/**
 * Plugin Name:       Mason WordPress: People Finder
 * Author:            Mason Webmaster
 * Plugin URI:        https://github.com/mason-webmaster/gmuw-wordpress-plugin-mason-peoplefinder
 * Description:       
 * Version:           0.9
 */


// Exit if this file is not called directly.
	if (!defined('WPINC')) {
		die;
	}

// Set up auto-updates
	require 'plugin-update-checker/plugin-update-checker.php';
	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/mason-webmaster/gmuw-wordpress-plugin-mason-peoplefinder/',
	__FILE__,
	'gmuw-wordpress-plugin-mason-peoplefinder'
	);

// Plugin activation
require('php/activate.php');

// functions
include('php/functions.php');

// Branding
include('php/fnsBranding.php');

// Admin menu
include('php/admin-menu.php');

// Admin page
include('php/admin-page.php');

// Admin dashboard
include('php/admin-dashboard.php');

// Plugin settings
include('php/settings.php');

// post types
require('php/post-types.php');

// Admin scripts
require('php/admin-scripts.php');

// Admin styles
require('php/admin-styles.php');

// Cron (WP cron)
require('php/cron.php');

// Scripts
require('php/scripts.php');

// Styles
require('php/styles.php');

// Styles
require('php/user-profiles.php');

// shortcodes
require('php/shortcodes.php');

// student import
require('php/student-import.php');

// taxonomies
require('php/taxonomies.php');

// ip addresses
require('php/ip-addresses.php');

// user search
require('php/user-search.php');

// dates
require('php/dates.php');

// WPForms customizations
require('php/wpforms.php');

//Register activation hook
register_activation_hook(
	__FILE__,
	'gmuw_pf_plugin_activate'
);
