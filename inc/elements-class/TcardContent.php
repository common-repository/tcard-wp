<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/inc/elements
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardContent
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

		$this->group = $wpdb->get_row("SELECT skin_type,skins_number,settings FROM $tcard_table WHERE group_id = $this->group_id");
		
		require_once TCARD_PATH . "inc/elements-class/TcardAnimations.php";
	}

	/**
	 * @since    1.0.0
	 */ 
	public function show_element($skin,$side,$contentElements,$type_action,$skinCloned){

		global $wpdb;

		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		$elements[$skin]['content'][$side] = $contentElements;

		$all_settings = $wpdb->get_results("SELECT settings FROM $tcard_skin_table WHERE group_id = $this->group_id");

		$this->tcardAnimations = new TcardAnimations();
		
		$width = 0;
		if(!empty($elements[$skin]['content'][$side])) {
			foreach ($elements[$skin]['content'][$side] as $key => $element) {
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

				$settings = unserialize($this->group->settings);

				$output = $this->get_elements($skin,$side,$element,$elemNumber,$width,$type_action,$skinCloned);
				$check = $this->check_element($element,$output);
				$pre_skin = self::check_pre_skins($skin_type);
				$skin_settings = unserialize($all_settings[$skin]->settings);

				$parent = "content";

				if(!empty($output['profile'])){
					$avatar_is_set = "is-set";
				}

				if(!empty($output['profile_btntype']) && $output['profile_btntype'] == "text"){
					$display = "is-set";
				}
				
				if($skin_type == $pre_skin){
					$no_width_set = "no_width_set";
					if($skin_type == "skin_1"){
						$skills_number[$skin][$side][$elemNumber] = 1;
						$item_list = 3;
						$output["skills"] = "bar";
					}elseif($skin_type == "skin_2"){
						$skills_number[$skin][$side][$elemNumber] = 3;
						$output["skills"] = "circle";
					}elseif($skin_type == "skin_3"){
						$item_list = 1;
					}elseif($skin_type == "skin_6"){
						if($side == "back"){
							$item_list = 2;
						}else{
							$item_list = 3;
						}
						$output["skills"] = "bar";
						$skills_number[$skin][$side][$elemNumber] = 3;
					}
					$list_number = 3;
				}
				
				$gallery = $wpdb->get_results("SELECT gallery FROM $tcard_skin_table WHERE group_id = $group_id");
				$gallery = unserialize($gallery[$skin]->gallery);
				
				$after_login = array(
					'user_email' 		=> __( 'Email', 'tcard' ),
					'user_firstname' 	=> __( 'First name', 'tcard' ),
					'user_lastnam' 		=> __( 'Last name', 'tcard' ),
					'display_name' 		=> __( 'Display name', 'tcard' ),
					'description'		=> __( 'Description', 'tcard' ),
					'nickname' 			=> __( 'Nickname', 'tcard' ),
					'user_url' 			=> __( 'Website', 'tcard' )

				);

				if($element !== "login"){
					(!empty($output[$element])) ? $output[$element] : $output[$element] = "";
				}
				
				(!empty($output['animation_in'])) ? $output['animation_in'] : $output['animation_in'] = "";
				(!empty($output['animation_out'])) ? $output['animation_out'] : $output['animation_out'] = "";
				(!empty($output['delay'])) ? $output['delay'] : $output['delay'] = "";
				
				require_once TCARD_PATH . "inc/TcardForms.php";
			    require $this->urlfile . "templates/elements/TcardContentElements.php";
			};
		};
	}

	/**
	 * @since    1.0.0
	 */ 
	public function get_elements($skin,$side,$element,$elemNumber,$width,$type_action,$skinCloned){

		global $wpdb;

		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		$output = array();

		$itemTitle = str_replace("_", " ", $element);
		
		$all_content = $wpdb->get_results("SELECT skin_id,content FROM $tcard_skin_table WHERE group_id = $this->group_id");

		$sub_elems = array('info_title','item','skills_number','skill','skill_percent','list','profile_btntype','profile_btntext','profile_emailtype','profile_emailtext','profile_email','contact','contact_admin_email','contact_item','contact_button','address_email','address_phone','register','register_item','register_button','login','after_login','login_display_title','msjafter_login','logout_login');

		if($type_action == "new-skin"){
			$content_element = unserialize($all_content[$skin]->content);
			if(!empty($content_element)){
				foreach ($content_element as $key => $value) {

					if(!empty($value[$side][$element][$elemNumber])){
						$output[$element] = html_entity_decode(stripslashes($value[$side][$element][$elemNumber]));
					}
					
					foreach ($sub_elems as $key => $sub_elem) {
						if($sub_elem == "info_title" || $sub_elem == "profile_btntext" || $sub_elem == "profile_emailtext" || 
							$sub_elem == "contact_button" || $sub_elem == "register_button" || $sub_elem == "msjafter_login" || 
							$sub_elem == "logout_login"){

								if(!empty($value[$side][$sub_elem][$elemNumber])){
									$output[$sub_elem]	= stripslashes($value[$side][$sub_elem][$elemNumber]);
								}else{
									$output[$sub_elem]	= "";
								}
								
						}
						elseif($sub_elem == "item" || $sub_elem == "skill" || $sub_elem == "skill_percent" || $sub_elem == "list"){
								if($sub_elem == "skill"){
									if(!empty($value[$side]["skills_skill$elemNumber"]))
									$output[$sub_elem]	= $value[$side]["skills_skill$elemNumber"];
								}elseif($sub_elem == "skill_percent"){
									if(!empty($value[$side]["skills_percent$elemNumber"])) 
									$output[$sub_elem]	= $value[$side]["skills_percent$elemNumber"];
								}
								else{
									if(!empty($value[$side][$sub_elem.$elemNumber]))
									$output[$sub_elem]	= $value[$side][$sub_elem.$elemNumber];
								}
						}
						elseif($sub_elem == "contact" || $sub_elem == "contact_item" || $sub_elem == "address_phone" || $sub_elem == "register" || 
							$sub_elem == "register_item" || $sub_elem == "login" || $sub_elem == "after_login"){
							if(!empty($value[$side][$sub_elem]))
							$output[$sub_elem] 	= $value[$side][$sub_elem];
						}
						else{
							if(!empty($value[$side][$sub_elem][$elemNumber]))
							$output[$sub_elem]	= $value[$side][$sub_elem][$elemNumber];
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
		}

		if(empty($output['element_width'])){
			$output['element_width'] = "100%";
		}

		$output['title'] = $itemTitle;
		$skin_type = $this->group->skin_type;

		if($skin_type == self::check_pre_skins($skin_type)){
			$output["skills_number"] = "percent";
			$output['tc_col'] = "tc-4";
		}
		return $output;
	}

	/**
	 * @since    1.0.0
	 */ 
	public function check_element($element,$output){

		if($element == "info" && !empty($output["info_title"])){
			$text = $output["info_title"];
		}
		elseif($element == "list" || $element == "contact" || $element == "register"){
			if(!empty($output[$element]))
			$text = count($output[$element]) ." " . __( 'item', 'tcard' ) . "(s)";
		}
		elseif($element == 'skills' && !empty($output["skill"])){
			$text = count($output["skill"]) ." " . __( 'skill', 'tcard' ) . "(s)";
		}
		elseif($element == "item"){
			if(!empty($output[$element][0]))
			$text = $output[$element][0];
		}
		elseif($element == "login"){
			if(!empty($output[$element][0]))
			$text = $output[$element][0];
		}
		elseif($element == "profile" && !empty($output["profile"])){
			$text = __( 'Is set', 'tcard' );
		}
		else{
			if(!empty($output[$element]))
			$text = $output[$element];
		}
		
		if(empty($text)){
			$check = __( 'empty', 'tcard' );
		}
		else{
			if(!is_array($element)){
				$check = wp_specialchars_decode($text);
				$check = preg_replace ('/<[^>]*>/', ' ', $check);
				$check = strip_tags(stripslashes(mb_strimwidth($check,0, 15,"...")));
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