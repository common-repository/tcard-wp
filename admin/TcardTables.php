<?php
/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/admin
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardTables
{

	/**
	 * @since    1.0.0
	 */ 
	public static function create_tables(){
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$tcards_version = "1.0";
		$tcard_table_db_version = get_option( "tcards_table_db_version" );

		if ( $tcard_table_db_version != $tcards_version ) {

			$tcards = $wpdb->prefix . "tcards";

			if ( $wpdb->get_var( "SHOW TABLES LIKE '{$tcards}'" ) != $tcards ) {
				$sql = "CREATE TABLE $tcards (
					group_id int(11) NOT NULL AUTO_INCREMENT,
					skin_type varchar(25) NOT NULL,
					skins_number tinyint(1) NOT NULL,
					title varchar(255) NOT NULL,
					publish_up datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					settings text NOT NULL,
					PRIMARY KEY (group_id)
				) $charset_collate;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			update_option('tcards_table_db_version',$tcards_version);
		}

		$tcard_skins_version = "1.0";
		$tcard_skin_db_version = get_option( "tcard_skin_db_version" );

		if ( $tcard_skin_db_version != $tcard_skins_version ) {

			$tcard_skins = $wpdb->prefix . "tcard_skins";
			if ( $wpdb->get_var( "SHOW TABLES LIKE '{$tcard_skins}'" ) != $tcard_skins ) {
				$sql_skin = "CREATE TABLE $tcard_skins (
					skin_id int(11) NOT NULL AUTO_INCREMENT,
					group_id int(11) NOT NULL,
					closed tinyint(1) NOT NULL,
					elements text NOT NULL, 
					header text NOT NULL, 
					content mediumtext NOT NULL, 
					footer text NOT NULL,
					gallery text NOT NULL,
					settings text NOT NULL,  
					PRIMARY KEY (skin_id)
				) $charset_collate;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql_skin);
			}
			update_option('tcard_skin_db_version',$tcard_skins_version);
		}
	}
}