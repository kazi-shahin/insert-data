<?php
/* Plugin Name: Installer Locator v4.0
Plugin URI: http://blubirdinteractive.com/
Description: A plugin to allow parameters to be passed in the URL and recognized by WordPress
Author: BBIL
Version: 1.0
Author URI: http://www.blubirdinteractive.com/
*/

//if(isset())

//$obj = new StdClass;
//add_menu_page('Page title', 'Top-level menu title', 'manage_options', 'my-top-level-handle', 'my_magic_function');
add_action('admin_menu', 'installer_setting');

function installer_setting(){
    add_menu_page('Installer Setting', 'Installer Setting', 'manage_options', 'view-installer', 'run_plugin','',2);
    add_submenu_page('view-installer', 'Installer Setting', 'Installer Setting', 'manage_options', 'view-installer' );
    add_submenu_page('view-installer', 'Location Upload', 'Location Upload', 'manage_options', 'location-upload', 'location_upload' );
}



function location_frontend() {
    include 'config.php';
    include 'templates/fontend.php';
}

add_shortcode('location_frontend', 'location_frontend');

function run_plugin(){
    include 'dal/config.php';
    include 'templates/index.php';
}


// XML UPLOADER FUNCTIONS
define('BBITABLENAME','bbi_installer');
define('BBITEMPTABLE','bbi_temp');
define('BBIUPLOADDIR','xml_upload');
define('BBISUCCESSDIR','xml_success');
define('BBIERRORDIR','xml_error');

function location_upload(){
    include('uploader/bbi_backend.php');
    include('uploader/bbi_frontend.php');
    include 'templates/uploader.php';
}
function upoader_on_uninstall(){
    global $wpdb;
    $table = $wpdb->prefix.BBITABLENAME;
    $wpdb->query( "DROP TABLE IF EXISTS `$table`" );
}

function upoader_on_deactivation(){
    //Some stuff
}
register_deactivation_hook(__FILE__, 'upoader_on_deactivation');
register_uninstall_hook(__FILE__, 'upoader_on_uninstall');