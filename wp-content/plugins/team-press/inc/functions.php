<?php
//shortcode
include plugin_dir_path(__FILE__).'shortcodes/teampress-list.php';
include plugin_dir_path(__FILE__).'shortcodes/teampress-grid.php';
include plugin_dir_path(__FILE__).'shortcodes/teampress-table.php';
include plugin_dir_path(__FILE__).'shortcodes/teampress-carousel.php';
//widget
include plugin_dir_path(__FILE__).'widgets/teampress.php';

if(!function_exists('extp_startsWith')){
	function extp_startsWith($haystack, $needle)
	{
		return !strncmp($haystack, $needle, strlen($needle));
	}
} 
if(!function_exists('extp_get_google_fonts_url')){
	function extp_get_google_fonts_url ($font_names) {
	
		$font_url = '';
	
		$font_url = add_query_arg( 'family', urlencode(implode('|', $font_names)) , "//fonts.googleapis.com/css" );
		return $font_url;
	} 
}
if(!function_exists('extp_get_google_font_name')){
	function extp_get_google_font_name($family_name){
		$name = $family_name;
		if(extp_startsWith($family_name, 'http')){
			// $family_name is a full link, so first, we need to cut off the link
			$idx = strpos($name,'=');
			if($idx > -1){
				$name = substr($name, $idx);
			}
		}
		$idx = strpos($name,':');
		if($idx > -1){
			$name = substr($name, 0, $idx);
			$name = str_replace('+',' ', $name);
		}
		return $name;
	}
}
if(!function_exists('extp_template_plugin')){
	function extp_template_plugin($pageName,$shortcode=false){
		if(isset($shortcode) && $shortcode== true){
			if (locate_template('teampress/content-shortcodes/content-' . $pageName . '.php') != '') {
				get_template_part('teampress/content-shortcodes/content', $pageName);
			} else {
				include extp_get_plugin_url().'templates/content-shortcodes/content-' . $pageName . '.php';
			}
		}else{
			if (locate_template('teampress/content-' . $pageName . '.php') != '') {
				get_template_part('teampress/content', $pageName);
			} else {
				include extp_get_plugin_url().'templates/content-' . $pageName . '.php';
			}
		}
	}
}

if(!function_exists('ex_teampress_query')){
    function ex_teampress_query($posttype, $count, $order, $orderby, $cat, $tag, $taxonomy, $meta_key, $ids, $location, $meta_value=false,$page=false,$mult=false){
		$posttype = 'ex_team';
		if($posttype == 'ex_team' && $taxonomy == ''){
			$taxonomy = 'extp_cat';
			
		}
		if(isset( $_GET['tcat']) && $_GET['tcat']!='' ){
			$cat = $_GET['tcat'];
		}
		$taxonomy_loc = 'extp_loc';
		$posttype = explode(",", $posttype);
		if($ids!=''){ //specify IDs
			$ids = explode(",", $ids);
			$args = array(
				'post_type' => $posttype,
				'posts_per_page' => $count,
				'post_status' => array( 'publish'),
				'post__in' =>  $ids,
				'order' => $order,
				'orderby' => $orderby,
				'meta_key' => $meta_key,
				'ignore_sticky_posts' => 1,
			);
		}elseif($ids==''){
			$args = array(
				'post_type' => $posttype,
				'posts_per_page' => $count,
				'post_status' => array( 'publish'),
				'tag' => $tag,
				'order' => $order,
				'orderby' => $orderby,
				'meta_key' => $meta_key,
				'ignore_sticky_posts' => 1,
			);
			if(!is_array($cat) && $taxonomy =='') {
				$cats = explode(",",$cat);
				if(is_numeric($cats[0])){
					$args['category__in'] = $cats;
				}else{			 
					$args['category_name'] = $cat;
				}
			}elseif( is_array($cat) && count($cat) > 0 && $taxonomy ==''){
				$args['category__in'] = $cat;
			}
			if($taxonomy !='' && $tag!=''){
				$tags = explode(",",$tag);
				if(is_numeric($tags[0])){$field_tag = 'term_id'; }
				else{ $field_tag = 'slug'; }
				if(count($tags)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($tags as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field_tag,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field_tag,
								  'terms' => $tags,
							  )
					  );
				}
			}
			//cats
			if($taxonomy !='' && $cat!='' && $location==''){
				$cats = explode(",",$cat);
				if(is_numeric($cats[0])){$field = 'term_id'; }
				else{ $field = 'slug'; }
				if(count($cats)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($cats as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field,
								  'terms' => $cats,
							  )
					  );
				}
			}
			if($taxonomy_loc !='' && $location!='' && $cat==''){
				$locations = explode(",",$location);
				if(is_numeric($locations[0])){$field = 'term_id'; }
				else{ $field = 'slug'; }
				if(count($locations)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($locations as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => $taxonomy_loc,
								  'field' => $field,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => $taxonomy_loc,
								  'field' => $field,
								  'terms' => $locations,
							  )
					  );
				}
			}
			if($location!='' && $cat !=''){
				$cats = explode(",",$cat);
				$locations = explode(",",$location);
				if(is_numeric($cats[0])){$field_cat = 'term_id';} else{ $field_cat = 'slug';}
				if(is_numeric($locations[0])){$field_loc = 'term_id';} else{ $field_loc = 'slug';}
				$texo = array(
						'relation' => 'AND',
						array(
							'taxonomy' => $taxonomy,
							'field' => $field_cat,
							'terms'    => $cats,
						),
						array(
							'taxonomy' => $taxonomy_loc,
							'field' => $field_loc,
							'terms'    => $locations,
						),
					  );
			}
			if(isset($mult) && $mult!=''){
				$texo['relation'] = 'AND';
				$texo[] = 
					array(
						'taxonomy' => 'wpex_category',
						'field' => 'term_id',
						'terms' => $mult,
					);
			}
			if(isset($texo)){
				$args += array('tax_query' => $texo);
			}
		}
		if(isset($meta_value) && $meta_value!='' && $meta_key!=''){
			if(!empty($args['meta_query'])){
				$args['meta_query']['relation'] = 'AND';
			}
			$args['meta_query'][] = array(
				'key'  => $meta_key,
				'value' => $meta_value,
				'compare' => '='
			);
		}	
		if(isset($page) && $page!=''){
			$args['paged'] = $page;
		}
		return apply_filters( 'extp_query', $args );
	}
}

