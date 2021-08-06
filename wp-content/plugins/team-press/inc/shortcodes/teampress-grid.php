<?php
function extp_shortcode_grid( $atts ) {
	if(phpversion()>=7){
		$atts = (array)$atts;
	}
	global $fullcontent_in,$ID,$number_excerpt;
	$ID = isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'extp-'.rand(10,9999);
	if(!isset($atts['ID'])){
		$atts['ID']= $ID;
	}
	$style = isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1';
	$column = isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '2';
	$posttype = 'ex_team';
	$ids   = isset($atts['ids']) ? $atts['ids'] : '';
	$taxonomy  = isset($atts['taxonomy']) ? $atts['taxonomy'] : '';
	$cat   = isset($atts['cat']) ? $atts['cat'] : '';
	$order_cat   = isset($atts['order_cat']) ? $atts['order_cat'] : '';
	$location   = isset($atts['location']) ? $atts['location'] : '';
	$order_location   = isset($atts['order_location']) ? $atts['order_location'] : '';
	$tag  = isset($atts['tag']) ? $atts['tag'] : '';
	$count   = isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9';
	$fullcontent_in   = isset($atts['fullcontent_in']) ? $atts['fullcontent_in'] : '';
	$search_box   = isset($atts['search_box']) ? $atts['search_box'] : 'hide';
	$category_box   = isset($atts['category_box']) ? $atts['category_box'] : '';
	$location_box   = isset($atts['location_box']) ? $atts['location_box'] : 'hide';
	$category_style   = isset($atts['category_style']) ? $atts['category_style'] : '';
	$location_style   = isset($atts['location_style']) ? $atts['location_style'] : '';
	$posts_per_page   = isset($atts['posts_per_page']) && $atts['posts_per_page'] !=''? $atts['posts_per_page'] : '3';
	$active_filter   = isset($atts['active_filter']) ? $atts['active_filter'] : '';
	$order  = isset($atts['order']) ? $atts['order'] : '';
	$orderby  = isset($atts['orderby']) ? $atts['orderby'] : '';
	$meta_key  = isset($atts['meta_key']) ? $atts['meta_key'] : '';
	$meta_value  = isset($atts['meta_value']) ? $atts['meta_value'] : '';
	$class  = isset($atts['class']) ? $atts['class'] : '';
	$page_navi  = isset($atts['page_navi']) ? $atts['page_navi'] : '';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$masonry  = isset($atts['masonry']) ? $atts['masonry'] : '';
	$alphab_filter  = isset($atts['alphab_filter']) ? $atts['alphab_filter'] : '';
	$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);

	$list_cat = $cat;
	if($active_filter!=''){ 
		$cat = $active_filter;
	}
	$args = ex_teampress_query($posttype, $posts_per_page, $order, $orderby, $cat, $tag, 'extp_cat', $meta_key, $ids, $location, $meta_value);
	
	$the_query = new WP_Query( $args );
	ob_start();
	$class = $class." column-".$column;
	$class = $class." style-".$style;
	if($style == 1 || $style == 3 || $style == 13 || $style == 14 || $style == 15 || $style == 16){
		$class = $class." style-classic";
	}
	$html_modal ='';
	$class = $class." fct-".$fullcontent_in;
	if($masonry=='yes'){
		$class = $class." extp-masonry";
		wp_enqueue_script( 'extp-masonry', TEAMPRESS_PATH.'js/masonry.pkgd.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'extp-imageloaded', TEAMPRESS_PATH.'js/imagesloaded.pkgd.min.js', array( 'jquery' ) );
	}
	?>
	<div class="ex-tplist <?php echo esc_attr($class);?>" id ="<?php echo esc_attr($ID);?>">
		<?php extp_filter_bar_alphab_html($alphab_filter);?>
        <?php extp_search_form_html($list_cat,$category_style,$order_cat,$location_box,$location,$location_style,$order_location,$active_filter,$search_box,$category_box);?>
        <?php 
        if(isset( $_GET['tcat']) && $_GET['tcat'] !=''|| $active_filter!=''){
        	$active_cat = $_GET['tcat'];
        	if((!isset($_GET['tcat']) || $_GET['tcat']=='') && $active_filter!=''){
        		$active_cat= $active_filter;
        	}
			$term = get_term_by('slug', $active_cat, 'extp_cat');
			if($term->description!=''){
				echo '<p class="extp-dcat" style="display:block;">'.$term->description.'</p>';
			}
		}
        ?>
        <div class="ctgrid">
		<?php
		$num_pg = '';
		$arr_ids = array();
		if ($the_query->have_posts()){ 
			$i=0;
			$it = $the_query->found_posts;
			if($it < $count || $count=='-1'){ $count = $it;}
			if($count  > $posts_per_page){
				$num_pg = ceil($count/$posts_per_page);
				$it_ep  = $count%$posts_per_page;
			}else{
				$num_pg = 1;
			}
			$arr_ids = array();
			while ($the_query->have_posts()) { $the_query->the_post();
				$arr_ids[] = get_the_ID();
				$i++;
				if(($num_pg == $paged) && $num_pg!='1'){
					if($i > $it_ep){ break;}
				}
				echo '<div class="item-grid" data-id="ex_id-'.$ID.'-'.get_the_ID().'"> ';
				$exlk = get_post_meta( get_the_ID(), 'extp_link', true );
					?>
					<div class="exp-arrow <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'class'); if($exlk!=''){ echo ' extp-exlink';}?>" 
					<?php echo ex_teampress_lightbox($fullcontent_in,$ID,'data'); ?> >
						<?php ex_teampress_lightbox($fullcontent_in,$ID,'') ?>
						<?php 
						extp_template_plugin('grid-'.$style,1);
						?>
					<div class="exclearfix"></div>
					</div>
					<?php
					if($fullcontent_in =='collapse'){
						extp_template_plugin('collapse',1);
					}else if ($fullcontent_in=='modal') {
						$getID = $ID.'-'.get_the_ID();
						$html_modal .= ex_teampress_modal_right_html($getID);
					}
				echo '</div>';
			}
		} ?>
		</div>
		<?php if ($html_modal!='') {		?>
			<div class="ex-overlay"></div>
	        <div class="extsc-hidden" id ="md-<?php echo esc_attr($ID);?>">
            	<div class="exp-mdcontaner"><?php echo $html_modal;?></div>
                <div class="extp-mdbutton">
                	<div class="extp-mdleft"></div>
                    <div class="extp-mdright"></div>
                    <div class="extp-mdclose">&nbsp;</div>
                </div>
            </div>
	    <?php } ?>
        
		<?php
		if($page_navi=='loadmore'){
			extp_ajax_navigate_html($ID,$atts,$num_pg,$args,$arr_ids); 
		}else{ ?>
			<div class="extp-pagination-parent">
			<?php extp_page_number_html($the_query,$ID,$atts,$num_pg,$args,$arr_ids);?>
			</div>
		<?php }
		?>
	</div>
	<?php
	wp_reset_postdata();
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode( 'ex_tpgrid', 'extp_shortcode_grid' );
add_action( 'after_setup_theme', 'wt_reg_grid_vc' );
function wt_reg_grid_vc(){
    if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("TeamPress - Grid", "teampress"),
	   "base" => "ex_tpgrid",
	   "class" => "",
	   "icon" => "icon-grid",
	   "controls" => "full",
	   "category" => esc_html__('TeamPress','teampress'),
	   "params" => array(
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Style", 'teampress'),
			 "param_name" => "style",
			 "value" => array(
				esc_html__('1', 'teampress') => '1',
				esc_html__('2', 'teampress') => '2',
				esc_html__('3', 'teampress') => '3',
				esc_html__('4', 'teampress') => '4',
				esc_html__('5', 'teampress') => '5',
				esc_html__('6', 'teampress') => '6',
				esc_html__('7', 'teampress') => '7',
				esc_html__('8', 'teampress') => '8',
				esc_html__('9', 'teampress') => '9',
				esc_html__('10', 'teampress') => '10',
				esc_html__('11', 'teampress') => '11',
				esc_html__('12', 'teampress') => '12',
				esc_html__('13', 'teampress') => '13',
				esc_html__('14', 'teampress') => '14',
				esc_html__('15', 'teampress') => '15',
				esc_html__('16', 'teampress') => '16',
				esc_html__('17', 'teampress') => '17',
				esc_html__('18', 'teampress') => '18',
				esc_html__('19', 'teampress') => '19',
				esc_html__('20', 'teampress') => '20',
				esc_html__('Image hover 1', 'teampress') => 'img-1',
				esc_html__('Image hover 2', 'teampress') => 'img-2',
				esc_html__('Image hover 3', 'teampress') => 'img-3',
				esc_html__('Image hover 4', 'teampress') => 'img-4',
				esc_html__('Image hover 5', 'teampress') => 'img-5',
				esc_html__('Image hover 6', 'teampress') => 'img-6',
				esc_html__('Image hover 7', 'teampress') => 'img-7',
				esc_html__('Image hover 8', 'teampress') => 'img-8',
				esc_html__('Image hover 9', 'teampress') => 'img-9',
				esc_html__('Image hover 10', 'teampress') => 'img-10',
			 ),
			 "description" => esc_html__('Number of style', 'teampress')
		  ),
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Columns", 'teampress'),
			 "param_name" => "column",
			 "value" => array(
				esc_html__('2 columns', 'teampress') => '2',
				esc_html__('3 columns', 'teampress') => '3',
				esc_html__('4 columns', 'teampress') => '4',
				esc_html__('5 columns', 'teampress') => '5',
			 ),
			 "description" => esc_html__('Number of column', 'teampress')
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Count", "teampress"),
			"param_name" => "count",
			"value" => "",
			"description" => esc_html__("Number of posts", 'teampress'),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Posts per page", "teampress"),
			"param_name" => "posts_per_page",
			"value" => "",
			"description" => esc_html__("Number items per page", 'teampress'),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Full content in", 'teampress'),
			 "param_name" => "fullcontent_in",
			 "value" => array(
				esc_html__('None', 'teampress') => '',
				esc_html__('Lightbox', 'teampress') => 'lightbox',
				esc_html__('Collapse', 'teampress') => 'collapse',
				esc_html__('Modal', 'teampress') => 'modal',
			 ),
			 "description" => esc_html__('Show full infomartion member in', 'teampress')
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("IDs", "teampress"),
			"param_name" => "ids",
			"value" => "",
			"description" => esc_html__("Specify post IDs to retrieve", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Category", "teampress"),
			"param_name" => "cat",
			"value" => "",
			"description" => esc_html__("List of cat ID (or slug), separated by a comma", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Location", "teampress"),
			"param_name" => "location",
			"value" => "",
			"description" => esc_html__("List of location ID (or slug), separated by a comma", "teampress"),
		  ),
		  /*array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Tags", "teampress"),
			"param_name" => "tag",
			"value" => "",
			"description" => esc_html__("List of tags, separated by a comma", "teampress"),
		  ),*/
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order", 'teampress'),
			 "param_name" => "order",
			 "value" => array(
			 	esc_html__('DESC', 'teampress') => 'DESC',
				esc_html__('ASC', 'teampress') => 'ASC',
			 ),
			 "description" => ''
		  ),
		  array(
		  	 "admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order by", 'teampress'),
			 "param_name" => "orderby",
			 "value" => array(
			 	esc_html__('Date', 'teampress') => 'date',
				esc_html__('ID', 'teampress') => 'ID',
				esc_html__('Author', 'teampress') => 'author',
			 	esc_html__('Title', 'teampress') => 'title',
				esc_html__('Name', 'teampress') => 'name',
				esc_html__('Modified', 'teampress') => 'modified',
			 	esc_html__('Parent', 'teampress') => 'parent',
				esc_html__('Random', 'teampress') => 'rand',
				esc_html__('Menu order', 'teampress') => 'menu_order',
				esc_html__('Meta value', 'teampress') => 'meta_value',
				esc_html__('Meta value num', 'teampress') => 'meta_value_num',
				esc_html__('Post__in', 'teampress') => 'post__in',
				esc_html__('None', 'teampress') => 'none',
			 ),
			 "description" => ''
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Meta key", "teampress"),
			"param_name" => "meta_key",
			"value" => "",
			"description" => esc_html__("Enter meta key to query", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Meta value", "teampress"),
			"param_name" => "meta_value",
			"value" => "",
			"description" => esc_html__("Enter meta value to query", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Number of Excerpt", "teampress"),
			"param_name" => "number_excerpt",
			"value" => "",
			"description" => esc_html__("Enter number", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Page navi", 'teampress'),
			 "param_name" => "page_navi",
			 "value" => array(
			 	esc_html__('Number', 'teampress') => '',
				esc_html__('Load more', 'teampress') => 'loadmore',
			 ),
			 "description" => esc_html__("Select type of page navigation", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Search box", 'teampress'),
			 "param_name" => "search_box",
			 "value" => array(
			 	esc_html__('Hide', 'teampress') => 'hide',
			 	esc_html__('Show', 'teampress') => 'show',
			 ),
			 "description" => esc_html__("Show search box", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Category box", 'teampress'),
			 "param_name" => "category_box",
			 "value" => array(
			 	esc_html__('Show', 'teampress') => 'show',
			 	esc_html__('Hide', 'teampress') => 'hide',
			 ),
			 "description" => esc_html__("Show Category filter", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Active special category", "teampress"),
			"param_name" => "active_filter",
			"value" => "",
			"description" => esc_html__("Enter special slug of category to active instead of All", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order Category Filter", 'teampress'),
			 "param_name" => "order_cat",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
			 	esc_html__('Yes', 'teampress') => 'yes',
			 ),
			 "description" => esc_html__("Order Category with custom order", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Category Style", 'teampress'),
			 "param_name" => "category_style",
			 "value" => array(
			 	esc_html__('Select box', 'teampress') => '',
			 	esc_html__('Inline', 'teampress') => 'inline',
			 ),
			 "description" => esc_html__("Choice Category Style", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Location box", 'teampress'),
			 "param_name" => "location_box",
			 "value" => array(
			 	esc_html__('Hide', 'teampress') => 'hide',
			 	esc_html__('Show', 'teampress') => 'show',
			 ),
			 "description" => esc_html__("Show location box", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order Location Filter", 'teampress'),
			 "param_name" => "order_location",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
			 	esc_html__('Yes', 'teampress') => 'yes',
			 ),
			 "description" => esc_html__("Order Location with custom order", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Location Style", 'teampress'),
			 "param_name" => "location_style",
			 "value" => array(
			 	esc_html__('Select box', 'teampress') => '',
			 	esc_html__('Inline', 'teampress') => 'inline',
			 ),
			 "description" => esc_html__("Choice Location Style", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Alphabetical filter", 'teampress'),
			 "param_name" => "alphab_filter",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
				esc_html__('Yes', 'teampress') => 'yes',
			 ),
			 "description" => esc_html__("Show Alphabetical filter", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Masonry layout", 'teampress'),
			 "param_name" => "masonry",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
				esc_html__('Yes', 'teampress') => 'yes',
			 ),
			 "description" => esc_html__("Enable Masonry layout", "teampress"),
		  ),
	   )
	));
	}
}