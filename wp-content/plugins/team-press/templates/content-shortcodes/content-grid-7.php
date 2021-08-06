<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  $image = get_post_meta( get_the_ID(), 'extp_image', true );
  $bgstyle ='';
  if($image!=''){
    $bgstyle ='style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.6) 0%,rgba(0,0,0,0.6) 100%), url('.$image.')"';
  }
  global $number_excerpt;
  $img_size = apply_filters('extp_image_size','full');
?>
<figure class="tpstyle-7 tppost-<?php the_ID();?>" >
  <div class="tpstyle-7-child" <?php echo $bgstyle; ?> >
    <div class="tpstyle-7-image">
      <a href="<?php echo $customlink; ?>">
        <?php the_post_thumbnail($img_size, array(
        'class' => 'tpstyle-7-profile'
        )); ?>
      </a>
    </div>  
    <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
    <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
      if($position!=''){ ?>
        <h5><?php echo $position; ?></h5>
    <?php }?>
  </div>
  <figcaption>
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
  <!-- </div> -->
  <?php extp_custom_single_color(7);?>
</figure>