if(!function_exists('ex_teampress_social')){
	function ex_teampress_social($id){
		if(!is_numeric($id) || extp_get_option('extp_disable_social') =='yes'){
			return ;
		}
		echo "<ul class ='ex-social-account'>";
			$behance = get_post_meta( $id, 'extp_behance', true ); 
			if($behance != ''){
				echo "<li class='teampress-behance'><a href='".esc_url($behance)."'><i class='fab fa-behance'></i></a></li>";
			}
			$dribbble = get_post_meta( $id, 'extp_dribble', true ); 
			if($dribbble != ''){
				echo "<li class='teampress-dribbble'><a href='".esc_url($dribbble)."'><i class='fab fa-dribbble'></i></a></li>";
			}
			$facebook = get_post_meta( $id, 'extp_facebook', true ); 
			if($facebook != ''){
				echo "<li class='teampress-facebook'><a href='".esc_url($facebook)."'><i class='fab fa-facebook-f'></i></a></li>";
			}
			$flickr = get_post_meta( $id, 'extp_flickr', true ); 
			if($flickr != ''){
				echo "<li class='teampress-flickr'><a href='".esc_url($flickr)."'><i class='fab fa-flickr'></i></a></li>";
			}
			$github = get_post_meta( $id, 'extp_github', true ); 
			if($github != ''){
				echo "<li class='teampress-github'><a href='".esc_url($github)."'><i class='fab fa-github'></i></a></li>";
			}
			$instagram = get_post_meta( $id, 'extp_instagram', true ); 
			if($instagram != ''){
				echo "<li class='teampress-instagram'><a href='".esc_url($instagram)."'><i class='fab fa-instagram'></i></a></li>";
			}
			$linkedIn = get_post_meta( $id, 'extp_linkedin', true ); 
			if($linkedIn != ''){
				echo "<li class='teampress-linkedin'><a href='".esc_url($linkedIn)."'><i class='fab fa-linkedin-in'></i></a></li>";
			}
			$pinterest = get_post_meta( $id, 'extp_pinterest', true ); 
			if($pinterest != ''){
				echo "<li class='teampress-pinterest'><a href='".esc_url($pinterest)."'><i class='fab fa-pinterest'></i></a></li>";
			}
			$tumblr = get_post_meta( $id, 'extp_tumblr', true ); 
			if($tumblr != ''){
				echo "<li class='teampress-tumblr'><a href='".esc_url($tumblr)."'><i class='fab fa-tumblr'></i></a></li>";
			}
			$twitter = get_post_meta( $id, 'extp_twitter', true ); 
			if($twitter != ''){
				echo "<li class='teampress-twitter'><a href='".esc_url($twitter)."'><i class='fab fa-twitter'></i></a></li>";
			}
			$youtube = get_post_meta( $id, 'extp_youtube', true ); 
			if($youtube != ''){
				echo "<li class='teampress-youtube'><a href='".esc_url($youtube)."'><i class='fab fa-youtube'></i></a></li>";
			}
			$email = get_post_meta( $id, 'extp_email', true ); 
			if($email != ''){
				echo "<li class='teampress-email'><a href='mailto:".sanitize_email($email)."'><i class='far fa-envelope'></i></a></li>";
			}

			$phone = get_post_meta( $id, 'extp_phone', true ); 
			if($phone != ''){
				echo "<li class='teampress-mobile'><a href='tel:".esc_attr($phone)."'><i class='fas fa-mobile-alt'></i></a></li>";
			}

			$custom_social = get_post_meta( $id, 'extp_custom_social_gr', true );
			if(!empty($custom_social)){
				foreach($custom_social as $social){
					echo "<li class='tp-ctsocial teampress-".esc_attr($social['_name'])."'><a href='".esc_url($social['_url'])."'>".$social['_icon']."</a></li>";
				}
			}
		echo "</ul>";
	}
}
if(!function_exists('ex_teampress_custom_info')){
	function ex_teampress_custom_info($id){
		$custom_info = get_post_meta( $id, 'extp_custom_team_info', true );
		if(!empty($custom_info)){
			foreach($custom_info as $info){
				echo "<h5><span>".$info['_name'].": </span>".$info['_content']."</h5>";
			}
		}
	}
}

if(!function_exists('ex_teampress_customlink')){
	function ex_teampress_customlink($id){
		$link = get_post_meta( $id, 'extp_link', true );
		if($link!=''){ return $link;}
		if ( extp_get_option('extp_disable_single') =='yes' ) {
			return 'javascript:;';
		}
		return get_the_permalink($id);
	}
}

if(!function_exists('ex_teampress_lightbox')){
	function ex_teampress_lightbox($fullcontent_in,$ID,$return){
		if ($fullcontent_in != 'lightbox') {
			return;
		}
		$datacl = 'exlb-'.$ID;
		if($return == 'class'){
			return 'exlightbox '.$datacl;
		}elseif($return == 'data'){
			return ' href="'.get_the_post_thumbnail_url().'" data-glightbox="descPosition: right;" data-class="'.$datacl.'"';
		}else{ ?>
			<div class="glightbox-desc">
		      <?php extp_template_plugin('lightbox',1);?>
		    </div>
    	<?php
		}
		
	}
}

