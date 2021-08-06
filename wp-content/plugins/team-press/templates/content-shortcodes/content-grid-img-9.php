<?php
  $customlink = ex_teampress_customlink(get_the_ID());
  $img_size = apply_filters('extp_image_size','full');
?> 
<figure class="tpstyle-img-9 tppost-<?php the_ID();?>">
  <a href="<?php echo $customlink; ?>"><?php the_post_thumbnail($img_size); ?></a>
  <figcaption>
    <div>
      <?php $position = get_post_meta( get_the_ID(), 'extp_position', true ); 
        if($position!=''){ ?>
          <h5><?php echo $position; ?></h5>
      <?php }?>
    </div>
    <h3><a href="<?php echo $customlink; ?>"><?php the_title(); ?></a></h3>
  </figcaption>
  <?php extp_custom_single_color('img-9');?>
</figure>