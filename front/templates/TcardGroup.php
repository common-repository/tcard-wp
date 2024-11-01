<?php

/**
 * @since           1.0.0
 * @package         Tcard
 * @subpackage  	Tcard/public/templates
 * @author     		Cloanta Alexandru <alexandrucloanta@yahoo.com>
 * @link            https://www.addudev.com
 **/

if (!defined('ABSPATH')) die('No direct access allowed');

if(!empty($get_skins)) :
?>
<div id="tcard-group-<?php echo $group_id ?>" class="tcard-group tcard-group-<?php echo $group_id ?>">
    <div class="<?php echo $settings["container_group"] ?>">
    <?php if($settings["group_name"] == true) : ?>
        <div class="row">
            <div class="tcard-group-title">
               <h2><?php echo wp_specialchars_decode(stripslashes($group->title)) ?></h2> 
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <?php $tcardSkinsController->skinType($group_id,TCARD_FRONT_URL); ?>
    </div>
    <?php for ($skin = 0; $skin < $skins_number; $skin++) :

    		if(!empty($all_gallery[$skin])){
    			$gallery = unserialize($all_gallery[$skin]);
    		}else{
    			$gallery = "";
    		}
            
            $skin_id = $get_skins[$skin]->skin_id;
            
            if(!empty($gallery['image']) || !empty($gallery['user'])) : ?>
      
                <div class="tcg-group" data-tcg-group="group-<?php echo $group_id.$skin ?>">

                    <div class="tcg fade-in" data-tcg-open="card-<?php echo $skin; ?>">

                        <?php if(!empty($gallery['image'])) :

                            foreach ($gallery['image'] as $key => $image) : ?>
                                <img class="tcg-item" src="<?php echo esc_url($image); ?>" alt=" ">
                            <?php endforeach;

                        endif; ?>
                        <div class="tcg-arrow tcg-left"></div>
                        <div class="tcg-arrow tcg-right"></div>
                    </div>
                    <div class="tcg-header">
                        <div class="tcg-counter">
                            <span class="tcg-current-counter"></span> / <span class="tcg-counter-all"></span>
                        </div>
                        <div class="tcg-close">
                            <span class="tcg-line"></span>
                            <span class="tcg-line"></span>
                        </div>
                    </div>

                </div>  

            <?php endif;
        
        endfor; ?>
    <script type="text/javascript">
    (function( $ ) {
        var group = $(".tcard-group-<?php echo $group_id ?>");

        group.each(function () {
            $(this).find(".tcard").tcard({
                tcardFlip: <?php echo $settings['tcardFlip']; ?>,
                tcardOn: '<?php echo $settings['tcardOn']; ?>',
                animationFront: '<?php echo $settings['animationFront']; ?>',
                animationOneTime: <?php echo $settings['animationOneTime']; ?>,
                randomColor: <?php echo $settings['randomColor']; ?>,
                durationCount: <?php echo $settings['durationCount']; ?>,
                autocomplete: <?php echo $settings['autocomplete']; ?>,
            });
        });
    })( jQuery );
    </script>
    </div>
</div>
<?php endif; ?>