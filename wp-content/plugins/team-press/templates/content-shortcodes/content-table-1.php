<?php
$customlink = ex_teampress_customlink(get_the_ID());
global $fullcontent_in,$ID,$number_excerpt;
$customlink = ex_teampress_customlink(get_the_ID());
$position = get_post_meta( get_the_ID(), 'extp_position', true );
$short_des = wp_trim_words(get_the_excerpt(),10,'...');
$exlk = get_post_meta( get_the_ID(), 'extp_link', true );
$img_size = apply_filters('extp_image_size','full');
?>
<tr class="<?php if($exlk!=''){ echo ' extp-exlink';}?>">
  <td class="exp-td-first"><a href="<?php echo $customlink; ?>"><?php the_post_thumbnail($img_size); ?></a></td>
  <td id="extd-<?php echo get_the_ID()?>" data-sort="<?php echo esc_attr(get_the_title());?>">
    <?php echo '<div class="item-grid tppost-'.get_the_ID().'" data-id="ex_id-'.$ID.'-'.get_the_ID().'"> ';?>
      <div class="exp-arrow <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'class');?>" <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'data'); ?> >
        <?php ex_teampress_lightbox($fullcontent_in,$ID,'') ?>
        <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
        <span class="extp-hide-mb">
          <?php if($position!=''){ ?>
            <h5><?php echo $position; ?></h5>
          <?php }
		  if($number_excerpt > 0){?>
          <p><?php echo wp_trim_words(get_the_excerpt(),10,'...'); ?></p>
          <?php }?>
          <?php echo ex_teampress_social(get_the_ID());?>
        </span>
      </div>
    </div>
  </td>
  <td class="extp-hide-screen" data-sort="<?php echo esc_attr($position);?>"><?php 
    if($position!=''){ ?>
      <h5><?php echo $position; ?></h5>
    <?php }?>
  </td>
  <?php
  if($number_excerpt =='full'){?>
    <td class="extp-hide-screen" data-sort="<?php echo esc_attr($short_des);?>"><p><?php echo get_the_excerpt(); ?></p></td>
    <?php
  }else 
  if($number_excerpt > 0){?>
      <td class="extp-hide-screen" data-sort="<?php echo esc_attr($short_des);?>"><p><?php echo $short_des; ?></p></td>
      <?php 
  }?>
  <td class="extp-hide-screen"><?php echo ex_teampress_social(get_the_ID());?></td>
  <?php extp_custom_single_color('table-1');?>
</tr>
