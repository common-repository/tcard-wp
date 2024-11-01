<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/public/templates
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

if($skin_type !== "skin_5") : ?>
<div class="<?php echo $settings["column"] ?>">
	<div class="tcard tcard-<?php echo $skin." ".$tcard_class[$skin] ?>" <?php echo $viewIn[$skin]; ?> <?php echo $viewOut[$skin]; ?> <?php echo $offsetView[$skin]; ?>>
		<div class="tcard-inner">
			<?php if(!empty($headerElements['front']) || !empty($contentElements['front']) || !empty($footerElements['front']) || $skin_type == "skin_2" ) : ?>
					<div class="tcard-front <?php echo $front_class[$skin];?>" <?php echo $front_background[$skin]; ?>>
					<?php if(!empty($headerElements['front'])) :
						if ( $login && !is_user_logged_in() ||
						   !$login && is_user_logged_in() || 
						   !is_user_logged_in()) : ?>
						   <?php (!empty($settings["front_bg_image_header"]) ? $settings["front_bg_image_header"] : $settings["front_bg_image_header"] = "") ?>
							<div class="tcard-header" <?php echo $settings["front_bg_image_header"]; ?>>
								<?php $elementsController->tcardHeader->show_element($skin,'front',$headerElements['front']) ?>
							</div>

						<?php endif; 
					endif;

					if(!empty($contentElements['front'])) : ?>
						<?php (!empty($settings["front_bg_image_content"]) ? $settings["front_bg_image_content"] : $settings["front_bg_image_content"] = "") ?>
						<div class="tcard-content" <?php echo $settings["front_bg_image_content"]; ?>>
							<?php ($type_action == "new-skin") ? $skinCloned = null : $skinCloned;
							 $elementsController->tcardContent->show_element($skin,'front',$contentElements['front'],$type_action,$skinCloned ) ?>
						</div>
					<?php endif;

					if(!empty($footerElements['front'])) :

						if ( $login && !is_user_logged_in() ||
						   !$login && is_user_logged_in() || 
						   !is_user_logged_in()) : ?>

							<div class="tcard-footer">
								<?php $elementsController->tcardFooter->show_element($skin,'front',$footerElements['front']) ?>
							</div>

						<?php endif; 
					endif;?>

				</div>
			<?php endif;

			if ( $login && !is_user_logged_in() ||
					!$login && is_user_logged_in() || 
		   		    !is_user_logged_in()) :

				if(!empty($headerElements['back']) || !empty($contentElements['back']) || !empty($footerElements['back'])) : ?>
					<div class="tcard-back <?php echo $back_class[$skin]; ?>" <?php echo $back_background[$skin]; ?>>

						<?php if(!empty($headerElements['back'])) : ?>
							<?php (!empty($settings["back_bg_image_header"]) ? $settings["back_bg_image_header"] : $settings["back_bg_image_header"] = "") ?>
							<div class="tcard-header" <?php echo $settings["back_bg_image_header"]; ?>>
								<?php $elementsController->tcardHeader->show_element($skin,'back',$headerElements['back']) ?>
							</div>
						<?php endif;

						if(!empty($contentElements['back'])) : ?>
						<?php (!empty($settings["back_bg_image_content"]) ? $settings["back_bg_image_content"] : $settings["back_bg_image_content"] = "") ?>
							<div class="tcard-content" <?php echo $settings["back_bg_image_content"]; ?>>
								<?php ($type_action == "new-skin") ? $skinCloned = null : $skinCloned;
								$elementsController->tcardContent->show_element($skin,'back',$contentElements['back'],$type_action,$skinCloned) ?>
							</div>
						<?php endif;


						if(!empty($footerElements['back'])) : ?>
							<div class="tcard-footer">
								<?php $elementsController->tcardFooter->show_element($skin,'back',$footerElements['back']) ?>
							</div>
						<?php endif; ?>

					</div>
				<?php endif;

			endif; ?>
		</div>
	</div>
</div>
<?php else : ?>
	<?php if(!empty($posts[$skin]->ID)) : ?>
		<div class="<?php echo $settings["column"] ?>">
			<div class="tcard tcard-<?php echo $skin." ".$tcard_class[$skin] ?>" <?php echo $viewIn[$skin]; ?> <?php echo $viewOut[$skin]; ?> <?php echo $offsetView[$skin]; ?>>
				<div class="tcard-inner">
					<div class="tcard-front <?php echo $front_background_color[$skin] ?>"<?php echo $front_background[$skin]; ?>>

						<div class="tcard-header">
							<div class="tcard-header-title tc-4">
							    <h2>Posted by - <span><?php echo get_the_author_meta('display_name',$posts[$skin]->post_author); ?></span></h2>
							</div>
						</div>
						<div class="tcard-content" <?php echo $backgroundPost; ?>>
							
						</div>

						<div class="tcard-footer">
							<div class="tc-post-title">
								<?php echo mb_strimwidth(get_the_title($posts[$skin]->ID),0, 40,"..."); ?>
							</div>
						</div>

					</div>
					<div class="tcard-back <?php echo $back_background_color[$skin] ?>" <?php echo $back_background[$skin]; ?>>
						<?php (!empty($settings["back_bg_image_header"])) ? $settings["back_bg_image_header"] : $settings["back_bg_image_header"] = ""; ?>
						<div class="tcard-content" <?php echo $settings["back_bg_image_header"]; ?>>
							<?php (!empty($output['tc_col'])) ? $output['tc_col'] : $output['tc_col'] = "tc-4"; ?>
							<div class="tcard-ellipsis_text <?php echo $output['tc_col']; ?>" data-text="ellipsis">
								<?php echo html_entity_decode($posts[$skin]->post_content); ?>
							</div>
						</div>

						<div class="tcard-footer">

							<div class="tc-button-post">
		                    	<a href="<?php echo get_permalink($posts[$skin]->ID); ?>">Read More</a>
		     				</div>

		     				<div class="tc-post-date">
		     					Date: <?php echo get_the_date( "j - m - Y", $posts[$skin]->ID ); ?> | 
		     					Comments: <?php echo get_comments_number( $posts[$skin]->ID ); ?>
		     				</div>
						</div>

					</div>
				</div>
			</div>
		</div>	
	<?php endif; ?>
<?php endif; ?>	