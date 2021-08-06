<?php
get_header();
?>
<div class="extp-member-listing extp-member-single">
	<?php
	$extp_single_slug = extp_get_option('extp_single_slug');
	if ($extp_single_slug!='' && $post = get_page_by_path( $extp_single_slug, OBJECT, 'page' ) ){
	    $id = $post->ID;
		$page = get_post($id);
		$content = $page->post_content;
	}else{
	    $content ='';
	}
	$content = $content!='' ? $content : '[ex_tpgrid style="1" column="3" count="999" posts_per_page="6" fullcontent_in="" search_box="show" category_style="inline" alphab_filter="yes"]';
	$sc = apply_filters('extp_shortcode_archives',$content);
	echo do_shortcode($sc);
	?>
</div><!--end main-content-->
<?php get_footer(); ?>