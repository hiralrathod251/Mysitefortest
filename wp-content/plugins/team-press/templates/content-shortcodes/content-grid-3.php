<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  global $number_excerpt;
  $image = get_post_meta( get_the_ID(), 'extp_image', true );
  $img_size = apply_filters('extp_image_size','full');
?>
<figure class="tpstyle-3 tppost-<?php the_ID();?>">
  <div class="tpstyle-3-image">
    <a href="<?php echo $customlink; ?>">
      <div class="image-bg-circle" style="background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(),$img_size); ?>)">
        <?php if($image!=''){?>
          <div class="image-bg-circle second-img" style="background-image: url(<?php echo esc_url($image); ?>)"></div>
          <?php }
        ?>
      </div>
    </a>
  </div>
  <figcaption>
    <div class="tpstyle-3-rib"><h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3></div>
    <div class="tpstyle-3-l"></div>
    <div class="tpstyle-3-r"></div>
    <div class="tpstyle-3-info">
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
    </div>
  </figcaption>
  <?php extp_custom_single_color(3);?>
</figure>