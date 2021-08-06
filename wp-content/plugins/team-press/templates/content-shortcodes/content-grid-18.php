<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  $image = get_post_meta( get_the_ID(), 'extp_image', true );
    $bgstyle ='';
    if($image!=''){
      $bgstyle ='style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.6) 0%,rgba(0,0,0,0.6) 100%), url('.$image.')"';
    };
  global $number_excerpt;
  $img_size = apply_filters('extp_image_size','full'); 
?>
<figure class="tpstyle-18 tppost-<?php the_ID();?>" <?php echo $bgstyle; ?> >
  <a href="<?php echo $customlink; ?>">
    <div class="tpstyle-18-profile" style="background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(),$img_size); ?>)"></div>
  </a>
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
    <div class="tpstyle-18-social">
      <?php echo ex_teampress_social(get_the_ID());?>
    </div>
  </figcaption>
  <?php extp_custom_single_color(18);?>
</figure>