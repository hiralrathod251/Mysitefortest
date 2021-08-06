<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  $image = get_post_meta( get_the_ID(), 'extp_image', true );
  if($image!=''){
    $image = '<img src="'.$image.'"/>';
  }
  global $number_excerpt;
  $img_size = apply_filters('extp_image_size','full');
?>
<figure class="tpstyle-11 tppost-<?php the_ID();?>">
  <div class="tpstyle-11-bg">
    <?php echo $image; ?>
  </div>
  <figcaption>
    <a href="<?php echo $customlink; ?>">
      <?php the_post_thumbnail($img_size, array(
      'class' => 'tpstyle-11-profile'
      )); ?>
    </a>
    <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a>
      <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
        if($position!=''){ ?>
          <span><?php echo $position; ?></span>
      <?php }?>
    </h3>
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
  <?php extp_custom_single_color(11);?>
</figure>
