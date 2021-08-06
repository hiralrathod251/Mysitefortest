<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  global $number_excerpt;
  $img_size = apply_filters('extp_image_size','full');
?>
<figure class="tpstyle-img-3 extp-scale tppost-<?php the_ID();?>">
  <a href="<?php echo $customlink; ?>"><?php the_post_thumbnail($img_size); ?></a>
  <figcaption>
    <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
    <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
      if($position!=''){ ?>
        <h5><?php echo $position; ?></h5>
    <?php }?>
    <?php 
	if($number_excerpt =='full'){?>
    <p><?php echo get_the_excerpt(); ?></p>
    <?php
  }else 
  if($number_excerpt > 0){?>
	<p><?php echo wp_trim_words(get_the_excerpt(),$number_excerpt,'...'); ?></p>
	<?php }?>
    <?php echo ex_teampress_social(get_the_ID());?>
  </figcaption>
  <?php extp_custom_single_color('img-3');?>
</figure>