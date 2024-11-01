<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/admin
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardSaveData
{
  
	/**
	 * Save all data. 
	 * @since    1.0.0
	 */    
    public static function save(){

        global $wpdb;

        $tcard_table = $wpdb->prefix.'tcards';
		$tcard_skin_table = $wpdb->prefix.'tcard_skins';
       
        $group_id = sanitize_text_field($_POST['group_id']);

        if (isset( $_POST['tcard_group_name'] )) {
            $title = htmlspecialchars($_POST['tcard_group_name']);
            $modified_date = current_time( 'mysql' );
        }

		$all_group_set = array();

        if(isset($_POST['group_set'])){
            $settings = $_POST['group_set'];

            foreach ($settings as $key => $setting) {
            	$all_group_set[$key] = sanitize_text_field($setting);
            }

            $checkboxes = array( 'group_name', 'tcardFlip', 'animationOneTime', 'randomColor', 'viewIn', 'viewOut', 'tcardGallery', 'autocomplete');

            foreach ( $checkboxes as $checkbox ) {
                if ( isset( $settings[$checkbox] ) && $settings[$checkbox] == 1 ) {
                    $settings[$checkbox] = 1;
                } else {
                    $settings[$checkbox] = 0;
                }

                $all_group_set[$checkbox] = sanitize_text_field($settings[$checkbox]);
            }
        }

        $all_group_set = serialize($all_group_set);

        $wpdb->query( $wpdb->prepare("INSERT INTO $tcard_table ( group_id, title, modified, settings ) 
                VALUES ( %d, %s, %s , %s )
                ON DUPLICATE KEY 
                UPDATE title = %s, modified = %s, settings = %s", 
                $group_id,$title,$modified_date,$all_group_set,$title,$modified_date,$all_group_set
            ) 
        );

		$get_skin_id = $_POST["skin_id"];

		foreach ($get_skin_id as $skin => $skin_id) {
			$rows = array(
		        array(
		            'skin_id'	 	=> sanitize_text_field( $skin_id ),
		            'closed' 		=> sanitize_text_field( $_POST["tcard_check_order$skin"] ),
		            'elements'		=> self::order($skin),
		            'header'		=> self::elements("header",$skin),
		            'content'		=> self::elements("content",$skin), 
		            'footer'		=> self::elements("footer",$skin),
		            'gallery'		=> self::gallery($skin), 
		            'settings'		=> self::skin_settings($skin,$group_id)  
		        )
		    );
		    foreach( $rows as $row ){
				$wpdb->query( $wpdb->prepare("INSERT INTO $tcard_skin_table ( skin_id, closed , elements, header, content, footer, gallery, settings ) 
	                	VALUES ( %d, %d, %s, %s, %s, %s, %s, %s )
	                	ON DUPLICATE KEY 
	                	UPDATE closed = %d, elements = %s, header = %s, content = %s, footer = %s, gallery = %s, settings = %s", 
	                	$row['skin_id'],$row['closed'],$row['elements'],$row['header'],$row['content'],$row['footer'],$row['gallery'],$row['settings'],$row['closed'],$row['elements'],$row['header'],$row['content'],$row['footer'],$row['gallery'],$row['settings']
		            ) 
		        ); 	 
	    	}
		}
    }


	/**
	 * Save order of elements. 
	 * @since    1.0.0
	 */ 
    public static function order($skin){

    	if(isset($_POST['elements'.$skin.'_front'])){
			foreach ($_POST['elements'.$skin.'_front'] as $key => $value) {
				foreach ($_POST['elements'.$skin.'_front'][$key] as $i => $value) {
					if(!empty($value))
					$order[$skin][$key]['front'][$i] = sanitize_text_field($value);
				}
			}	
		}

		if(isset($_POST['elements'.$skin.'_back'])){
			foreach ($_POST['elements'.$skin.'_back'] as $key => $value) {
				foreach ($_POST['elements'.$skin.'_back'][$key] as $i => $value) {
					if(!empty($value))
					$order[$skin][$key]['back'][$i] = sanitize_text_field($value);
				}
			}	
		}

	    (empty($order)) ? $order = "" : $order = serialize($order);

    	return $order;
    }

	/**
	 * Save all elements. 
	 * @since    1.0.0
	 */ 
    public static function elements($col,$skin){

		if(isset($_POST[$col.$skin.'_front'])){
			foreach ($_POST[$col.$skin.'_front'] as $key => $value) {		
				foreach ($_POST[$col.$skin.'_front'][$key] as $i => $value) {
					if(!empty($value))

						if($key == "address" || $key == "info" || $key == "ellipsis_text" || strrchr($key,"slider")){
							
							$elements[$skin]['front'][$key][$i] = htmlspecialchars($value);

						}elseif($key == "address_email" || $key == "profile_email"){

							$elements[$skin]['front'][$key][$i] = sanitize_email($value);

						}else{
							$elements[$skin]['front'][$key][$i] = sanitize_text_field($value);
						}
				}
			}
		}

		if(isset($_POST[$col.$skin.'_back'])){
			foreach ($_POST[$col.$skin.'_back'] as $key => $value) {
				foreach ($_POST[$col.$skin.'_back'][$key] as $i => $value) {
					if(!empty($value))

						if($key == "address" || $key == "info" || $key == "ellipsis_text" || strrchr($key,"slider")){

							$elements[$skin]['back'][$key][$i] = htmlspecialchars($value);

						}elseif($key == "address_email" || $key == "profile_email"){

							$elements[$skin]['back'][$key][$i] = sanitize_email($value);

						}else{
							$elements[$skin]['back'][$key][$i] = sanitize_text_field($value);
						}
				}
			}	
		}

    	(empty($elements)) ? $elements = "" : $elements = serialize($elements);

    	return $elements;
    }

	/**
	 * Save settings for skin. 
	 * @since    1.0.0
	 */ 
    public static function skin_settings($skin,$group_id){

    	$skin_settings = $_POST["skin_set$skin"];
    	if(isset($skin_settings)){
	    	foreach ($skin_settings as $key => $setting) {
	    		if(!empty($setting))
	    		$all_settings[$key] = sanitize_text_field($setting);
	    	}
    	}

    	$checkboxes = array( 'front_frostedglass', 'back_frostedglass','main_cubicbezier','main_frostedglass_main');

        foreach ( $checkboxes as $checkbox ) {
            if ( isset( $skin_settings[$checkbox] ) && $skin_settings[$checkbox] == 1 ) {
                $skin_settings[$checkbox] = 1;
            } else {
                $skin_settings[$checkbox] = 0;
            }
            $all_settings[$checkbox] = sanitize_text_field($skin_settings[$checkbox]);
        }
       
        $all_settings["skin_index"] = $skin;
        $all_settings['group_id'] = $group_id;

    	(empty($all_settings)) ? $all_settings = "" : $all_settings = serialize($all_settings);

    	return $all_settings;
    }

	/**
	 * Save gallery images. 
	 * @since    1.0.0
	 */ 
    public static function gallery($skin){

    	$skin_images = $_POST["tcg_gallery$skin"];
    	$all_images = array();
    	if(isset($skin_images)){
	    	foreach ($skin_images as $key => $images) {
	    		if($key == "image"){
	    			foreach ($images as $image) {
	    				if(!empty($image))
	    				$all_images[$key][] = sanitize_text_field($image);
	    			}
	    		}else{
	    			$all_images[$key] = sanitize_text_field($images);
	    		}
	    	}
    	}

    	(empty($all_images)) ? $all_images = "" : $all_images = serialize($all_images);

    	return $all_images;
    }
}