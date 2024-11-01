<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/admin/templates/elements
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

 if (!defined('ABSPATH')) die('No direct access allowed');

?>

<div class="tcard-element <?php echo $output['tc_col']. " " .$element ?>" data-element="<?php echo $element. "-" . ($elemNumber + 1) ?>">
	<input class="tcard-input" type="hidden" name="elements<?php echo $skin. "_" .$side ?>[header][]" value="<?php echo esc_attr($element) ?>">
	<div class="tcard-element-bar <?php echo $no_width_set; ?>">

		<?php if($element == "header_title" || $element == "info" || $element == "profile" || $element == "slider") :
			 echo $output['title'] ?> : <span><?php echo $check ?></span>
		<?php elseif($element == "gallery_button") : ?>
			Button : <span class="icon-tc-button"><i class='fas fa-camera'></i></span>
		<?php elseif($element == "social_button") : ?>
			Button : <span class="icon-tc-button"><i class="fas fa-share-alt"></i></span>
		<?php else: ?>
			<h4><?php _e( 'Button:', 'tcard' ) ?> </span> 
				<span class="tcard-button-type <?php echo $element ?>">
					<span class="tcard-btn-line"></span>
					<span class="tcard-btn-line"></span>
					<span class="tcard-btn-line"></span>
					<span class="tcard-btn-line"></span>
					<span class="tcard-btn-line"></span>
					<span class="tcard-btn-line"></span>
					<span class="tcard-btn-line"></span>
					<span class="tcard-btn-line"></span>
					<span class="tcard-btn-line"></span>
				</span>
			</h4>
		<?php endif; ?>

	</div>
	<div class="tcard-modal">
		<div class="tcard-modal-body modal-elements">
			<div class="tcard-modal-header">
				<h4><?php _e( 'Header:', 'tcard' ) ?> <?php echo $output['title'] ?></h4>
			</div>
			<div class="tcard-modal-content <?php echo $element ?>">
				<?php if($element === "header_title") : ?>

					<div class="tcard-modal-item">
						<h4><?php _e( 'Title:', 'tcard' ) ?> </h4> 
						<input class="tcard-input tc-input-title" type="text" name="header<?php echo $skin. "_" .$side. "[" .$element ?>][]" value="<?php echo $output[$element] ?>">
					</div>

					<div class="tcard-modal-item">

						<?php echo $animations->set_animation( 'header',$side, $skin, $output['animation_in'], $output['animation_out'] ) .
							$animations->set_delay( 'header',$side, $skin, $output['delay'] ) ?>
					</div>

				<?php elseif($element === "info") : 

					(!empty($output["info_title"])) ? $output["info_title"] : $output["info_title"] = "";?>

					<div class="tcard-modal-item">
						<div class="tcard-modal-item-inner">
							<h4><?php _e( 'Title:', 'tcard' ) ?> </h4> 
							<input class="tcard-input tc-input-title" type="text" name="header<?php echo $skin. "_" .$side. "[" .$element ?>_title][]" value="<?php echo $output["info_title"] ?>">
						</div>
						<?php echo $animations->set_delay( 'header',$side, $skin, $output['delay'] ); ?>
					</div>

					<div class="tcard-modal-item">
						<div class="tcard-modal-item-inner">
							<h4 class="tc-modal-editor-title"><?php _e( 'Description:', 'tcard' ) ?> </h4> 
							<textarea id="tc-header-editor-<?php echo $side. "_" .$element.$skin.$elemNumber; ?>" class="tcard-textarea tcard-input" type="text" name="header<?php echo $skin. "_" .$side. "[" .$element ?>][]"><?php echo $output[$element] ?></textarea>
						</div>
						<?php echo $animations->set_delay( 'header',$side, $skin, $output['delay'] ); ?>
					</div>
					<div class="tcard-modal-item">
						<?php echo $animations->set_animation( 'header',$side, $skin, $output['animation_in'], $output['animation_out'] ); ?>
					</div>
											
				<?php elseif($element === "profile") : ?>

					<div class="tcard-modal-item">
						<div class="tcard-modal-item-inner">
							<h4 class="tcard-with-em"><?php _e( 'Image:', 'tcard' ) ?> <br> <em class="tcard-em"><?php printf(__( 'Image size: %s', 'tcard' ), "80 X 80"); ?></em> </h4>
							<input class="tcard-input tcard-image-input" type="text" name="header<?php echo $skin. "_" .$side. "[" .$element ?>][]" value="<?php echo $output[$element] ?>">
							<h4 class="tcard-btn-style tcard-up-image"> Upload Image </h4>
						</div>
						<div class="tcard-profile-image <?php echo $avatar_is_set ?>">
							<img class="tcard-avatar-src" src="<?php echo esc_url($output[$element]) ?>">
						</div>
						<?php echo $animations->set_animation( 'header',$side, $skin, $output['animation_in'], $output['animation_out'] ) .
							$animations->set_delay( 'header',$side, $skin, $output['delay'] ) ?>
					</div>

					<div class="tcard-modal-item">
						<div class="tcard-modal-item-inner">
							<h4 class="tcard-with-em"><?php _e( 'Button Type:', 'tcard' ) ?> <br> <em class="tcard-em">If the gallery exists</em></h4> 
							<select class="tcard-input tc-show-input" name="header<?php echo $skin. "_" .$side. "[" .$element ?>_btntype][]">
								<option value="icon" <?php selected( $output['profile_btntype'], 'icon' ); ?>>Icon</option>
								<option value="text" <?php selected( $output['profile_btntype'], 'text' ); ?>>Text</option>
							</select>
							<input class="tcard-input tchp_text_btn <?php echo $display ?>" type="text" name="header<?php echo $skin. "_" .$side. "[" .$element ?>_btntext][]" value="<?php echo $output['profile_btntext'] ?>" style="margin-top: 10px;">
						</div>
						<?php echo $animations->set_animation( 'header',$side, $skin, $output['animation_in'], $output['animation_out'] ) .
							$animations->set_delay( 'header',$side, $skin, $output['delay'] ) ?>
					</div>

					<div class="tcard-modal-item">
						<div class="tcard-modal-item-inner">
							<h4><?php _e( 'Email:', 'tcard' ) ?> </h4> 
							<input class="tcard-input" type="text" name="header<?php echo $skin. "_" .$side. "[" .$element ?>_email][]" value="<?php echo $output['profile_email'] ?>">
						</div>
						<div class="tcard-modal-item-inner">
							<h4 class="tcard-with-em"><?php _e( 'Button Type:', 'tcard' ) ?> <br> <em class="tcard-em"><?php _e( 'If is an email set', 'tcard' ) ?></em></h4> 
							<select class="tcard-input tc-show-input" name="header<?php echo $skin. "_" .$side. "[" .$element ?>_emailtype][]">
								<option value="icon" <?php selected( $output['profile_emailtype'], 'icon' ); ?>>Icon</option>
								<option value="text" <?php selected( $output['profile_emailtype'], 'text' ); ?>>Text</option>
							</select>
							<input class="tcard-input tchp_text_btn <?php echo $display ?>" type="text" name="header<?php echo $skin. "_" .$side. "[" .$element ?>_emailtext][]" value="<?php echo $output['profile_emailtext'] ?>" style="margin-top: 10px;">
						</div>
						<?php echo $animations->set_animation( 'header',$side, $skin, $output['animation_in'], $output['animation_out'] ) .
							$animations->set_delay( 'header',$side, $skin, $output['delay'] ) ?>
					</div>

				<?php elseif ($element == "button_four_line"  	|| 
						$element == "button_three_line" 		|| 
						$element == "button_arrow"  			||
						$element == "button_squares" 			||
						$element == "gallery_button"			||
						$element == "social_button"				) : 

						if($element == "social_button") : ?>

							<div class="tcard-modal-item">
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">facebook</div>
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">twitter</div>
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">google+</div>
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">instagram</div>
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">pinterest</div>
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">reddit</div> 
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">linkedin</div> 
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">tumblr</div>
								<div class="tc-list tcard-add-item" data-itemnum="<?php echo $elemNumber ?>">flickr</div>  
							</div>

						
							<?php if(!empty($output['social_button_order'])) :

								foreach ($output['social_button_order'] as $key => $list_item) :

									if($list_item == "google+") :
										$icon = "google-plus-square";
									elseif($list_item == "instagram" || $list_item == "linkedin" || $list_item == "flickr") :
										$icon = "$list_item";	
									else :
										$icon = "$list_item-square";
									endif; 

									if(!empty($output[$element][$key])){
										$social_button_text = stripslashes($output[$element][$key]);
									}else{
										$social_button_text = "";
									}
									?>

									<div class="tcard-modal-item">
										<div class="tcard-modal-item-inner">
											<input class="tcard-input" type="hidden" name="header<?php echo $skin. "_" .$side. "[" .$element. "_order" .$elemNumber ?>][]" value="<?php echo esc_attr($list_item) ?>">
											<h4><i class="fab fa-<?php echo $icon; ?>"></i> </h4>
											<input class="tcard-input" placeholder="<?php echo $list_item; ?> username" type="text" name="header<?php echo $skin. "_" .$side. "[" .$element.$elemNumber ?>][]" value="<?php echo $social_button_text; ?>">
											<h4 class="tcard-btn-style tcard-remove-item"></h4>
										</div>
									</div>
								<?php endforeach;
							endif;
			
						endif;?>

						<div class="tcard-modal-item">
							<?php echo $animations->set_animation( 'header',$side, $skin, $output['animation_in'], $output['animation_out'] ) .
							$animations->set_delay( 'header',$side, $skin, $output['delay'] ) ?>
						</div>
				<?php endif; ?>
			</div>
			<div class="tcard-modal-footer">
				<div class="tcard-close-modal">Close</div>
			</div>
		</div>
	</div>
</div>
