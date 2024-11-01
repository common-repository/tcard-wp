<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/public
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardFront 
{

	/**
	 * Construct
	 * @since    1.0.0
	 */

	public function __construct(){

		require_once TCARD_PATH . "inc/TcardAjax.php";
		require_once TCARD_PATH . "inc/TcardForms.php";
	}

	/**
	 * Register the Styles for the public-facing side of the site.
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( TCARD_NAME."bootstrap" , TCARD_ASSETS_URL . 'bootstrap/css3.7/bootstrap.min.css', "" ,'all');
		wp_enqueue_style( TCARD_NAME , TCARD_BASE_URL . 'front/css/tcard.min.css', "" ,TCARD_VERSION);
		wp_enqueue_style( TCARD_NAME."color" , TCARD_BASE_URL . 'front/css/tcard_color.min.css', "" ,TCARD_VERSION);
		wp_enqueue_style( TCARD_NAME."animate" , TCARD_ASSETS_URL . 'animate/animate.css', "" ,'all');
		wp_enqueue_style( TCARD_NAME."fontawesome" , TCARD_ASSETS_URL . 'fontawesome/css/fontawesome-all.min.css', "" ,'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		
		wp_enqueue_script('jquery');
		wp_enqueue_media();
		wp_enqueue_script( TCARD_NAME , TCARD_BASE_URL . 'front/js/tcard.min.js', array( 'jquery' ), TCARD_VERSION ,false );

		wp_localize_script( TCARD_NAME, 'tcard_front', array(
				'ajaxurl' 					=> admin_url('admin-ajax.php'),
				'tcard_contact'				=> wp_create_nonce("tcard_contact"),
			));
	}

	/**
	 * Shortcode.
	 * Redirect the user after login to same page where is tcard with login elemet.
	 * @since    1.0.0
	 */
	public function add_filters_shortcode(){
		add_shortcode('tcard',array( $this , 'group' ));
		add_filter( 'authenticate','TcardForms::login_redirect' , 101, 3 );
	}

	/**
	 * Display group for the public-facing side of the site.
	 * @since    1.0.0
	 */
	public function group($attr){

		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';
		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		extract(shortcode_atts(array(
                'group_id' => ''
                    ), $attr));

		require_once TCARD_PATH . "inc/TcardSkinsController.php";
		$tcardSkinsController = new TcardSkinsController();

		$get_skins =  $wpdb->get_results("SELECT * FROM $tcard_skin_table WHERE group_id = $group_id");

		$group = $wpdb->get_row("SELECT * FROM $tcard_table WHERE group_id = $group_id");

		$all_gallery = $wpdb->get_col("SELECT gallery FROM $tcard_skin_table WHERE group_id = $group_id");
		$all_gallery = array_filter($all_gallery);

		$curr_user = wp_get_current_user();

		if(!empty($group)){
			$settings = unserialize($group->settings);
			$skin_type = $group->skin_type;
			$skins_number = $group->skins_number;
		}else{
			$skin_type = "";
			$skins_number = "";
		}

		if(!empty($settings["container_group"])){
			if($settings["container_group"] == "fixed"){
				$settings["container_group"] = "container";
			}elseif($settings["container_group"] == "fluid"){
				$settings["container_group"] = "container-fluid";
			}
		}

		if(empty($settings['tcardFlip'])){
			$settings['tcardFlip'] = 0;
		}

		if(empty($settings['tcardOn'])){
			$settings['tcardOn'] = "button";
		}

		if(empty($settings['animationFront'])){
			$settings['animationFront'] = "ready";
		}

		if(empty($settings['animationOneTime'])){
			$settings['animationOneTime'] = 0;
		}

		if(empty($settings['randomColor'])){
			$settings['randomColor'] = 0;
		}

		if(empty($settings['durationCount'])){
			$settings['durationCount'] = 900;
		}

		if(empty($settings['autocomplete'])){
			$settings['autocomplete'] = 0;
		}
			
		if(empty($group) && !empty($group_id)){
			return "<h3 class='tcard-not-group'>Tcard group-$group_id does not exist!!</h3>";
		}else{
			ob_start();
		  		 require TCARD_FRONT_URL . "templates/TcardGroup.php";
		 	return ob_get_clean();
		}
	
	}

	public function contact(){

		TcardForms::contact();
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
