<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  global $number_excerpt;
  $img_size = apply_filters('extp_image_size','full');
?>
<figure class="tpstyle-img-4 tppost-<?php the_ID();?>">
  <a href="<?php echo $customlink; ?>"><?php the_post_thumbnail($img_size); ?></a>
  <figcaption>
    <div class="tpstyle-img-4-meta">
      <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
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
    <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
      if($position!=''){ ?>
        <h5><?php echo $position; ?></h5>
    <?php }?>
  </figcaption>
  <div class="tpstyle-img-4-hover"></div>
  <i><div class="ex-icon ex-icon-plus"><span class="name"></span></div></i>
  <?php extp_custom_single_color('img-4');?>
</figure>