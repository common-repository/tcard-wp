<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/admin
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardAjax
{
	
	/**
	 * @since    1.0.0
	 */
	public static function add_skin(){

		check_ajax_referer( 'tcard_add_skin', 'security' );

		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';
		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

	    $skin_type = sanitize_text_field($_POST['nameSkin']);
	    $startCount = sanitize_text_field($_POST['startCount']);
	    $stopCount = sanitize_text_field($_POST['stopCount']);
	    $group_id = sanitize_text_field($_POST['group_id']);

		/**
		 * @since    1.4.0
		 */		    
	    $type_action = sanitize_text_field($_POST['type_action']);
	    $skinCloned = sanitize_text_field($_POST['skinCloned']);
	  	
		/**
		 * @since    1.0.0
		 */		  	
	    require_once TCARD_PATH . "inc/TcardSkinsController.php";
	    $tcardSkinsController = new TcardSkinsController();

	    $wpdb->insert(
			$tcard_skin_table,
			array('group_id' => $group_id )
		);

	    $tcardSkinsController->admin_skins($group_id,TCARD_ADMIN_URL,$startCount,$stopCount,$skin_type,$type_action,$skinCloned);
	    
		$wpdb->update( 
			$tcard_table, 
			array( 
				'skins_number' => $stopCount
			), 
			array( 
				'group_id' => $group_id 
			), 
			array( '%d' ), 
			array( '%d' ) 
		);

	 	die();
	}

	/**
	 * @since    1.0.0
	 */
	public static function delete_skin(){

		check_ajax_referer( 'tcard_delete_skin', 'security' );

		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';
		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		$group_id = sanitize_text_field($_POST['group_id']);
		$skin_key = sanitize_text_field($_POST['skin_key']);
		$skins_number = sanitize_text_field($_POST['skins_number']);

        $skins = $wpdb->get_results("SELECT skin_id FROM $tcard_skin_table WHERE group_id =  $group_id");

  
 		if(!empty($skins[$skin_key]->skin_id)){

        	$wpdb->delete($tcard_skin_table,array('skin_id' => $skins[$skin_key]->skin_id));

    	}else{
			http_response_code(500);
    	}

        $wpdb->update( 
			$tcard_table, 
			array( 
				'skins_number' => $skins_number
			), 
			array( 
				'group_id' => $group_id 
			), 
			array( '%d' ), 
			array( '%d' ) 
		);

		die();
	}

	/**
	 * @since    1.0.0
	 */
	public static function select_skin(){

		check_ajax_referer( 'tcard_select_skin', 'security' );

		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';

		$group_id = sanitize_text_field($_POST['group_id']);
		$skin_type = sanitize_text_field($_POST['skin_type']);
		$group_name = sanitize_text_field($_POST['group_name']);
	

		require_once TCARD_PATH . "inc/TcardSkinsController.php";
	    $tcardSkinsController = new TcardSkinsController();
	    $tcardSkinsController->admin_skins($group_id,TCARD_ADMIN_URL,$startCount = null,$stopCount = null,$skin_type,'new-skin',$skinCloned = null);

        $wpdb->update( 
			$tcard_table, 
			array( 
				'title' => $group_name,
				'skin_type' => $skin_type
			), 
			array( 
				'group_id' => $group_id 
			), 
			array( '%s' ),
			array( '%s' ), 
			array( '%d' ) 
		);
        
		die();
	}

	/**
	 * Check if is one of pre-made skins
	 * @since    1.0.0
	 */
	public static function check_pre_skins($skin_type){

		$pre_skin_type = array("skin_1","skin_2","skin_3","skin_4","skin_5","skin_6");

		foreach ($pre_skin_type as $pre_skin) {
			if($skin_type == $pre_skin){
				return $pre_skin;
			}
		}
	}
}