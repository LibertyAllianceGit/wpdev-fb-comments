<?php
/*
Plugin Name: WP Developers Facebook Comments
Plugin URI: http://wpdevelopers.com
Description: Facebook comments by WP Developers.
Version: 2.0.0
Author: Tyler Johnson
Author URI: http://tylerjohnsondesign.com
License: GPL2
Copyright © 2019 WP Developers. All Rights Reserved.
*/

/**
Disallow Direct Access to Plugin File
**/
if(!defined('WPINC')) { die; }

/**
Updates
**/
require 'updates/plugin-update-checker.php';
$wpdevUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/LibertyAllianceGit/wpdev-fb-comments/',
	__FILE__,
	'wpdev-fb-comments'
);

/**
Constants
**/
define('WPDEVFB_BASE_VERSION', '1.6.6');
define('WPDEVFB_BASE_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('WPDEVFB_BASE_URI', trailingslashit(plugin_dir_url(__FILE__)));

/**
Include
**/
// Functions
require_once(WPDEVFB_BASE_PATH.'includes/functions.php');
// Admin
require_once(WPDEVFB_BASE_PATH.'admin/settings.php');

/**
Enqueue Plugin Files
**/
function wpdev_facebook_comments_admin_enqueue() {
  wp_enqueue_style('wpdev-fb-admin-css', WPDEVFB_BASE_PATH.'assets/wpdev-fb-comments-admin-css.css', '', WPDEVFB_BASE_VERSION);
  wp_enqueue_script('wpdev-fb-admin-js', WPDEVFB_BASE_PATH.'assets/wpdev-fb-comments-admin-js.js', array('jquery'), WPDEVFB_BASE_VERSION);
}
add_action('admin_enqueue_scripts', 'wpdev_facebook_comments_admin_enqueue');
