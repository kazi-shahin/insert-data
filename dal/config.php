<?php
global $wpdb;
define('TableInstaller',$wpdb->prefix . 'installer');
define('TableInstallerIndustry',$wpdb->prefix . 'installer_industry');
define('TableInstallerProduct',$wpdb->prefix . 'installer_product');
define('TableWpPost',$wpdb->prefix . 'posts');

// creates my_table in database if not exists
$table = TableInstaller;
$charset_collate = $wpdb->get_charset_collate();
$sql = "CREATE TABLE IF NOT EXISTS $table (
    `installer_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `installer_name` VARCHAR(256) NULL,
	`phone` VARCHAR(32) NULL, 
	`website` VARCHAR(512) NULL,
    `street_line_1` VARCHAR(256) NULL,
    `street_line_2` VARCHAR(256) NULL,
    `city` VARCHAR(256) NULL,
    `state` VARCHAR(256) NULL,
    `zip` VARCHAR(16) NOT NULL,
    `country` VARCHAR(128) NULL,
    `latitude` VARCHAR(32) NOT NULL,
    `longitude` VARCHAR(32) NOT NULL,
    `status` TINYINT NOT NULL DEFAULT '1' COMMENT '0=Inactive,1=Active'
) $charset_collate;";

$sql2 = "CREATE TABLE IF NOT EXISTS ".TableInstallerIndustry." (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `installer_id` INT NOT NULL,
    `industry_id` INT NOT NULL
) $charset_collate;";


$sql3 = "CREATE TABLE IF NOT EXISTS ".TableInstallerProduct." (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `installer_id` INT NOT NULL,
    `product_id` INT NOT NULL
) $charset_collate;";



require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
dbDelta($sql2);
dbDelta($sql3);
include 'class.installer.php';
include 'class.installer.industry.php';
include 'class.installer.product.php';
function get_data($key){
    return isset($_GET[$key])?$_GET[$key]:'';
}

function post_data($key){
    return isset($_POST[$key])?$_POST[$key]:'';
}

function error($msg){
    return '<span style="color:red">'.$msg.'</span>';
}

function success($msg){
    return '<span style="color:green">'.$msg.'</span>';
}