if(!function_exists('ex_teampress_modal_right_html')){
	function ex_teampress_modal_right_html($id){
		ob_start();
		echo '<div class="item-modl" id="ex_id-'.$id.'">'; extp_template_plugin('modal',1); echo '</div>';
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
if(!function_exists('extp_page_number_html')){
	if(!function_exists('extp_page_number_html')){
		function extp_page_number_html($the_query,$ID,$atts,$num_pg,$args,$arr_ids){
			if(function_exists('paginate_links')) {
				echo '<div class="extp-pagination">';
				echo '
					<input type="hidden"  name="id_grid" value="'.$ID.'">
					<input type="hidden"  name="total_item" value="'.$the_query->found_posts.'">
					<input type="hidden"  name="num_page" value="'.$num_pg.'">
					<input type="hidden"  name="num_page_uu" value="1">
					<input type="hidden"  name="current_page" value="1">
					<input type="hidden"  name="ajax_url" value="'.esc_url(admin_url( 'admin-ajax.php' )).'">
					<input type="hidden"  name="param_query" value="'.esc_html(str_replace('\/', '/', json_encode($args))).'">
					<input type="hidden"  name="param_ids" value="'.esc_html(str_replace('\/', '/', json_encode($arr_ids))).'">
					<input type="hidden" id="param_shortcode" name="param_shortcode" value="'.esc_html(str_replace('\/', '/', json_encode($atts))).'">
				';
				if($num_pg > 1){
					$page_link =  paginate_links( array(
						'base'         => esc_url_raw( str_replace( 999999999, '%#%', get_pagenum_link( 999999999, false ) ) ),
						'format'       => '?paged=%#%',
						'add_args'     => false,
						'show_all'     => true,
						'current' => max( 1, get_query_var('paged') ),
						'total' => $num_pg,
						'prev_next'    => false,
						'type'         => 'array',
						'end_size'     => 3,
						'mid_size'     => 3
					) );
					$class = '';
					if ( get_query_var('paged')<2) {
						$class = 'disable-click';
					}
					$prev_link = '<a class="prev-ajax '.$class.'" href="javascript:;">&larr;</a>';
					$next_link = '<a class="next-ajax" href="javascript:;">&rarr;</a>';
					array_unshift($page_link, $prev_link);
					$page_link[] = $next_link;
					echo '<div class="page-navi">'.implode($page_link).'</div>';
				}
				echo '</div>';
			}
		}
	}
}

if(!function_exists('extp_ajax_navigate_html')){
	function extp_ajax_navigate_html($ID,$atts,$num_pg,$args,$arr_ids){
		$html = '
			<div class="ex-loadmore">
				<input type="hidden"  name="id_grid" value="'.$ID.'">
				<input type="hidden"  name="num_page" value="'.$num_pg.'">
				<input type="hidden"  name="num_page_uu" value="1">
				<input type="hidden"  name="current_page" value="1">
				<input type="hidden"  name="ajax_url" value="'.esc_url(admin_url( 'admin-ajax.php' )).'">
				<input type="hidden"  name="param_query" value="'.esc_html(str_replace('\/', '/', json_encode($args))).'">
				<input type="hidden"  name="param_ids" value="'.esc_html(str_replace('\/', '/', json_encode($arr_ids))).'">
				<input type="hidden" id="param_shortcode" name="param_shortcode" value="'.esc_html(str_replace('\/', '/', json_encode($atts))).'">';
				if($num_pg > 1){
					$html .='
					<a  href="javascript:void(0)" class="loadmore-exbt" data-id="'.$ID.'">
						<span class="load-text">'.esc_html__('Load more','teampress').'</span><span></span>&nbsp;<span></span>&nbsp;<span></span>
					</a>';
				}
				$html .='
		</div>';
		echo $html;
	}
}

add_action( 'wp_ajax_extp_loadmore', 'ajax_extp_loadmore' );
add_action( 'wp_ajax_nopriv_extp_loadmore', 'ajax_extp_loadmore' );
function ajax_extp_loadmore(){
	global $columns,$number_excerpt,$show_time,$orderby,$img_size,$ID;
	global $fullcontent_in,$ID,$number_excerpt;
	$atts = json_decode( stripslashes( $_POST['param_shortcode'] ), true );
	$ID = isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'extp-'.rand(10,9999);
	$style = isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1';
	$column = isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '2';
	$posttype   = isset($atts['posttype']) && $atts['posttype']!='' ? $atts['posttype'] : 'ex_team';
	$ids   = isset($atts['ids']) ? $atts['ids'] : '';
	$taxonomy  = isset($atts['taxonomy']) ? $atts['taxonomy'] : '';
	$cat   = isset($atts['cat']) ? $atts['cat'] : '';
	$location   = isset($atts['location']) ? $atts['location'] : '';
	$tag  = isset($atts['tag']) ? $atts['tag'] : '';
	$count   = isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9';
	$fullcontent_in   = isset($atts['fullcontent_in']) ? $atts['fullcontent_in'] : 'lightbox';
	$posts_per_page   = isset($atts['posts_per_page']) && $atts['posts_per_page'] !=''? $atts['posts_per_page'] : '3';
	$order  = isset($atts['order']) ? $atts['order'] : '';
	$orderby  = isset($atts['orderby']) ? $atts['orderby'] : '';
	$meta_key  = isset($atts['meta_key']) ? $atts['meta_key'] : '';
	$meta_value  = isset($atts['meta_value']) ? $atts['meta_value'] : '';
	$class  = isset($atts['class']) ? $atts['class'] : '';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$page = $_POST['page'];
	$layout = isset($_POST['layout']) ? $_POST['layout'] : '';
	$param_query = json_decode( stripslashes( $_POST['param_query'] ), true );
	$param_ids = '';
	if(isset($_POST['param_ids']) && $_POST['param_ids']!=''){
		$param_ids =  json_decode( stripslashes( $_POST['param_ids'] ), true )!='' ? json_decode( stripslashes( $_POST['param_ids'] ), true ) : explode(",",$_POST['param_ids']);
	}
	$end_it_nb ='';
	if($page!=''){ 
		$param_query['paged'] = $page;
		$count_check = $page*$posts_per_page;
		if(($count_check > $count) && (($count_check - $count)< $posts_per_page)){$end_it_nb = $count - (($page - 1)*$posts_per_page);}
		else if(($count_check > $count)) {die;}
	}
	if($orderby =='rand' && is_array($param_ids)){
		$param_query['post__not_in'] = $param_ids;
		$param_query['paged'] = 1;
	}
	global $wpdb;
	$first_char = isset($_POST['char']) ? $_POST['char'] : '';
	if($first_char!=''){
		$fl_tp = 0;
		$fl_tp = apply_filters( 'extp_filter_aphab_type', $fl_tp );
		if($fl_tp ==1){
			$postids = $wpdb->get_col($wpdb->prepare("
				SELECT      ID
				FROM        $wpdb->posts
				WHERE       post_type = 'ex_team' AND SUBSTR(SUBSTRING_INDEX($wpdb->posts.post_title,' ',-1),1,1) = %s
				ORDER BY    $wpdb->posts.post_title",
				$first_char)
			);
		}else{
			$postids = $wpdb->get_col($wpdb->prepare("
				SELECT      ID
				FROM        $wpdb->posts
				WHERE       post_type = 'ex_team' AND SUBSTR($wpdb->posts.post_title,1,1) = %s
				ORDER BY    $wpdb->posts.post_title",
				$first_char)
			);
		}
		if(!empty($postids)){
			$param_query['post__in'] = $postids;
		}else{
			echo '<span class="extp-nors">'.esc_html__('No matching records found','teampress').'</span>';die;
		}
	}
	//echo '<pre>';
	//print_r($param_query);//exit;
	$the_query = new WP_Query( $param_query );
	$it = $the_query->post_count;
	ob_start();
	if($the_query->have_posts()){
		$i =0;
		$arr_ids = array();
		$html_modal = '';
		while($the_query->have_posts()){ $the_query->the_post();
			$i++;
			$arr_ids[] = get_the_ID();
			$exlk = get_post_meta( get_the_ID(), 'extp_link', true );
			if($layout=='table'){
				extp_template_plugin('table-'.$style,1);
				if ($fullcontent_in=='modal') {
					$getID = $ID.'-'.get_the_ID();
					$html_modal .= ex_teampress_modal_right_html($getID);
				};
			}else if($layout=='list'){
				echo '<div class="tpitem-list item-grid" data-id="ex_id-'.$ID.'-'.get_the_ID().'"> ';
					?>
					<div class="exp-arrow <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'class'); if($exlk!=''){ echo ' extp-exlink';}?>"
					<?php echo ex_teampress_lightbox($fullcontent_in,$ID,'data'); ?> >
						<?php ex_teampress_lightbox($fullcontent_in,$ID,'') ?>
						<?php 
						extp_template_plugin('list-'.$style,1);
						?>
						<div class="exclearfix"></div>
					</div>
					<?php 
					if($orderby =='rand' && is_array($param_ids)){
						?>
				        <script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#<?php  echo esc_html__($_POST['id_crsc']);?> input[name=param_ids]').val(<?php echo str_replace('\/', '/', json_encode(array_merge($param_ids,$arr_ids)));?>);
						});
				        </script>
				        <?php 
					}
					?>
                </div>
				<?php
				if($fullcontent_in =='collapse'){
					extp_template_plugin('collapse',1);
				}else if ($fullcontent_in=='modal') {
						$getID = $ID.'-'.get_the_ID();
						$html_modal .= ex_teampress_modal_right_html($getID);
				};
			}else{?>
                <div class="item-grid de-active" data-id="ex_id-<?php echo esc_attr($ID).'-'.get_the_ID();?>">
                	<div class="exp-arrow <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'class'); if($exlk!=''){ echo ' extp-exlink';}?>"
					<?php echo ex_teampress_lightbox($fullcontent_in,$ID,'data'); ?> >
						<?php ex_teampress_lightbox($fullcontent_in,$ID,'') ?>
		                <?php extp_template_plugin('grid-'.$style,1); ?>
		                <div class="exclearfix"></div>
					</div>
					<?php if($fullcontent_in =='collapse'){
					extp_template_plugin('collapse',1);
					}elseif ($fullcontent_in=='modal') {
						$getID = $ID.'-'.get_the_ID();
						$html_modal .= ex_teampress_modal_right_html($getID);
					};
					if($orderby =='rand' && is_array($param_ids)){
						?>
				        <script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#<?php  echo esc_html__($_POST['id_crsc']);?> input[name=param_ids]').val(<?php echo str_replace('\/', '/', json_encode(array_merge($param_ids,$arr_ids)));?>);
						});
				        </script>
				        <?php 
					}
					?>
                </div>
				<?php
				
			}
			
			if($end_it_nb!='' && $end_it_nb == $i){break;}
		}
		wp_reset_postdata();
		//echo esc_html(str_replace('\/', '/', json_encode($arr_ids)));exit;
	}
	$html = ob_get_clean();
	$output =  array('html_content'=>$html,'html_modal'=> $html_modal);
	echo str_replace('\/', '/', json_encode($output));
	die;
}
if(!function_exists('extp_filter_bar_alphab_html')){
	function extp_filter_bar_alphab_html($alphab_filter){
		if($alphab_filter!='yes'){ return;}
		global $wp;
		$curent_url = home_url( $wp->request );
		?>
        <div class="etp-alphab">
        	<ul class="etp-alphab-list">
            	<li><a href="<?php echo esc_url($curent_url)?>" data-value="" class="current"><?php echo esc_html__('All','teampress');?></a></li>
				<?php 
				$alphab = apply_filters('extp_alphab_text',range('a', 'z'));
				foreach ($alphab as $char) {
					echo '<li><a href="'.esc_url(add_query_arg( array('orderby' => $char), $curent_url )).'" data-value="'.esc_attr($char).'">'.$char.'</a></li>';
				}
				?>
            </ul>
        </div>
        <?php
	}
}
// alphab ajax
add_action( 'wp_ajax_extp_filter_alphab', 'ajax_extp_filter_alphab' );
add_action( 'wp_ajax_nopriv_extp_filter_alphab', 'ajax_extp_filter_alphab' );
function ajax_extp_filter_alphab(){
	global $fullcontent_in,$ID,$number_excerpt;
	$atts = json_decode( stripslashes( $_POST['param_shortcode'] ), true );
	$ID = isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'extp-'.rand(10,9999);
	$ids   = isset($atts['ids']) ? $atts['ids'] : '';
	$count   = isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9';
	$style = isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1';
	$fullcontent_in   = isset($atts['fullcontent_in']) ? $atts['fullcontent_in'] : 'lightbox';
	$posts_per_page   = isset($atts['posts_per_page']) && $atts['posts_per_page'] !=''? $atts['posts_per_page'] : '3';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$cat   = isset($atts['cat']) ? $atts['cat'] : '';
	$location   = isset($atts['location']) ? $atts['location'] : '';
	$page_navi  = isset($atts['page_navi']) ? $atts['page_navi'] : '';
	$page = $_POST['page'];
	$layout = isset($_POST['layout']) ? $_POST['layout'] : '';
	$param_query = json_decode( stripslashes( $_POST['param_query'] ), true );
	$param_ids = '';
	if(isset($_POST['param_ids']) && $_POST['param_ids']!=''){
		$param_ids =  json_decode( stripslashes( $_POST['param_ids'] ), true )!='' ? json_decode( stripslashes( $_POST['param_ids'] ), true ) : explode(",",$_POST['param_ids']);
	}
	$end_it_nb ='';
	if($page!=''){ 
		$param_query['paged'] = $page;
		$count_check = $page*$posts_per_page;
		if(($count_check > $count) && (($count_check - $count)< $posts_per_page)){$end_it_nb = $count - (($page - 1)*$posts_per_page);}
		else if(($count_check > $count)) {die;}
	}
	global $wpdb;
	$first_char = isset($_POST['char']) ? $_POST['char'] : '';
	if($first_char!=''){
		$fl_tp = 0;
		$fl_tp = apply_filters( 'extp_filter_aphab_type', $fl_tp );
		if($fl_tp ==1){
			$postids = $wpdb->get_col($wpdb->prepare("
				SELECT      ID
				FROM        $wpdb->posts
				WHERE       post_type = 'ex_team' AND SUBSTR(SUBSTRING_INDEX($wpdb->posts.post_title,' ',-1),1,1) = %s
				ORDER BY    $wpdb->posts.post_title",
				$first_char)
			);
		}else{
			$postids = $wpdb->get_col($wpdb->prepare("
				SELECT      ID
				FROM        $wpdb->posts
				WHERE       post_type = 'ex_team' AND SUBSTR($wpdb->posts.post_title,1,1) = %s
				ORDER BY    $wpdb->posts.post_title",
				$first_char)
			);
		}
		if(!empty($postids)){
			if($ids !=''){
				$ids = explode(",", $ids);
				$arr = array_intersect($ids,$postids);
				if(empty($arr)){
					$html = '<span class="extp-nors">'.esc_html__('No matching records found','teampress').'</span>';
					echo str_replace('\/', '/', json_encode(array('html_content'=>$html,'page_navi'=> 'off'))); die;
				}
				$postids = $arr;
			}
			$param_query['post__in'] = $postids;
			$param_query['paged'] = 1;
		}else{
			$html = '<span class="extp-nors">'.esc_html__('No matching records found','teampress').'</span>';
			echo str_replace('\/', '/', json_encode(array('html_content'=>$html,'page_navi'=> 'off'))); die;
		}
	}else{
		$param_query['post__in'] ='';
		if($ids !=''){
			$ids = explode(",", $ids);
			$param_query['post__in'] = $ids;
		}
	}
	if(isset($_POST['key_word']) && $_POST['key_word']!=''){
		$param_query['s'] = $_POST['key_word'];
	}else{
		$param_query['s'] = '';
	}
	if(isset($_POST['cat']) && $_POST['cat']!='' && isset($_POST['location']) && $_POST['location']==''){
		$param_query['tax_query'] = array(
			array(
				'taxonomy' => 'extp_cat',
				'field'    => 'slug',
				'terms'    => $_POST['cat'],
			),
		);
	}elseif ($_POST['location']=='' && $location == '') {
		$param_query['tax_query'] ='';
		if($cat!=''){
			$taxonomy ='extp_cat'; 
			$cats = explode(",",$cat);
			if(is_numeric($cats[0])){$field = 'term_id'; }else{ $field = 'slug'; }
			if(count($cats)>1){
				  $texo = array( 'relation' => 'OR');
				  foreach($cats as $iterm) {
					  $texo[] = array(
							  'taxonomy' => $taxonomy,
							  'field' => $field,
							  'terms' => $iterm,
						  );
				  }
			  }else{
				  $texo = array(
					  array(
							  'taxonomy' => $taxonomy,
							  'field' => $field,
							  'terms' => $cats,
						  )
				  );
			}
			$param_query['tax_query'] = $texo;
		}
	}
	if(isset($_POST['location']) && $_POST['location']!='' && isset($_POST['cat']) && $_POST['cat']==''){
		$param_query['tax_query'] = array(
			array(
				'taxonomy' => 'extp_loc',
				'field'    => 'slug',
				'terms'    => $_POST['location'],
			),
		);
	}elseif ($_POST['cat']=='' && $cat == '') {
		$param_query['tax_query'] ='';
		if($location!=''){
			$taxonomy ='extp_loc'; 
			$locations = explode(",",$location);
			if(is_numeric($locations[0])){$field = 'term_id'; }else{ $field = 'slug'; }
			if(count($locations)>1){
				  $texo = array( 'relation' => 'OR');
				  foreach($locations as $iterm) {
					  $texo[] = array(
							  'taxonomy' => $taxonomy,
							  'field' => $field,
							  'terms' => $iterm,
						  );
				  }
			  }else{
				  $texo = array(
					  array(
							  'taxonomy' => $taxonomy,
							  'field' => $field,
							  'terms' => $locations,
						  )
				  );
			}
			$param_query['tax_query'] = $texo;
		}
	}
	if(isset($_POST['location']) && $_POST['location']!='' && isset($_POST['cat']) && $_POST['cat'] !=''){
		$cats = sanitize_text_field($_POST['cat']);
		$locations = sanitize_text_field($_POST['location']);
		$taxonomy_loc = 'extp_loc';
		$taxonomy ='extp_cat';
		$texo = array(
				'relation' => 'AND',
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms'    => $cats,
				),
				array(
					'taxonomy' => $taxonomy_loc,
					'field' => 'slug',
					'terms'    => $locations,
				),
			  );
		$param_query['tax_query'] = $texo;
	}
	if((isset($_POST['location'])  || isset($_POST['cat']) ) && ($location !='' || $cat !='')){
		$taxonomy_loc = 'extp_loc';
		$taxonomy ='extp_cat';
		if(isset($_POST['cat']) && $_POST['cat']!=''){
			$cat = $_POST['cat'];
		}
		if(isset($_POST['location']) && $_POST['location']!=''){
			$location = $_POST['location'];
		}
		$cats = explode(",",$cat);
		$locations = explode(",",$location);
		if($cat!='' && $location!=''){
			if(is_numeric($cats[0])){$field_cat = 'term_id';} else{ $field_cat = 'slug';}
			if(is_numeric($locations[0])){$field_loc = 'term_id';} else{ $field_loc = 'slug';}
			$texo = array(
				'relation' => 'AND',
				array(
					'taxonomy' => $taxonomy,
					'field' => $field_cat,
					'terms'    => $cats,
				),
				array(
					'taxonomy' => $taxonomy_loc,
					'field' => $field_loc,
					'terms'    => $locations,
				),
			);
		}else if ($cat!='' && $location==''){
			if(is_numeric($cats[0])){$field_cat = 'term_id';} else{ $field_cat = 'slug';}
			$texo = array(
				array(
					'taxonomy' => $taxonomy,
					'field' => $field_cat,
					'terms'    => $cats,
				),
			);
		}else{
			if(is_numeric($locations[0])){$field_loc = 'term_id';} else{ $field_loc = 'slug';}
			$texo = array(
				array(
					'taxonomy' => $taxonomy_loc,
					'field' => $field_loc,
					'terms'    => $locations,
				),
			);
		}
		$param_query['tax_query'] = $texo;
	}
	// print_r($param_query['tax_query']);exit;
	$the_query = new WP_Query( $param_query );
	$it = $the_query->post_count;
	ob_start();
	if($the_query->have_posts()){
		$it = $the_query->found_posts;
		if($it < $count || $count=='-1'){ $count = $it;}
		if($count  > $posts_per_page){
			$num_pg = ceil($count/$posts_per_page);
			$it_ep  = $count%$posts_per_page;
		}else{
			$num_pg = 1;
		}
		$arr_ids = array();
		$html_modal = '';
		while($the_query->have_posts()){ $the_query->the_post();
			$i++;
			$exlk = get_post_meta( get_the_ID(), 'extp_link', true );
			if($layout=='table'){
				extp_template_plugin('table-'.$style,1);
			}else if($layout=='list'){
				echo '<div class="tpitem-list item-grid" data-id="ex_id-'.$ID.'-'.get_the_ID().'"> ';
					?>
					<div class="exp-arrow <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'class'); if($exlk!=''){ echo ' extp-exlink';}?>"
					<?php echo ex_teampress_lightbox($fullcontent_in,$ID,'data'); ?> >
						<?php ex_teampress_lightbox($fullcontent_in,$ID,'') ?>
						<?php 
						extp_template_plugin('list-'.$style,1);
						?>
					<div class="exclearfix"></div>
					</div>
                </div>
				<?php
				if($fullcontent_in =='collapse'){
					extp_template_plugin('collapse',1);
				}
			}else{?>
                <div class="item-grid de-active" data-id="ex_id-<?php echo esc_attr($ID).'-'.get_the_ID();?>">
                	<div class="exp-arrow <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'class'); if($exlk!=''){ echo ' extp-exlink';}?>" 
					<?php echo ex_teampress_lightbox($fullcontent_in,$ID,'data'); ?> >
						<?php ex_teampress_lightbox($fullcontent_in,$ID,'') ?>
		                <?php extp_template_plugin('grid-'.$style,1); ?>
		                <div class="exclearfix"></div>
					</div>
					<?php if($fullcontent_in =='collapse'){
					extp_template_plugin('collapse',1);
				} ?>
                </div>
				<?php
				
			}
			if ($fullcontent_in=='modal') {
				$getID = $ID.'-'.get_the_ID();
				$html_modal .= ex_teampress_modal_right_html($getID);
			}
			if($end_it_nb!='' && $end_it_nb == $i){break;}
		}
		wp_reset_postdata();
		//echo esc_html(str_replace('\/', '/', json_encode($arr_ids)));exit;
		?>
        </div>
        <?php
	}
	// echo "ok"; exit;
	$html = ob_get_contents();
	ob_end_clean();
	$html_dcat = '';
	if($html==''){
		$html = '<span class="extp-nors">'.esc_html__('No matching records found','teampress').'</span>';
	}else if(isset($_POST['cat']) && $_POST['cat']!=''){
		$term = get_term_by('slug', $_POST['cat'], 'extp_cat');
		if($term->description!=''){
			$html_dcat ='<p class="extp-dcat" style="display:block;">'.$term->description.'</p>';
		}
	}
	ob_start();
	if($page_navi=='loadmore'){
		extp_ajax_navigate_html($ID,$atts,$num_pg,$param_query,$arr_ids); 
	}else{
		extp_page_number_html($the_query,$ID,$atts,$num_pg,$param_query,$arr_ids);
	}
	$page_navihtml = ob_get_contents();
	ob_end_clean();
	$output =  array('html_content'=>$html,'page_navi'=> $page_navihtml,'html_modal'=>$html_modal, 'html_dcat' => $html_dcat);
	echo str_replace('\/', '/', json_encode($output));
	die;
}
if(!function_exists('extp_search_form_html')){
	function extp_search_form_html($cats,$category_style,$order_cat,$location_box,$location,$location_style,$order_location,$active_filter,$search_box,$category_box){
		if($category_box=="hide" && $location_box=="hide" && $search_box=="hide") {
			return;
		}
		$class ='';
		if ($category_style=='inline'){ 
    		$class = 'extp-cat-inline';
    	}
		$args = array(
			'hide_empty'        => true,
			'parent'        => '0',
		);
		/*if($cats !=''){
		    unset($args['parent']);
		}*/
		$cats = $cats!=''? explode(",",$cats) : array();
		if (!empty($cats) && !is_numeric($cats[0])) {
			$args['slug'] = $cats;
			$args['orderby'] = 'slug__in';
		}else if (!empty($cats)) {
			$args['include'] = $cats;
			$args['orderby'] = 'include';
		}
		if ($order_cat == 'yes' && empty($cats)) {
			$args['meta_key'] = 'extp_cat_order';
			$args['orderby'] = 'meta_value';
		}
		$args = apply_filters('extp_filter_args_query',$args);
		$terms = get_terms('extp_cat', $args);
		$class_all='';
		if ($category_style !='inline' && $location_style !='inline' && $location_box=='show') {
			$class_all='extp-search-select';
		}elseif ($category_style =='inline' && $location_style =='inline'  && $location_box=='show') {
			$class_all='extp-search-inline';
		}elseif ($category_style !='inline' && $location_style =='inline' && $location_box=='show') {
			$class_all='extp-search-select-cat';
		}
		?>
        <div class="extp-search <?php echo $class_all;?>">
        <form role="search" method="get" id="searchform" class="etp-search-form <?php echo $class; ?>" action="<?php echo home_url(); ?>/">
        	<div class="extp-search-group">
            
            <?php
            if($category_box!="hide") {
	            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
	            	<?php if ($category_style=='inline'){ 
	            		extp_child_cat_html($cats,$order_cat,$active_filter);
	            	} 
	            	$name_all = apply_filters( 'extp_ftcat_lbname', esc_html__('All Categories','teampress') );
	            	?>
	                <select name="extp_cat">
	                	<option value=""><?php echo $name_all; ?></option>
	                  	<?php
	                  	$count_stop = 5;
					  	foreach ( $terms as $term ) {
					  		$cls='';
					  		if(isset( $_GET['tcat']) && $_GET['tcat'] == $term->slug ){
				            	$cls = 'selected';
				            }else if($active_filter!='' && $active_filter == $term->slug){
	        					$cls  = 'selected';
	        				}
					  		echo '<option value="'. $term->slug .'" '.esc_attr($cls).'>'. $term->name .'</option>';
					  		extp_show_child_inline($cats,$term,$count_stop,'','category',$order_cat);
					  	}
					  ?>
	                </select>
	            <?php
		        } 
		    }//if have terms ?>
	        <?php if($location_box=="show") {extp_location_html($location,$location_style,$order_location);}
	        if($search_box!="hide") {?>
              <input type="hidden" name="post_type" value="ex_team" />
              <span class="search-btsm">
              	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php echo  esc_html__('Search','teampress'); ?>" class="form-control" />
              	<button type="submit" class="tp-search-submit" ><i class="fa fa-search"></i></button>
              </span>
            <?php }?>  
            </div>
        </form>
        <style type="text/css">
			
		</style>
        </div>
        
        <?php
	}
}
if(!function_exists('extp_child_cat_html')){
	function extp_child_cat_html($cats,$order_cat,$active_filter){
		$args = array(
			'parent'        => '0',
			'hide_empty'        => true,
		);
		/*if(!empty($cats)){
		    unset($args['parent']);
		}*/
		if (!empty($cats) && !is_numeric($cats[0])) {
			$args['slug'] = $cats;
			$args['orderby'] = 'slug__in';
		}else if (!empty($cats)) {
			$args['include'] = $cats;
			$args['orderby'] = 'include';
		}
		if ($order_cat == 'yes' && empty($cats)) {
			$args['meta_key'] = 'extp_cat_order';
			$args['orderby'] = 'meta_value';
		}
		$terms = get_terms('extp_cat', $args);
		$count_stop = 5;
		if ($terms) {
		global $wp;
		$curent_url = home_url( $wp->request );
		?>
        <div class="extp-child_cat extp-cat-box">
			<ul class="extp-top-cat">
				<?php
				$cls = 'extp-child-active';
        		if(isset($active_filter) && $active_filter != ''){
        			$cls = '';	
        		}
				if(isset( $_GET['tcat']) && $_GET['tcat'] != '' ){
					$cls = $active_filter ='';
				}
				$name_all = apply_filters( 'extp_ftcat_lbname', esc_html__('All','teampress') );
				echo '<li class="extp-top-term extp-child-click '.$cls.'" data-value=""><span>'.$name_all.'</span>';
				
				foreach ($terms as $term) {
					$cls ='';
					if($active_filter!='' && $active_filter == $term->slug){
    					$cls = 'extp-child-active';
    				}
		            if(isset( $_GET['tcat']) && $_GET['tcat'] == $term->slug ){
		            	$cls = 'extp-child-active';
		            }
					echo '<li class="extp-top-term extp-child-click '.$cls.'" data-value="'.$term->slug.'">
					<a href="'.esc_url(add_query_arg( array('tcat' => $term->slug), $curent_url )).'">'.$term->name.'</a>';
					extp_show_child_inline($cats,$term,$count_stop,'inline','category',$order_cat);
					
			        echo '</li>';
				}?>
			</ul>
        </div>
        
        <?php }
	}
}
if(!function_exists('extp_show_child_inline')){
	function extp_show_child_inline($cats,$term,$count_stop,$inline,$type,$order_cat=false){
		if ($count_stop < 2) {
			return;
		}
		$charactor ='';
		if ($count_stop == 5) {
			$charactor ='— ';
		}elseif ($count_stop == 4) {
			$charactor ='—— ';
		}elseif ($count_stop == 3) {
			$charactor ='——— ';
		}elseif ($count_stop == 2) {
			$charactor ='———— ';
		}
		$args_child = array(
				'child_of' => $term->term_id,
				'parent' => $term->term_id,
				'hide_empty'        => false,
		);
		if (!empty($cats) && !is_numeric($cats[0])) {
			$args_child['slug'] = $cats;
		}else if (!empty($cats)) {
			$args_child['include'] = $cats;
		}

		if (isset($order_cat) && $order_cat == 'yes' && empty($cats)) {
			$args_child['meta_key'] = 'extp_cat_order';
			$args_child['orderby'] = 'meta_value';
		}else{
			$order_cat ='';
		}
		$args_child = apply_filters('extp_child_cat_args',$args_child);
		if ($type == 'category') {
			$second_level_terms = get_terms('extp_cat', $args_child);
		}else{
			$second_level_terms = get_terms('extp_loc', $args_child);
		}
		global $wp;
		$curent_url = home_url( $wp->request );
		if ($second_level_terms) {
			$count_stop = $count_stop -1;
			if ($inline != 'inline') {
				foreach ($second_level_terms as $second_level_term) {
					$cls ='';
		            if(isset( $_GET['tcat']) && $_GET['tcat'] == $second_level_term->slug ){
		            	$cls = 'selected';
		            }
					echo '<option value="'. $second_level_term->slug .'" '.esc_attr($cls).'>'.$charactor. $second_level_term->name .'</option>';
					extp_show_child_inline($cats,$second_level_term,$count_stop,'',$type,$order_cat);
				}
			}else{
				echo '<span class="extp-caret"></span>';
		        echo '<ul class="extp-ul-child">';
		        foreach ($second_level_terms as $second_level_term) {
		            $second_term_name = $second_level_term->name;
		            $cls ='';
		            if(isset( $_GET['tcat']) && $_GET['tcat'] == $second_level_term->slug ){
		            	$cls = 'extp-child-active';
		            }
		            echo '<li class="extp-child-click '.esc_attr($cls).'" data-value="'.$second_level_term->slug.'">
		            <a href="'.esc_url(add_query_arg( array('tcat' => $second_level_term->slug), $curent_url )).'">'.$second_term_name.'</a>';
		            extp_show_child_inline($cats,$second_level_term,$count_stop,'inline',$type,$order_cat);
		            echo '</li>';
		        }

		        echo '</ul>';
		    }
	    }
	}
}
if(!function_exists('extp_location_html')){
	function extp_location_html($locations,$location_style,$order_location){
		$class = '';
		if ($location_style=='inline'){ 
    		$class = 'extp-loc-inline';
    	}
		$args = array(
			'hide_empty'        => true,
			'parent'        => '0',
		);
		$locations = $locations!=''? explode(",",$locations) : array();
		if (!empty($locations) && !is_numeric($locations[0])) {
			$args['slug'] = $locations;
			$args['orderby'] = 'slug__in';
		}else if (!empty($locations)) {
			$args['include'] = $locations;
			$args['orderby'] = 'include';
		}
		if ($order_location == 'yes' && empty($locations)) {
			$args['meta_key'] = 'extp_loc_order';
			$args['orderby'] = 'meta_value';
		}
		$terms = get_terms('extp_loc', $args);
		$name_all = apply_filters( 'extp_loc_lbname', esc_html__('All Locations','teampress') );
		$count_stop = 5;
		?>
        <div class="extp-loc_parent <?php echo $class; ?>">
        	<div class="extp-loc">
            
            <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
            	<?php if ($location_style=='inline'){ ?>
            		<div class="extp-child_cat">
						<ul class="extp-top-cat">
							<?php
							echo '<li class="extp-top-term extp-child-click extp-child-active" data-value=""><span>'.$name_all.'</span>';
							
							foreach ($terms as $term) {
								echo '<li class="extp-top-term extp-child-click " data-value="'.$term->slug.'">'.$term->name;
								extp_show_child_inline($locations,$term,$count_stop,'inline','location');
								
						        echo '</li>';
							}?>
						</ul>
			        </div>
            	<?php } 

            	?>
                <select name="extp_location">
                	<option value=""><?php echo $name_all; ?></option>
                  	<?php
                  	
				  	foreach ( $terms as $term ) {
				  		echo '<option value="'. $term->slug .'">'. $term->name .'</option>';
				  		extp_show_child_inline($locations,$term,$count_stop,'','location');
				  	}
				  ?>
                </select>
            <?php
	        } //if have terms ?>

            </div>

        </div>
        
        <?php
	}
}

