<?php
/*
Plugin Name: Example Plugin
Plugin URI: http://yourwebsite.com/my-custom-plugin
Description: A brief description of your plugin.
Version: 1.1
Author: Your Name
Author URI: http://yourwebsite.com
License: GPLv2 or later
*/
// Include the admin menu and settings
include_once plugin_dir_path(__FILE__) . 'admin/admin-menu.php';
include_once plugin_dir_path(__FILE__) . 'admin/admin-settings.php';

// Include other functions
include_once plugin_dir_path(__FILE__) . 'includes/functions.php';