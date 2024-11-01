<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/admin/templates
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

?>
<div class="tcard-row <?php echo $closed; ?>">
	<div class="tcard-row-bar">
		<span><?php echo $countSkin; ?>.<span class="tcard-skin-order"><?php echo $skin + 1; ?> </span> 
		<?php if($skin_type !== "skin_5") : ?>
		<span class="tcard-arrow">
		  	<input class="tcard_reorder tcard_check" type="checkbox" name="tcard_check_order<?php echo $skin; ?>" value="1" <?php checked( $get_skins[$skin]->closed, 1 , true ); ?>>
				<label></label>
		</span>
		<span class="skin-cloned-after"></span>
		<?php endif; ?>
	</span>
	<div class="tcard-row-bar-btns">
		<?php if($skin_type !== $pre_skin) : ?>
			<span class="tcard-clone-skin"><i class="fas fa-clone"></i></span>
		<?php endif; ?>		
		<span class="tcard-settings"><i class="fas fa-cog"></i></span>
		<?php $elementsController->tcardSettings->settings(
	 				$group_id,
	 				'skin',
	 				$skin_type. "." . ($skin + 1),
	 				$skin,
	 				$settings = array(
	 					'main' => array(
	 						'main_animation'		=> array('select',array(
	 							'flip-y to-left',
	 							'flip-y to-right',
	 						    'flip-x to-top',
	 						    'flip-x to-bottom',
	 						    'rotate-y to-left',
	 						    'rotate-y to-right',
								'rotate-x to-top',
								'rotate-x to-bottom',
	 						),'Main animation',''),
	 						'cubicbezier'			=> array(
	 							'input','checkbox',
	 							__( 'Cubicbezier' , 'tcard' ),
	 							__( 'Default: false' , 'tcard' )
	 						),
	 						'frostedglass_main'		=> array(
	 							'input','checkbox',
	 							__( 'Frostedglass' , 'tcard' ),
	 							__( 'Default: false' , 'tcard' )
	 						),
	 						'viewIn'				=> array(
	 							'select','viewIn',
	 							__( 'Viewport In' , 'tcard' ),
	 							sprintf(
								    __( 'Default: false %s if is empty', 'tcard' ),
								    "<br>"
								)
	 						),
	 						'viewOut'				=> array(
	 							'select','viewOut',
	 							__( 'Viewport Out' , 'tcard' ),
	 							sprintf(
								    __( 'Default: false %s if Viewport In is empty', 'tcard' ),
								    "<br>"
								)
	 						),
		 					'setOffsetView'			=> array(
		 						'input','number',
		 						__( 'Set viewIn' , 'tcard' ),
		 						sprintf(
								    __( 'Default: %s', 'tcard' ),
								    "200px"
								)
		 					),
		 					''						=> array('title'),
		 					'extra_small'			=> array(
		 						'select',12,
		 						__( 'Extra small' , 'tcard' ),
		 						sprintf(
								    __( '%s', 'tcard' ),
								    "v3+ = (<768px)"
								)
		 					),
		 					'small'					=> array(
		 						'select',12,
		 						__( 'Small' , 'tcard' ),
		 						sprintf(
								    __( '%s', 'tcard' ),
								    "v3+ = (≥768px)"
								)
		 					),
		 					'medium'				=> array(
		 						'select',12,
		 						__( 'Medium' , 'tcard' ),
		 						sprintf(
								    __( '%s', 'tcard' ),
								    "v3+ = (≥992px)"
								)
							),	
		 					'large'					=> array(
		 						'select',12,
		 						__( 'Large' , 'tcard' ),
		 						sprintf(
								    __( '%s', 'tcard' ),
								    "v3+ = (≥1200px)"
								)
		 					)
	 					),
	 					'front' => array(
	 						'background_color'		=> array(
	 							'input','text',
	 							__( 'Background color' , 'tcard' ),''
	 						),
	 						'background_image'		=> array(
	 							'input','text',
	 							__( 'Background image' , 'tcard' ),''
	 						),
	 						'frostedglass'			=> array(
	 							'input','checkbox',
	 							__( 'Frostedglass' , 'tcard' ),
	 							__( 'Default: false' , 'tcard' )
	 						),
	 						'frostedglass_image'			=> array(
	 							'input','checkbox',
	 							__( 'Frostedglass background image' , 'tcard' ),
	 							__( 'Default: false' , 'tcard' )
	 						),
	 						'opacity'			=> array(
	 							'input','text',
	 							__( 'Frostedglass opacity' , 'tcard' ),
	 							__( 'Default: 0.5' , 'tcard' )
	 						),
	 						'bg_image_content'		=> array(
	 							'input','text',
	 							__( 'Background image Content' , 'tcard' ),''
	 						),
	 						'bg_image_header'		=> array(
	 							'input','text',
	 							__( 'Background image Header' , 'tcard' ),''
	 						)
	 					),
	 					'back' => array(
	 						'background_color'		=> array(
	 							'input','text',
	 							__( 'Background color' , 'tcard' ),''
	 						),
	 						'background_image'		=> array(
	 							'input','text',
	 							__( 'Background image' , 'tcard' ),''
	 						),
	 						'frostedglass'			=> array(
	 							'input','checkbox',
	 							__( 'Frostedglass' , 'tcard' ),
	 							__( 'Default: false' , 'tcard' )
	 						),
	 						'frostedglass_image'	 => array(
	 							'input','checkbox',
	 							__( 'Frostedglass background image' , 'tcard' ),
	 							__( 'Default: false' , 'tcard' )
	 						),
	 						'opacity'			=> array(
	 							'input','text',
	 							__( 'Frostedglass opacity' , 'tcard' ),
	 							__( 'Default: 0.5' , 'tcard' )
	 						),
	 						'bg_image_content'		=> array(
	 							'input','text',
	 							__( 'Background image Content' , 'tcard' ),''
	 						),
	 						'bg_image_header'		=> array(
	 							'input','text',
	 							__( 'Background image Header' , 'tcard' ),''
	 						)
	 					),
	 					'social' => array(
	 						'twitter_username'				=> array(
	 							'input','text',
	 							__( 'Twitter username' , 'tcard' ),
	 							__( 'Username without @ character' , 'tcard' )
	 						),
							'twitter_token'				=> array(
	 							'input','text',
	 							__( 'Twitter access token' , 'tcard' )
	 						),
	 						'twitter_stoken'			=> array(
	 							'input','text',
	 							__( 'Twitter access token secret' , 'tcard' )
	 						),
	 						'twitter_key'				=> array(
	 							'input','text',
	 							__( 'Twitter consumer key' , 'tcard' )
	 						),
	 						'twitter_csecret'			=> array(
	 							'input','text',
	 							__( 'Twitter consumer secret' , 'tcard' )
	 						),	
	 					)
	 				),
	 				$skin_type
	 			);?>
		<span class="tcard-delete-skin"><i class="fas fa-trash-alt"></i></span>
	</div>
	</div>
	<div class="tcard-skin">
		<input type="hidden" class="tcard_skin_id" name="skin_id[]" value="<?php echo $get_skins[$skin]->skin_id ?>">
		<?php if(!empty($headerElements)) : ?>
			<div class="tcard-item tcard-header" data-item="tcard-header">
				<div class="tcard-item-bar"><span class="tcard-item-title">Header</span></div>
				<?php if(!empty($headerElements['front'])) : ?>
					<div class="tcard-main-elem front-side" data-side="front">
						<div class="tcard-item-bar">Front Side</div>
						<div class="tcard-item-elements">
							<?php $elementsController->tcardHeader->show_element( $skin,'front',$headerElements['front'] ) ?>	
						</div>
					</div>
				<?php endif; ?>

				<?php if(!empty($headerElements['back'])) : ?>
					<div class="tcard-main-elem back-side" data-side="back">
						<div class="tcard-item-bar">Back Side</div>
						<div class="tcard-item-elements">
							<?php $elementsController->tcardHeader->show_element( $skin,'back',$headerElements['back'] ) ?>	
						</div>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>

		<?php if(!empty($contentElements)) : ?>
			<div class="tcard-item tcard-content" data-item="tcard-content">
				<div class="tcard-item-bar"><span class="tcard-item-title">Content</span></div>

				<?php if(!empty($contentElements['front'])) : ?>
					<div class="tcard-main-elem front-side" data-side="front">
						<div class="tcard-item-bar">Front Side</div>
						<div class="tcard-item-elements">
							<?php echo $elementsController->tcardContent->show_element( $skin,'front', $contentElements['front'],$type_action,$skinCloned ) ?>	
						</div>
					</div>
				<?php endif; ?>

				<?php if(!empty($contentElements['back'])) : ?>
					<div class="tcard-main-elem back-side" data-side="back">
						<div class="tcard-item-bar">Back Side</div>
						<div class="tcard-item-elements">
							<?php $elementsController->tcardContent->show_element( $skin,'back', $contentElements['back'],$type_action,$skinCloned ) ?>	
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if(!empty($footerElements)) : ?>
			<div class="tcard-item tcard-footer" data-item="tcard-footer">
				<div class="tcard-item-bar"><span class="tcard-item-title">Footer</span></div>

				<?php if(!empty($footerElements['front'])) : ?>
					<div class="tcard-main-elem front-side" data-side="front">
						<div class="tcard-item-bar">Front Side</div>
						<div class="tcard-item-elements">
							<?php $elementsController->tcardFooter->show_element( $skin,'front',$footerElements['front'] ) ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if(!empty($footerElements['back'])) : ?>
					<div class="tcard-main-elem back-side" data-side="back">
						<div class="tcard-item-bar">Back Side</div>
						<div class="tcard-item-elements">
							<?php $elementsController->tcardFooter->show_element( $skin,'back',$footerElements['back'] ) ?>
						</div>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; 
		if($skin_type !== "skin_5") :?>
		<div class="tcard-item tcard-gallery">
			<div class="tcard-item-bar tcard-gallery-bar">
				<span class="tcard-item-title"><?php _e( 'Gallery:' , 'tcard' ) ?></span>
				<span class="tc-multiple-images"><i class="fas fa-cloud-upload-alt"></i></i></span>
			</div>
			<div class="gallery">
				<?php if(!empty($gallery['image'])) :
				
					foreach ($gallery['image'] as $key => $image) : ?>
						<div class="tcg-box" style="background-image: url(<?php echo esc_url($image); ?>)">
							<input type="hidden" name="tcg_gallery<?php echo $skin ?>[image][]" value='<?php echo esc_url($image); ?>'>
							<div class="remove-tcg-img"></div>
						</div>
					<?php endforeach; 
					
				endif; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>