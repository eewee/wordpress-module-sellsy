<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;
$sql[] = "DROP TABLE  `".$wpdb->prefix."eewee_sellsy_setting`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."eewee_sellsy_ticket`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."eewee_sellsy_ticket_form`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."eewee_sellsy_contact`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."eewee_sellsy_contact_form`";
$sql[] = "DROP TABLE  `".$wpdb->prefix."eewee_sellsy_error`";
foreach( $sql as $v ){ $wpdb->query($v); }