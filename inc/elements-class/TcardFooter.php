<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/inc/elements
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardFooter
{

	/**
	 * @since    1.0.0
	 */
	private $group;

	/**
	 * @since    1.0.0
	 */
	private $tcardAnimations;

	/**
	 * @since    1.0.0
	 */
	private $group_id;

	/**
	 * @since    1.0.0
	 */
	private $urlfile;

	/**
	 * Construct.
	 * @since    1.0.0
	 */
	public function __construct($group_id, $urlfile){
		
		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';

		$this->group_id = $group_id;

		$this->urlfile = $urlfile;

		$this->group = $wpdb->get_row("SELECT skin_type,settings FROM $tcard_table WHERE group_id = $this->group_id");
		
		require_once TCARD_PATH . "inc/elements-class/TcardAnimations.php";
	}

	/**
	 * @since    1.0.0
	 */ 
	public function show_element($skin,$side,$footerElements){

		global $wpdb;

		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		$elements[$skin]['footer'][$side] = $footerElements;

		$all_settings = $wpdb->get_results("SELECT settings FROM $tcard_skin_table WHERE group_id = $this->group_id");

		$this->tcardAnimations = new TcardAnimations();

		$width = 0;

		if(!empty($elements[$skin]['footer'][$side])) {
			foreach ($elements[$skin]['footer'][$side] as $key => $element) {
				$width++;

				if (!isset($elemNumbers[$element])){
					$elemNumbers[$element]= 0;
				} 
				else{
					$elemNumbers[$element]++;
				}

				$elemNumber = $elemNumbers[$element];

				$group_id = $this->group_id;
				$skin_type = $this->group->skin_type;
				$animations = $this->tcardAnimations;
				$pre_skin = self::check_pre_skins($skin_type);
				$skin_settings = unserialize($all_settings[$skin]->settings);

				$parent = "footer";

				if($skin_type == $pre_skin){
					$no_width_set = "no_width_set";
					$info_list = 3;
				}

				$output = $this->get_elements($skin,$side,$element,$elemNumber,$width);
				$check = $this->check_element($element,$output);
				
				if(!empty($output[$element]) && $output[$element] == "link"){
					$display = "is-set";
				}

				if($element !== "social_list"){
					(!empty($output[$element])) ? $output[$element] : $output[$element] = "";
				}
				

				(!empty($output['animation_in'])) ? $output['animation_in'] : $output['animation_in'] = "";
				(!empty($output['animation_out'])) ? $output['animation_out'] : $output['animation_out'] = "";
				(!empty($output['delay'])) ? $output['delay'] : $output['delay'] = "";
				require $this->urlfile . "templates/elements/TcardFooterElements.php";
			};
		};
	}

	/**
	 * @since    1.0.0
	 */ 
	public function get_elements($skin,$side,$element,$elemNumber,$width){

		global $wpdb;

		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		$output = array();

		if($element == "tc_button"){
			$itemTitle = str_replace("tc_", " ", $element);
		}else{
			$itemTitle = str_replace("_", " ", $element);
		}
		
		
		$all_footer = $wpdb->get_results("SELECT skin_id,footer FROM $tcard_skin_table WHERE group_id = $this->group_id");
		$footer_element = unserialize($all_footer[$skin]->footer);

		$sub_elems = array('tc_button_name','tc_button_link','tc_button_extra','info_title','info_list_title','info_list_text','social_list_order','social_list','gallery_button_text');

		if(!empty($footer_element)){
			foreach ($footer_element as $key => $value) {

				if(!empty($value[$side][$element][$elemNumber])){
					$output[$element] = html_entity_decode(stripslashes($value[$side][$element][$elemNumber]));
				}
				

				foreach ($sub_elems as $key => $sub_elem) {

					if($sub_elem == "tc_button_name" || $sub_elem == "tc_button_extra" || $sub_elem == "info_title"){
						if(!empty($value[$side][$sub_elem][$elemNumber])){
							$output[$sub_elem] = stripslashes($value[$side][$sub_elem][$elemNumber]);	
						}else{
							$output[$sub_elem] = "";
						}
					}
					elseif($sub_elem == "info_list_title" || $sub_elem == "info_list_text" || $sub_elem == "social_list_order" || $sub_elem == "social_list"){
						if(!empty($value[$side][$sub_elem.$elemNumber]))
							$output[$sub_elem] = $value[$side][$sub_elem.$elemNumber];
					}
					else{
						if(!empty($value[$side][$sub_elem][$elemNumber])){
							$output[$sub_elem] = $value[$side][$sub_elem][$elemNumber];
						}else{
							$output[$sub_elem] = "";
						}
					}

			
				}
				
				if(!empty($value[$side]['element_width'][$width - 1])){
					$output['element_width'] = $value[$side]['element_width'][$width - 1];
				}else{
					$output['element_width'] = "";
				}
				

				if(!empty($value[$side]["animation_in"])){
					$output['animation_in']	= $value[$side]["animation_in"];
				}else{
					$output['animation_in'] = "";
				}
				if(!empty($value[$side]["animation_out"])){
						$output['animation_out'] = $value[$side]["animation_out"];
				}else{
					$output['animation_out'] = "";
				}
				if(!empty($value[$side]["delay"])){
						$output['delay'] = $value[$side]["delay"];
				}else{
					$output['delay'] = "";
				}
			}
		}

		if(empty($output['element_width'])){
			$output['element_width'] = "100%";
		}

		if(!empty($output['info_list_title'])){
			$output['info_list'] = count($output['info_list_title']) . " item(s)";
		}	

		if(!empty($output['social_list_order'])){
			$output['list_order'] = count($output['social_list_order']) . " item(s)";
		}

		$output['title'] = $itemTitle;

		$skin_type = $this->group->skin_type;
		if($element == "tc_button" && $skin_type == "skin_4" || $skin_type == "skin_3"){
			$output["tc_button"] = "main";
		}elseif($element == "tc_button" && $skin_type == "skin_6"){
			$output["tc_button"] = "link";
		}

		if($skin_type == self::check_pre_skins($skin_type)){
			$output['tc_col'] = "tc-4";
		}
		return $output;
	}

	/**
	 * @since    1.0.0
	 */ 
	public function check_element($element,$output){
		if($element == "social_list" && !empty($output['list_order'])){
			$text = $output['list_order'];
		}elseif($element == "tc_button" && !empty($output['tc_button_name'])){
			$text = $output['tc_button_name'];
		}
		elseif($element == "info" && !empty($output['info_title'])){
			$text = $output['info_title'];
		}
		else{
			if(!empty($output[$element]))
			$text = $output[$element];
		}
		if(empty($text)){
			$check =  __( 'empty', 'tcard' );
		}else{
			if(!is_array($element)){
				$check = wp_specialchars_decode($text);
				$check = preg_replace ('/<[^>]*>/', ' ', $check);
				$check = strip_tags(mb_strimwidth($check,0, 15,"..."));
			}
		}
		return $check;
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