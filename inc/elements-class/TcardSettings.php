<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/admin/templates
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

class TcardSettings
{

	/**
	 * Set settings for group and skins.
	 * @since    1.0.0
	 */	
	public function settings($group_id,$type, $title, $skin ,$elements,$skin_type){

		global $wpdb;

		$tcard_table = $wpdb->prefix.'tcards';
		$tcard_skin_table = $wpdb->prefix.'tcard_skins';

		if(!empty($group_id)){
			if($type == "group"){
				$output = $wpdb->get_row("SELECT settings FROM $tcard_table WHERE group_id = $group_id");
				$output = unserialize($output->settings);
			}else{
				$output = $wpdb->get_results("SELECT settings FROM $tcard_skin_table WHERE group_id = $group_id");
				$output = unserialize($output[$skin]->settings);
			}
		}

		$title = mb_strimwidth($title, 0, 20, "...");
		($type == "skin") ? $modalType = "settings-skin" : $modalType = "";
		$pre_skin = self::check_pre_skins($skin_type);
		?>
		<div class="tcard-modal modal-settings">
			<div class="tcard-modal-body <?php echo $modalType ?>">
				<div class="tcard-modal-header">
					<h4> <?php echo $title ?> : <?php _e( 'settings', 'tcard' ) ?></h4>
					<?php if($type == "skin") : ?>

						<div class="settings-btns">
							<?php foreach ($elements as $key => $element) :

								($key == "main") ? $curr = "tc-current-side" : $curr = ''; 

								if($skin_type  == $pre_skin && $key !== "social" || 
									$skin_type == $pre_skin && $key == "main" || $key == "front" || $key == "back" || 
									$skin_type !== $pre_skin) : ?>
								<div class="settings-btn <?php echo $curr ?>" data-menu-container="<?php echo $key ?>"><?php echo $key; ?></div>

								<?php endif;
								
							endforeach; ?>
						</div>
						
					<?php endif; ?>
				</div>
				<div class="tcard-modal-content">
				<?php if($type == "group") : ?>
					<table class="table-tc-settings">
						<tbody>
						<?php foreach ($elements as $key => $element) : ?>

							<?php if($key == "randomColor" && $skin_type  == $pre_skin || 
									 $key !== "randomColor" && $skin_type  !== $pre_skin ||
									 $skin_type  == $pre_skin) : ?>

								<tr>
								    <td class="tc-td"><?php echo $element[2]; ?></td>
								    <td class="tc-td2">

								    	<?php if($element[1] == "checkbox") :
											$value = 1;
										else :
											(!empty($output[$key])) ? $output[$key] : $output[$key] = "";
											$value = $output[$key];
										endif;

								    	if($element[0] == "select") : 

								    		if($key == "tcardOn" && $skin_type == "skin_2" || $skin_type == "skin_5"){
								    			$output[$key] = "hover";
								    		}elseif($key == "tcardOn"){
								    			$output[$key] = "button";
								    		}
								    		(!empty($output[$key])) ? $output[$key] : $output[$key] = "";
								    		?>
								    		<select name="<?php echo $type ?>_set[<?php echo $key ?>]">
								    			<?php foreach ($element[1] as $opt => $option) : ?>
								    			<option value="<?php echo $option ?>" <?php selected( $output[$key], $option ); ?>><?php echo $option ?></option>
								    			<?php endforeach; ?>	
								    		</select>

								    	<?php else :

								    		if($element[1] == "checkbox") :
								    			
								    			if(!empty($output[$key]) && $output[$key] == 1) :
						 							$checked = "checked";
						 						else :
						 							$checked = "";
						 						endif; 
						 						(!empty($output[$key])) ? $output[$key] : $output[$key] = "";
						 						?>

								    			<div class="tc-check-settings">
												  <input id="<?php echo $key ?>" type="<?php echo $element[1] ?>" name="<?php echo $type. "_set[".$key ?>]" value="<?php echo $value ?>" <?php echo $checked; ?>>
												  <label for="<?php echo $key ?>"></label>
												</div>
								    		<?php elseif($element[1] == "number") : 
								    			(!empty($output[$key])) ? $output[$key] : $output[$key] = "";
								    			?>
								    			<input type="<?php echo $element[1] ?>" name="<?php echo $type. "_set[".$key ?>]" value="<?php echo $output[$key] ?>">
								    		<?php endif;

								    	 endif; ?>	
								    </td>
								    <td>
								    	<div class="setting-info"><?php echo $element[3] ?></div>
								    </td>
								</tr>

							<?php endif;

						endforeach; ?>
						</tbody>
					</table>
				<?php else :

					foreach ($elements as $key => $element) : 
						if($skin_type  == $pre_skin && $key !== "social" || 
							$skin_type == $pre_skin && $key == "main" || $key == "front" || $key == "back" || 
							$skin_type !== $pre_skin) : ?>

						<div class="tcard-modal-container" data-modal-container="<?php echo $key; ?>">
							<table class="table-tc-settings">
								<tbody>
								<?php foreach ($element as $set => $setting) : 
									if($skin_type == "skin_1" && $set !== "frostedglass" && $set !== "opacity" && $set !== 'frostedglass_image' ||
									$skin_type == "skin_2" && $set !== "frostedglass" && $set !== "opacity" && $set !== 'frostedglass_image' 	||
									$skin_type == "skin_3" && $set !== "frostedglass" && $set !== "opacity" && $set !== 'frostedglass_image' 	||
									$skin_type == "skin_4" && $set !== "frostedglass"  && $set !== "opacity" && $set !== 'frostedglass_image' 	||
									$skin_type == "skin_5" && $set !== "frostedglass" && $set !== "opacity" && $set !== "frostedglass_main" && $set !== "bg_image_header" && $set !== 'frostedglass_image' ||
									$skin_type == "skin_6" && $set !== "frostedglass" && $set !== "frostedglass_main" && $set !== "opacity" && $set !== 'frostedglass_image' ||
									$skin_type !== $pre_skin && $set !== "frostedglass_main") :
									?>
									<tr>
										<?php (!empty($setting[2])) ? $setting[2] : $setting[2] = ""; 
										(!empty($output[$key."_".$set."_bg"])) ? $output[$key."_".$set."_bg"] : $output[$key."_".$set."_bg"] = "";?>
									    <td class="tc-td"><?php echo $setting[2]; ?></td>
									    <td class="tc-td2">
									    <?php (!empty($setting[1]) ? $setting[1] : $setting[1] = "") ?>
							    		<?php if($setting[1] == "checkbox") : 
							    			if(!empty($output[$key."_".$set]) && $output[$key."_".$set] = 1) :
						 						$checked = "checked";
						 					else :
						 						$checked = "";
						 					endif;?>
							    			<div class="tc-check-settings">
												<input class="tcard-input" id="<?php echo $set."_".$key.$skin ?>" type="<?php echo $setting[1] ?>" name="<?php echo $type."_set".$skin. "[" .$key."_".$set ?>]" <?php echo $checked; ?> value="1">
											  	<label for="<?php echo $set."_".$key.$skin ?>"></label>

												<?php if($skin_type !== $pre_skin) : ?>  	
											  		<input type="hidden" class="frostedglassbg tcard-input" name="<?php echo $type."_set".$skin. "[" .$key."_".$set."_bg" ?>]" value="<?php echo $output[$key."_".$set."_bg"] ?>">
											  	<?php endif; ?>

											</div>
							    		<?php elseif($setting[1] == "text") :
							    			(!empty($output[$key."_".$set])) ? $output[$key."_".$set] : $output[$key."_".$set] = "";
							    			if($set == "background_color") : 
							    				if($skin_type !== $pre_skin) : ?>

													<input class="tcard-input tcard-color-picker" type="<?php echo $setting[1] ?>" name="<?php echo $type. "_set".$skin. "[" .$key."_".$set ?>]" value="<?php echo $output[$key."_".$set] ?>">
												<?php else: 
													$colors = array("air-force-blue","alizarin","alizarin-crimson","amaranth","amber","american-rose","android-green","awesome","azure","beige","black","blue","darkblue","ball-blue","bleu-de-france","blue-green","blue-purple","bright-green","canary-yellow","carmine","carolina-blue","cerulean","cerulean-blue","chrome-yellow","cinnabar","cool-black","cornflower-blue","dark-slate-gray","dark-gray","davy-grey","debian-red","deep-magenta","deep-pink","egyptian-blue","electric-yellow","electric-violet","jungle-green","lemon","lemon-lime","lava","lavender-blue","light-sea-green","light-slate-gray","majorelle-blue","medium-persian-blue","magenta","navy","orange","persian-red","prussian-blue","paris-green","rose-madder","royal-blue","saint-blue","sea-blue","screamin-green","shamrock-green","true-blue","teal","yellowgreen") ?>

													<select style="float: left;margin-left: 44px;" class="tcard-input" name="<?php echo $type. "_set".$skin. "[" .$key."_".$set ?>]">
														<?php foreach ($colors as $col => $color) : ?>
															<option value="<?php echo $color ?>" <?php selected( $output[$key."_".$set], $color ); ?> ><?php echo $color ?></option>
														<?php endforeach; ?>	
													</select>
												<?php endif;	
							    			else: ?>

							    				<?php if($set == "opacity") :
							    					$opacity = "opacity_fg";
							    				else :
							    					$opacity = "";
							    				endif; 

							    				if($key == "social") : 
							    					$width = "style='width:auto'";
							    				else :
							    					$width = "";
							    				endif;

							    				(!empty($output[$key."_".$set])) ? $output[$key."_".$set] : $output[$key."_".$set] = ""; ?>

												<input class="tcard-input tcard-image-input <?php echo $opacity ?>" <?php echo $width ?> type="<?php echo $setting[1] ?>" name="<?php echo $type. "_set".$skin. "[" .$key."_".$set ?>]" value="<?php echo $output[$key."_".$set] ?>">

												<?php if($set == "background_image" || $set == "bg_image_content" || $set == "bg_image_header") : ?>
							    					<h4 class="tcard-up-image"> <?php _e( 'Upload Image', 'tcard' ) ?> </h4>
							    				<?php endif;
							    			endif;

							    		elseif($setting[1] == "number") : ?>
							    			<?php (!empty($output[$set])) ? $output[$set] : $output[$set] = ""; ?>
							    			<input class="tcard-input" type="<?php echo $setting[1] ?>" name="<?php echo $type. "_set".$skin. "[" .$set ?>]" value="<?php echo $output[$set] ?>">

										<?php elseif($setting[0] == "select") : 

											if($setting[1] == "viewIn") :	
												(!empty($output[$set])) ? $output[$set] : $output[$set] = "";
												$animations_in = array('shake','headShake','swing','tada','wobble','jello','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','flipInX','flipInY','lightSpeedIn','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','hinge','jackInTheBox','rollIn','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','slideInDown','slideInLeft','slideInRight','slideInUp'
												);?>

												<select class="tcard-input" name="<?php echo $type. "_set".$skin. "[" .$set ?>]">
													<option></option>
													<?php foreach ($animations_in as $animation) : ?>
								    					<option value="<?php echo $animation ?>" <?php selected( $output[$set], $animation ); ?>><?php echo $animation ?></option>
								    				<?php endforeach; ?>
									    		</select>

									    	<?php elseif($setting[1] == "viewOut") :
									    		(!empty($output[$set])) ? $output[$set] : $output[$set] = "";
									    		$animations_out = array('shake','headShake','swing','tada','wobble','jello','bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight','bounceOutUp','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp','fadeOutUpBig','flipOutX','flipOutY','lightSpeedOut','rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','hinge','jackInTheBox','rollOut','zoomOut','zoomOutDown','zoomOutLeft','zoomOutRight','zoomOutUp','slideOutDown','slideOutLeft','slideOutRight','slideOutUp'
									    		);?>

												<select class="tcard-input" name="<?php echo $type. "_set".$skin. "[" .$set ?>]">
													<option></option>
													<?php foreach ($animations_out as $animation) : ?>
								    					<option value="<?php echo $animation ?>" <?php selected( $output[$set], $animation ); ?>><?php echo $animation ?></option>
								    				<?php endforeach; ?>
									    		</select>	

											<?php else:
												(!empty($output[$set])) ? $output[$set] : $output[$set] = "";
												if($set == "extra_large"){
													$col = "col-xl-";
												}
												elseif($set == "large"){
													$col = "col-lg-";
												}
												elseif($set == "medium"){
													$col = "col-md-";
												}
												elseif($set == "small"){
													$col = "col-sm-";
												}
												elseif($set == "extra_small"){
													$col = "col-xs-";
													
												}

												if($set == "extra_small"){
													$inherit = "";
												}else{
													$inherit = "Inherit";
												}?>

								    			<select class="tcard-input" name="<?php echo $type. "_set".$skin. "[" .$set ?>]">

								    				<?php if(is_array($setting[1])) :

									    				foreach ($setting[1] as $an) : ?>
									    					<option value="<?php echo $an ?>" <?php selected( $output[$set], $an ); ?>><?php echo $an ?></option>
									    				<?php endforeach;

								    				else : 
								    					if(!empty($inherit)) :?>
								    						<option value="<?php echo $inherit; ?>"><?php echo $inherit; ?></option>
								    					<?php endif;
								    					
							    						for ($i = $setting[1]; $i > 0; $i=$i-1) : ?>
									    					<option value="<?php echo $col.$i ?>" <?php selected( $output[$set],$col.$i ); ?>><?php echo $col.$i ?></option>
									    				<?php endfor; 
								    					
									    			endif;?>

									    		</select>	

									    	<?php endif; ?>
								    	<?php elseif($setting[0] == "title") : ?>	
								    		<h3><?php _e( 'Set columns', 'tcard' ) ?></h3>
							    		<?php endif; ?>
									    </td>
									    <?php (!empty($setting[3])) ? $setting[3] : $setting[3] = ""; ?>
									    <td><div class="setting-info"><?php echo $setting[3] ?></div></td>
									</tr>
								<?php endif;
								endforeach; ?>
								</tbody>
							</table>
						</div>


					<?php endif;

					endforeach;
					
				endif; ?>

				</div>
				<div class="tcard-modal-footer">
					<div class="tcard-close-modal">
						Close
					</div>
				</div>
			</div>
		</div>
		<?php
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