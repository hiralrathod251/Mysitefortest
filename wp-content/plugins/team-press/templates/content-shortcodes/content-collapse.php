<?php 
  $customlink = ex_teampress_customlink(get_the_ID());
?>
<div class="exp-mdcontaner">
<div class="exp-expand">
	<div class="exp-padding">
		<div class="extp-epbutton">
            <div class="exp-expand-pre"></div>
            <div class="exp-expand-next"></div>
            <div class="exp-expand-close">&nbsp;</div>
        </div>
		<div class="exp-expand-image">
			<a href="<?php echo $customlink; ?>"><?php echo '<img src="'.get_post_meta( get_the_ID(), 'extp_image', true ).'"/>'; ?></a>
		</div>
		<div class="exp-expand-des">
			<h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
            <div class="expand-social"><?php echo ex_teampress_social(get_the_ID());?></div>
			<div class="exp-expand-meta">
				<?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
				if($position!=''){ ?>
				  <h5><span><?php echo esc_html__('Position: ','teampress'); ?></span><?php echo $position; ?></h5>
				<?php }?>
				<?php $phone = get_post_meta( get_the_ID(), 'extp_phone', true ); 
				if($phone!=''){ ?>
				  <h5><span><?php echo esc_html__('Phone: ','teampress'); ?></span><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo $phone; ?></a></h5>
				<?php }?>
				<?php $email = get_post_meta( get_the_ID(), 'extp_email', true ); 
				if($email!=''){ ?>
				  <h5><span> <?php echo esc_html__('Email: ','teampress'); ?></span><a href="mailto:<?php echo sanitize_email($email); ?>"><?php echo $email; ?></a></h5>
				<?php }
				$cate = extp_taxonomy_info('extp_cat','off',get_the_ID());
	            if($cate!=''){
	                ?>
	                <h5 class="team-cat-info"><span><?php echo esc_html__('Categories: ','teampress'); ?></span>
	                    <?php echo $cate; ?>
	                </h5>
	                <?php
	            }
	            $location = extp_taxonomy_info('extp_loc','off',get_the_ID());
	            if($location!=''){
	                ?>
	                <h5 class="team-log-info"><span><?php echo esc_html__('Location: ','teampress'); ?></span>
	                    <?php echo $location; ?>
	                </h5>
	                <?php
	            }
				ex_teampress_custom_info(get_the_ID());?>
			</div>
			<div class="extp-mb-content"><?php
				if(class_exists('WPBMap') && method_exists('WPBMap', 'addAllMappedShortcodes')) { 
					WPBMap::addAllMappedShortcodes();
				}
				the_content(); ?>
			</div>
		</div>
	</div>
</div>
</div>