function extp_convert_color($color){
	if ($color == '') {
		return;
	}
	$hex  = str_replace("#", "", $color);
	if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
	}
	$rgb = $r.','. $g.','.$b;
	return $rgb;
}
function extp_custom_single_color($style){
	$extp_color = get_post_meta( get_the_ID(), 'extp_color', true );
	global $ID;
	if($extp_color!=''){?>
	<style type="text/css">
		<?php if($style==1 || $style==2 || $style==5 || $style==6 || $style==10 || $style==12 || $style==13 || $style=='img-1'){?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a:hover{ background:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==3) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a,
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .tpstyle-3-rib{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==4) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .tpstyle-4-image{ border:2px solid <?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?>  h3 a{ color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==7) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .tpstyle-7-profile{ border:solid 5px <?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> { background-color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==8) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a:hover{ background:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .tpstyle-8-position{ background-color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==9) {$rgb = extp_convert_color($extp_color);?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .tpstyle-9-position{ background:rgba(<?php echo esc_attr($rgb);?>,.7)}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==11) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a:hover{ background:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 span{ color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==14 || $style==15 || $style==16 || $style==18) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==17) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> p:after, 
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account{ background:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> p:after{ border-color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==19) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h5{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .tpstyle-19-image{ border-color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .tpstyle-19-image:before{ border-top-color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> { background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style==20) {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3,
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?>.tpstyle-20-blue:before,
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?>.tpstyle-20-blue:after{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='img-2' || $style=='img-3') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h5{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a:hover{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='img-4') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ border-color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a:hover{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='img-5' || $style=='img-6') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h5{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='img-7') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a:hover{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='img-8') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> >i{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a:hover{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='img-9') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='img-10') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h5{ color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='list-1'|| $style=='list-2') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> .ex-social-account li a:hover{ background:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='list-3') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h5{ color:<?php echo esc_attr($extp_color);?>}
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> { border-color:<?php echo esc_attr($extp_color);?>}
		<?php } elseif ($style=='table-1'|| $style=='table-2') {?>
			#<?php echo $ID?>.ex-tplist .tppost-<?php the_ID();?> h3 a{ color:<?php echo esc_attr($extp_color);?>}
		<?php }	?>

	</style>
	<?php }
}

if(!function_exists('extp_taxonomy_info')){
	function extp_taxonomy_info( $tax, $link=false, $id= false){
		if(isset($id) && $id!=''){
			$product_id = $id;
		}else{
			$product_id = get_the_ID();
		}
		$post_type = 'ex_team';
		ob_start();
		if(isset($tax) && $tax!=''){
			$args = array(
				'hide_empty'        => true, 
			);
			$terms = wp_get_post_terms($product_id, $tax, $args);
			if(!empty($terms) && !is_wp_error( $terms )){
				$c_tax = count($terms);
				$i=0;
				foreach ( $terms as $term ) {
					$i++;
					if(isset($link) && $link=='off'){
						echo $term->name;
					}else{
						echo '<a href="'.get_term_link( $term ).'" title="' . $term->name . '">'. $term->name .'</a>';
					}
					if($i != $c_tax){ echo '<span>, </span>';}
				}
			}
		}
		$output_string = ob_get_contents();
		ob_end_clean();
		return apply_filters('extp_taxonomy_html',$output_string,$tax);
	}
}