<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/admin
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardAdmin 
{


	/**
	 * Construct
	 * @since    1.0.0
	 */
	public function __construct(){

		require_once TCARD_PATH . "inc/TcardAjax.php";

    	require_once TCARD_ADMIN_URL . 'TcardSaveData.php';
	}	

	/**
	 * Register the stylesheets for the admin area.
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {

		if( $hook !== 'toplevel_page_tcard' ) 
			return;

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( TCARD_NAME , TCARD_BASE_URL . 'admin/css/tcard-admin.min.css', "" , TCARD_VERSION );
		wp_enqueue_style( TCARD_NAME."fontawesome" , TCARD_ASSETS_URL . 'fontawesome/css/fontawesome-all.min.css', "" ,'all');
	}	

	/**
	 * Register the JavaScript for the admin area.
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		if( $hook !== 'toplevel_page_tcard' ) 
			return;
	    wp_enqueue_script("jquery-ui-draggable");
	    wp_enqueue_script("jquery-ui-droppable");
	    wp_enqueue_editor();
	    wp_enqueue_media();
	    wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( TCARD_NAME , TCARD_BASE_URL . 'admin/js/tcard-admin.min.js', array( 'jquery' ), TCARD_VERSION, true );
		wp_localize_script( TCARD_NAME, 'tcard', array(
			'add_skin' 				=> wp_create_nonce("tcard_add_skin"),
			'delete_skin' 			=> wp_create_nonce("tcard_delete_skin"),
			'select_skin' 			=> wp_create_nonce("tcard_select_skin"),
			'group_id' 				=> $this->find_group('DESC')
		)); 	
		
	}

	/**
	 * @since 1.0.0
	 */
	public function add_tcard_page(){

		add_menu_page(
	        'Tcard',
	        'Tcard',
	        'manage_options',
	        'tcard', 
	        array( $this, "dashboard" ),
	        'dashicons-index-card',
	        77
	    );
	}

	/**
	 * Callback function for admin page
	 * @since 1.0.0
	 */
	public static function dashboard(){

		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';

		$group_id = $this->find_group('DESC');
		$groups = $this->sort_groups('DESC');

		require_once TCARD_PATH . "inc/TcardSkinsController.php";
		require_once TCARD_PATH . "inc/TcardElementsController.php";
		
		$tcardSkinsController = new TcardSkinsController();
		$tcardElements = new TcardElementsController($group_id, $urlfile = null);

		$group_output =  $wpdb->get_row("SELECT * FROM $tcard_table WHERE group_id = $group_id");

		$categories = get_categories( $args = '' ); 

		if(!empty($group_output)){
			$group_settings = unserialize($group_output->settings);
			$get_skin_type = $group_output->skin_type;
			$group_title = $group_output->title;
			$group_skins_number = $group_output->skins_number;
		}else{
			$group_settings = "";
			$get_skin_type = "";
			$group_title = "";
			$group_skins_number = "";
		}

		/**
		 * @since 1.6.0
		 */	
		$pre_skin = $this->check_pre_skins($get_skin_type);

		if($get_skin_type == $pre_skin){
			$skin_name = $get_skin_type;
		}

		/**
		 * @since 1.0.0
		 */	
		require_once TCARD_ADMIN_URL . 'templates/TcardDashboard.php';
	}

	/**
     * Create a new group
     * @since 1.0.0
     */
	public function create_group(){

        check_admin_referer( "tcard_create_group" );

        global $wpdb;

        $tcard_table = $wpdb->prefix.'tcards';

        $group = array(
        	'publish_up' 	=> current_time( 'mysql' ),
        	'modified' 		=> current_time( 'mysql' ),
        	'title'			=> 'New Group',
        );

        $wpdb->query( $wpdb->prepare("INSERT INTO $tcard_table ( publish_up, modified , title ) 
				VALUES ( %s, %s, %s )", 
				$group
			) 
		);

        $group_id = $this->find_group('DESC');
        wp_redirect( admin_url( "admin.php?page=tcard&group_id={$group_id}" ) );
	}

	/**
     * Delete group displayed
     * @since 1.0.0
     */
  	public function delete_group() {

        check_admin_referer( "tcard_delete_group" );

        global $wpdb;

        $tcard_table = $wpdb->prefix.'tcards';
		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

        $group_id = absint( $_GET['get_group_id'] );

       	$group = "DELETE FROM $tcard_table WHERE group_id = %d";
	    $wpdb->query( $wpdb->prepare($group, $group_id) );

	    $skins = "DELETE FROM $tcard_skin_table WHERE group_id = %d";
	    $wpdb->query( $wpdb->prepare($skins, $group_id ) );
    
	    $group_id = $this->find_group('DESC');

		if(!empty($group_id)){
			wp_redirect( admin_url( "admin.php?page=tcard&group_id={$group_id}" ) );
		}else{
			wp_redirect( admin_url( "admin.php?page=tcard" ) );
		}
    }

	/**
     * Sort order of groups
     * @since 1.0.0
     */
 	public function sort_groups($order) {

 		global $wpdb;

 		$tcard_table = $wpdb->prefix.'tcards';

 		$groups = array();

        $all_groups = $wpdb->get_results("SELECT group_id, title FROM $tcard_table ORDER BY modified $order");

        foreach ($all_groups as $group) {

  			 $groups[] = array(
  			 	'group_id' => $group->group_id,
                'title' => $group->title
            );
        }

        return $groups;
    }

	/**
     * Find the id of group
     * @since 1.0.0
     */
	public function find_group($order) {

		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';

        $group = $wpdb->get_row("SELECT group_id FROM $tcard_table ORDER BY modified $order");
 
 		if(!empty($group)){
 			$get_id = $group->group_id;
 		}else{
 			$get_id = "";
 		}

        if (isset($_REQUEST['group_id']) && $group_id = $_REQUEST['group_id']) {
            return (int)$group_id;
        }else{
	        return (int)$get_id;
        }

        return false;

    }

    /**
     * @since 1.0.0
     */
    public function update_group(){

    	check_admin_referer( "tcard_update_group" );
    	
    	$group_id = sanitize_text_field( $_POST['group_id'] );
    	$group_id = (int)$group_id;

		TcardSaveData::save();

		wp_redirect( admin_url( "admin.php?page=tcard&group_id={$group_id}" ) );
    }

	/**
     * @since 1.0.0
     */
	public function add_skin(){
		TcardAjax::add_skin();
	}

	/**
     * @since 1.0.0
     */
	public function delete_skin(){

		TcardAjax::delete_skin();
	}

	/**
     * @since 1.0.0
     */
	public function select_skin(){

		TcardAjax::select_skin();
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
