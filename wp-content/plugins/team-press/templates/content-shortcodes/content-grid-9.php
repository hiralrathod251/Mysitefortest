<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  global $number_excerpt;
  $image = get_post_meta( get_the_ID(), 'extp_image', true );
  $img_size = apply_filters('extp_image_size','full');
?>
<figure class="tpstyle-9 tppost-<?php the_ID();?>">
  <div class="tpstyle-9-image">
    <a href="<?php echo $customlink; ?>">
      <?php the_post_thumbnail($img_size); ?>
      <?php if($image!=''){?>
        <img class="second-img second-cus" src="<?php echo esc_url($image); ?>">
        <?php }
      ?>
      </a>
    <div class="tpstyle-9-position">
      <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
      if($position!=''){ ?>
        <h5><?php echo $position; ?></h5>
      <?php }?>
    </div>
  </div>
  <div class="tpstyle-9-content">
    <figcaption>
      <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
      <div class="tpstyle-9-meta">
        <?php $phone = get_post_meta( get_the_ID(), 'extp_phone', true ); 
        if($phone!=''){ ?>
          <h4><?php echo esc_html__('Phone: ','teampress')."<a href='tel:".esc_attr($phone)."'>". $phone; ?></a></h4>
        <?php }?>
        <?php $email = get_post_meta( get_the_ID(), 'extp_email', true ); 
        if($email!=''){ ?>
          <h4><?php echo esc_html__('Email: ','teampress')."<a href='mailto:".sanitize_email($email)."'>". $email; ?></a></h4>
        <?php }
        do_action('extp_after_meta_style9');
        ?>
      </div>
      <?php 
	if($number_excerpt =='full'){?>
    <p><?php echo get_the_excerpt(); ?></p>
    <?php
  }else 
  if($number_excerpt > 0){?>
	<p><?php echo wp_trim_words(get_the_excerpt(),$number_excerpt,'...'); ?></p>
	<?php }?>

    </figcaption>
    <?php echo ex_teampress_social(get_the_ID());?>
  </div>
  <?php extp_custom_single_color(9);?>
</figure>