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
<div class="wrap tcard">
	 <form accept-charset="UTF-8" action="<?php echo admin_url( 'admin-post.php'); ?>" method="post">
	 	<input type="hidden" name="action" value="tcard_update_group">
        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">

	 	<?php wp_nonce_field( 'tcard_update_group' ); ?>

	 	<div class="tcard-page-header">
	 		<div class="select-tcard-group">
	 			<h4><?php _e( 'Select group:', 'tcard' ); ?></h4>
	 			<select onchange='if (this.value) window.location.href=this.value'>

	 				<?php foreach ($groups as $group) :

	 					if($group['group_id'] == $group_id) :
	 						$selected = "selected";
	 					else :
	 						$selected = '';
	 					endif ?>

	 					<option value='?page=tcard&group_id=<?php echo $group['group_id'] ?>'<?php echo $selected; ?>><?php echo stripslashes($group['title']) ?></option>
	 				<?php endforeach; ?>

	 			</select>
	 			<div class="create-new-group"><?php _e( 'or', 'tcard' ) ?>
	 				<a style="margin-left: 5px" href="<?php echo wp_nonce_url(admin_url("admin-post.php?action=tcard_create_group"), "tcard_create_group") ?>"><?php _e( 'Create a new group', 'tcard' ) ?></a>
	 			</div>
	 		</div>
	 		<div class="header-btns">
	 			<span class="tcard-count-skin"><?php _e( 'Total skins:', 'tcard' ) ?><span><?php echo $group_skins_number ?></span></span>
	 			<div class="tc-header-icon tc-add-new-skin">
	 				<i class="fas fa-plus-square"></i>
	 				<input type="hidden" name="skins_number" value="<?php echo $group_skins_number ?>" />
	 			</div>
	 			<div class="tc-header-icon tcard-settings"><i class="fas fa-cog"></i></div>

	 			<?php $tcardElements->tcardSettings->settings(
	 				$group_id,
	 				'group',
	 				__( 'Group - ', 'tcard' ) . $group_title,
	 				'',
	 				$settings = array(
	 					'group_name' 			=> array(
	 						'input','checkbox',
	 						__( 'Group name', 'tcard' ),
	 						sprintf(
							    __( 'If you want to display %s name of this group %s set this option true', 'tcard' ),
							    "<br>","<br>"
							)
	 					),
	 					'tcardFlip' 			=> array(
	 						'input','checkbox',
	 						__( 'Turn off flip', 'tcard' ),
	 						__( 'Default: true', 'tcard' )
	 					),
	 					'tcardOn' => array(
	 						'select',array('button','hover'),
	 						__( 'Tcard on', 'tcard' ),
	 						__( 'Default: button', 'tcard' )
	 					),
	 					'animationFront' 		=> array(
	 						'select',array('ready','hover'),
	 						__( 'Animations front', 'tcard' ),
	 						__( 'Default: ready', 'tcard' )
	 					),
	 					'animationOneTime'		=> array(
	 						'input','checkbox',
	 						__( 'Animations once', 'tcard' ),
	 						__( 'Default: false', 'tcard' )
	 					),
	 					'randomColor'			=> array(
	 						'input','checkbox',
	 						__( 'Random color', 'tcard' ),
	 						__( 'Default: false', 'tcard' )
	 					),
	 					'durationCount'			=> array(
	 						'input','number',
	 						__( 'Count time', 'tcard' ),
	 						__( 'Default: 900', 'tcard' )
	 					),
	 					'autocomplete' 			=> array(
	 						'input','checkbox',
	 						__( 'Autocomplete', 'tcard' ),
	 						__( 'Default: off', 'tcard' )
	 					),
	 					'container_group' 		=> array(
	 						'select',array('fixed','fluid'),
	 						__( 'Container', 'tcard' ),
	 						__( 'Default: fixed', 'tcard' )
	 					)			
	 				),
	 				$get_skin_type
	 			);?>

		 		<a class="tc-header-icon delete-tcard-group" href="<?php echo wp_nonce_url(admin_url("admin-post.php?action=tcard_delete_group&get_group_id={$group_id}"), "tcard_delete_group") ?>"><i class="fas fa-trash-alt"></i></a>
		 		<a class="tc-header-icon tcard-logo" href="http://addudev.com/tcard" target="_blank"><img src="<?php echo TCARD_BASE_URL . 'admin/images/logo.png' ?>"/></a>
	 		</div>
	 	</div>

	 	<div class="tcard-sidebar">
	 		<div class="tcard-sidebar-head">
	 			<button class='alignleft button button-primary' type='submit' name='save' id='tcard-save'>Save</button>
	 			<span class="spinner"></span>
	 		</div>
	 		<?php if(!empty($groups)) : ?>
		 		<div class="tcard-sidebar-item group-settings">
		 			<h4><?php _e( 'Set group name:', 'tcard' ) ?></h4>
		 			<input type="text" class="tcard-group-title tcard-input" name="tcard_group_name" value="<?php echo stripslashes($group_title) ?>">
		 		</div>
		 		<div class="tcard-sidebar-item group-settings">
		 			<h4><?php _e( 'Select Skin:', 'tcard' ) ?></h4>
		 			<select id="select-skin" name="tcard_skin_type">
		 				<option></option>
						<?php for($i = 1; $i <= 6; $i++) : ?>
							<option value="skin_<?php echo $i ?>" <?php selected( $get_skin_type, "skin_$i" ); ?>> Skin <?php echo $i ?> </option>
						<?php endfor; ?>
					</select>
		 		</div>
	 		<?php endif; ?>
	 		<?php if($get_skin_type == "skin_5") : ?>
	 			<div class="skin_5 tcard-sidebar-item group-settings">	
		 			<h4><?php _e( 'Select category:', 'tcard' ) ?></h4>	
		 			<select name="group_set[category_name]">
						<?php foreach ($categories as $key => $category) : 
							(!empty($group_settings['category_name'])) ? $group_settings['category_name'] : $group_settings['category_name'] = "";?>
							<option value="<?php echo $category->name ?>" <?php selected( $group_settings['category_name'], $category->name ); ?> ><?php echo $category->name; ?></option>
						<?php endforeach; ?>

					</select>
				</div>
				<div class="skin_5 tcard-sidebar-item group-settings">	
		 			<h4><?php _e( 'Order by:', 'tcard' ) ?></h4>	
		 			<select name="group_set[orderby_category]">
		 				<?php (!empty($group_settings['orderby_category'])) ? $group_settings['orderby_category'] : $group_settings['orderby_category'] = "";?>
						<option value="author" <?php selected( $group_settings['orderby_category'], 'author' ); ?>>author</option>
						<option value="title" <?php selected( $group_settings['orderby_category'], 'title' ); ?>>title</option>
						<option value="date" <?php selected( $group_settings['orderby_category'], 'date' ); ?>>date</option>
						<option value="modified" <?php selected( $group_settings['orderby_category'], 'modified' ); ?>>modified</option>
						<option value="rand" <?php selected( $group_settings['orderby_category'], 'rand' ); ?>>rand</option>
					</select>
				</div>
				<div class="skin_5 tcard-sidebar-item group-settings last-child">	
		 			<h4><?php _e( 'Order:', 'tcard' ) ?></h4>	
		 			<select name="group_set[order_category]">
		 				<?php (!empty($group_settings['order_category'])) ? $group_settings['order_category'] : $group_settings['order_category'] = "";?>
						<option value="DESC" <?php selected( $group_settings['order_category'], 'DESC' ); ?>>DESC</option>
						<option value="ASC" <?php selected( $group_settings['order_category'], 'ASC' ); ?>>ASC</option>
					</select>
				</div>
 			<?php endif; ?>

	 		<div class="tcard-sidebar-item tcard-sidebar-info">
	 			<h3><?php _e( 'How to use', 'tcard' ) ?></h3>
	 			<p><?php _e( 'To display your tcard group, add the following shortcode (blue) to your page. If adding the tcard group to your theme files, additionally include the surrounding PHP function (magenta).', 'tcard' ) ?></p>
	 			<pre id="tcard-code">&lt;?php echo do_shortcode(<br>'<span class="tcard-shortcode">[tcard group_id="<?php echo $group_id; ?>"]</span>'<br>); ?&gt;</pre>
	 			<div class="copy-shortcode"><?php _e( 'Copy all', 'tcard' ) ?></div>
	 		</div>
	 		<div class="pro-version">For more features check <a href="https://codecanyon.net/item/tcard-simple-plugin-for-creating-cards/22061987?ref=andru24" target="_blank"> Tcard WP </a> Pro version.</div>
	 	</div>
	 	<div class="tcard-container-skins <?php echo $skin_name; ?>">
	 		<?php if(!empty($group_output->skin_type)) $tcardSkinsController->skinType($group_id,TCARD_ADMIN_URL); ?>
	 	</div>
	 </form>
</div>