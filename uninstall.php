<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

class Tcarduninstall
{

    public static function uninstall(){

    	global $wpdb;
    	
    	$tcards = $wpdb->prefix . 'tcards';
     	$sql = "DROP TABLE IF EXISTS $tcards";
     	$wpdb->query($sql);

     	$tcard_skins = $wpdb->prefix . 'tcard_skins';
     	$sql = "DROP TABLE IF EXISTS $tcard_skins";
     	$wpdb->query($sql);

    }
}

Tcarduninstall::uninstall();