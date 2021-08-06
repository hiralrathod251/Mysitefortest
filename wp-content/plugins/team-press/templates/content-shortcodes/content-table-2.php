<?php
$customlink = ex_teampress_customlink(get_the_ID());
global $fullcontent_in,$ID,$number_excerpt;
$customlink = ex_teampress_customlink(get_the_ID());
$exlk = get_post_meta( get_the_ID(), 'extp_link', true );
$img_size = apply_filters('extp_image_size','full');
?>
  <tr class="<?php if($exlk!=''){ echo ' extp-exlink';}?>">
	<td class="exp-td-first ex-table2-image"><a href="<?php echo $customlink; ?>"><?php the_post_thumbnail($img_size); ?></a></td>
	<td id="extd-<?php echo get_the_ID()?>" class="ex-table2-info">
	  <?php echo '<div class="item-grid tppost-'.get_the_ID().'" data-id="ex_id-'.$ID.'-'.get_the_ID().'"> ';?>
		<div class="exp-arrow <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'class');?>" <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'data'); ?> >
		  <?php ex_teampress_lightbox($fullcontent_in,$ID,'') ?>
		  <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
		  <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
			if($position!=''){ ?>
			  <h5><?php echo $position; ?></h5>
			<?php }
		  if($number_excerpt =='full'){?>
		    <p><?php echo get_the_excerpt(); ?></p>
		    <?php
		  }else
		  if($number_excerpt > 0){?>
		  <p><?php echo wp_trim_words(get_the_excerpt(),$number_excerpt,'...'); ?></p>
          <?php }?>
		  <span class="extp-hide-mb">
			<?php echo ex_teampress_social(get_the_ID());?>
		  </span>
		</div>
	  </div>
	</td>
	<td class="ex-table2-social extp-hide-screen"><?php echo ex_teampress_social(get_the_ID());?></td>
	<?php extp_custom_single_color('table-2');?>
  </tr>