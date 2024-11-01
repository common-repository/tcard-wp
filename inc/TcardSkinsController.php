<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/inc
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardSkinsController 
{

	/**
	 * @since    1.0.0
	 */
	public $group;


	/**
	 * @since    1.0.0
	 */
	public function skinType($group_id,$file_url){

		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';

	    $this->group = $wpdb->get_row("SELECT * FROM $tcard_table WHERE group_id = $group_id");

		if($file_url == TCARD_ADMIN_URL){
			$this->admin_skins($group_id,$file_url,$startCount = null,$stopCount = null,$skin_type = null,"new-skin",$skinCloned = null);
		}else{
			$this->public_skins($group_id,$file_url,"new-skin",$is_widget = false,$alex = null);
		}

	}
	
	/**
	 * @since    1.0.0
	 */	
	public function admin_skins($group_id,$file_url,$startCount,$stopCount,$skin_type,$type_action,$skinCloned){

		global $wpdb;

		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		$group = $this->group;
		$get_skins = $wpdb->get_results("SELECT * FROM $tcard_skin_table WHERE group_id = $group_id");

		require_once TCARD_PATH . "inc/TcardElementsController.php";
		$elementsController = new TcardElementsController($group_id, $file_url);

		if(!empty($group->skin_type)){
			$skin_type = $group->skin_type;
			$stopCount = $group->skins_number;
			$startCount = 0;
		}
		
		$countSkin = str_replace("_", " ", $skin_type);
		$users = get_users(array(
		    'orderby' => 'ID',
		    'order' => 'ASC',
		));

		require_once TCARD_ADMIN_URL . "TcardSetElements.php";
		$tcardsetElements = new TcardSetElements();
		$pre_skin = self::check_pre_skins($skin_type);
		for ($skin = $startCount; $skin < $stopCount; $skin++) {

			$elements_skin = $tcardsetElements->set_elements($skin,$skin_type);

			if(!empty($elements_skin)){
				$all_elements = $elements_skin;
			}else{
				if($skin_type !== "skin_5"){
					$all_elements = unserialize($get_skins[$skin]->elements);
				}
			}
			

			/**
			 * @since    1.4.0
			 */	
			if($type_action == "new-skin"){
				if(!empty($all_elements[$skin]['header'])){
					$headerElements = $all_elements[$skin]['header'];
				}else{
					$headerElements = "";
				}
				if(!empty($all_elements[$skin]['content'])){
					$contentElements = $all_elements[$skin]['content'];
				}else{
					$contentElements = "";
				}
				if(!empty($all_elements[$skin]['footer'])){
					$footerElements = $all_elements[$skin]['footer'];
				}else{
					$footerElements = "";
				}
			}

			/**
			 * @since    1.0.0
			 */	

			($get_skins[$skin]->closed == 1) ? $closed = "closed" : $closed = "open";
			$gallery = unserialize($get_skins[$skin]->gallery);
	
			(!empty($gallery['image'])) ? $have_images = "have_images" : $have_images = "";
			if($skin_type !== "skin_5"){
				(empty($headerElements) && empty($contentElements) && empty($footerElements)) ? $addHeight[$skin] = "extra-height" : '';
			}

			require $file_url . "templates/TcardSkins.php";
		}
	}

	/**
	 * @since    1.0.0
	 */
	public function public_skins($group_id,$file_url,$type_action,$is_widget,$skin_index){


		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';
		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		$group = $wpdb->get_row("SELECT * FROM $tcard_table WHERE group_id = $group_id");
		$get_skins = $wpdb->get_results("SELECT * FROM $tcard_skin_table WHERE group_id = $group_id");

		require_once TCARD_PATH . "inc/TcardElementsController.php";
		$elementsController = new TcardElementsController($group_id, $file_url);

		$skin_type = $group->skin_type;

		$group_settings = unserialize($group->settings);

		if($skin_type == "skin_5"){
			$args = array(
				'posts_per_page'   => $group->skins_number,
				'category_name'    => $group_settings["category_name"],
				'orderby'          => $group_settings['orderby_category'],
				'order'            => $group_settings['order_category'],
				'post_type'        => 'post',
				'post_status'      => 'publish',
				'suppress_filters' => true 
			);
			$posts = get_posts( $args );
		}
		

		$skins_number = $group->skins_number;
		$startCount = 0;
		
		for ($skin = $startCount; $skin < $skins_number; $skin++) {
			$all_elements = unserialize($get_skins[$skin]->elements);
			if(!empty($all_elements[$skin]['header'])){
				$headerElements = $all_elements[$skin]['header'];
			}else{
				$headerElements = "";
			}
			if(!empty($all_elements[$skin]['content'])){
				$contentElements = $all_elements[$skin]['content'];
			}else{
				$contentElements = "";
			}
			if(!empty($all_elements[$skin]['footer'])){
				$footerElements = $all_elements[$skin]['footer'];
			}else{
				$footerElements = "";
			}

			$gallery = unserialize($get_skins[$skin]->gallery);
			$settings = unserialize($get_skins[$skin]->settings);
			if(!empty($all_elements[$skin]['content']['front'])){
				$login = in_array('login', $all_elements[$skin]['content']['front']);
			}else{
				$login = "";
			}

			$set_columns = array('extra_small','small','medium','large');

			foreach ($set_columns as $column) {
				if($settings[$column] == "Inherit"){
					$settings[$column] = "";
				}
				$settings["column"] = $settings['extra_small']." ".$settings['small']." ".$settings['medium']." ".$settings['large'];
				$settings["column"] = preg_replace('/\s+/', ' ',$settings["column"]);
			}

			if($settings["main_cubicbezier"] == true){
				$settings["main_cubicbezier"] = "cubicbezier";
			}else{
				$settings["main_cubicbezier"] = "";
			}

			$check_frostedglass = array('main_frostedglass_main','front_frostedglass','back_frostedglass');
			foreach ($check_frostedglass as $frostedglass) {
				if($settings[$frostedglass] == true){
					$settings[$frostedglass] = "frosted-glass";
				}else{
					$settings[$frostedglass] = "";
				}
			}

			$pre_skin = self::check_pre_skins($skin_type);

			if($skin_type == $pre_skin){
				$front_background_color[$skin] = $settings["front_background_color"];
				$back_background_color[$skin] = $settings["back_background_color"];
			}else{
				if(!empty($settings["front_background_color"])){
					$cfront_background_color[$skin] = "background-color:".$settings["front_background_color"]."";
				}
				if(!empty($settings["back_background_color"])){
					$cback_background_color[$skin] = "background-color:".$settings["back_background_color"]."";
				}		
			}

			$all_background_image = array('front_background_image','back_background_image','front_bg_image_content','back_bg_image_content','front_bg_image_header','back_bg_image_header');

			foreach ($all_background_image as $background_image) {
				
				if(!empty($settings[$background_image])){
					if($background_image == "front_bg_image_content" || $background_image == 'back_bg_image_content' || $background_image == 'front_bg_image_header' || $background_image == 'back_bg_image_header'){

						$settings[$background_image] = "style='background-image: url(".esc_url($settings[$background_image]).")'";	

					}else{
						$settings[$background_image] = "background-image: url(".esc_url($settings[$background_image]).")";
					}
					$backgroundPost = $settings['front_bg_image_content'];
				}else{
					if($skin_type == "skin_5"){
						$backgroundPost = 'style="background-image: url('.get_the_post_thumbnail_url($posts[$skin]->ID, 'large').')"';
					}
					
				}

			}

			if(!empty($settings['front_background_image']) || !empty($cfront_background_color[$skin])){
				$front_background[$skin] = 'style="'.$settings['front_background_image'].';'. $cfront_background_color[$skin].'"';	
			}else{
				$front_background[$skin] = "";
			}
	
			if(!empty($settings['back_background_image']) || !empty($cback_background_color[$skin])){
				$back_background[$skin] = 'style="'.$settings['back_background_image'].';'. $cback_background_color[$skin].'"';
			}else{
				$back_background[$skin] = "";
			}

			if($skin_type == $pre_skin){
				$skin_name = $skin_type;
			}

			$tcard_class[$skin] = $skin_name." ".$settings["main_animation"]." ".$settings["main_cubicbezier"]." ".$settings['main_frostedglass_main'];
			$tcard_class[$skin] = preg_replace('/\s+/', ' ',$tcard_class[$skin]);

			if(empty($front_background_color[$skin])){
				$front_background_color[$skin] = "";
			}

			$front_class[$skin] = $front_background_color[$skin]." ".$settings['front_frostedglass'];
			$front_class[$skin] = preg_replace('/\s+/', ' ',$front_class[$skin]);

			if(empty($back_background_color[$skin])){
				$back_background_color[$skin] = "";
			}
			
			$back_class[$skin] = $back_background_color[$skin]." ".$settings['back_frostedglass'];
			$back_class[$skin] = preg_replace('/\s+/', ' ',$back_class[$skin]);

			if(!empty($settings['viewIn'])){
				$viewIn[$skin] = 'data-view-in='.$settings['viewIn'].'';

				if(!empty($settings['viewOut'])){
					$viewOut[$skin] = 'data-view-out='.$settings['viewOut'].'';
				}

				if(empty($settings['setOffsetView'])){
					$offsetView[$skin] = 'data-offsetView="200"';
				}else{
					$offsetView[$skin] = 'data-offsetView='.$settings['setOffsetView'].'';
				}
				
			}else{
				$viewIn[$skin] = "";
				$viewOut[$skin] = "";
				$offsetView[$skin] = "";
			}

			require $file_url . "templates/TcardSkins.php";

		}

		if($skin_type == "skin_5"){
			wp_reset_postdata();
		}

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