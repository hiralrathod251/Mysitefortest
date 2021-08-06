<?php 
  $customlink = ex_teampress_customlink(get_the_ID()); 
  global $number_excerpt;
  $img_size = apply_filters('extp_image_size','full');
?>
<figure class="tpstyle-img-11 extp-scale " >
  <a href="<?php echo $customlink; ?>"><?php the_post_thumbnail($img_size); ?></a>
  <figcaption>
    <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
    <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
        if($position!=''){ ?>
          <h5><?php echo $position; ?></h5>
      <?php }?>
    <div class="tpstyle-img-11-icons">
      
    </div>
  </figcaption>
</figure>
