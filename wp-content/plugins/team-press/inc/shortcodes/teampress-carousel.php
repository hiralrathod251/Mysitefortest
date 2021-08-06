<?php
function extp_shortcode_carousel( $atts ) {
	if(phpversion()>=7){
		$atts = (array)$atts;
	}
	global $fullcontent_in,$ID,$number_excerpt;
	$ID = isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'ex-'.rand(10,9999);
	if(!isset($atts['ID'])){
		$atts['ID']= $ID;
	}
	$style = isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1';
	$column = isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '2';
	$posttype   = isset($atts['posttype']) && $atts['posttype']!='' ? $atts['posttype'] : 'ex_team';
	$ids   = isset($atts['ids']) ? $atts['ids'] : '';
	$taxonomy  = isset($atts['taxonomy']) ? $atts['taxonomy'] : '';
	$cat   = isset($atts['cat']) ? $atts['cat'] : '';
	$location   = isset($atts['location']) ? $atts['location'] : '';
	$tag  = isset($atts['tag']) ? $atts['tag'] : '';
	$count   = isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9';
	$fullcontent_in   = isset($atts['fullcontent_in']) ? $atts['fullcontent_in'] : '';
	$order  = isset($atts['order']) ? $atts['order'] : '';
	$orderby  = isset($atts['orderby']) ? $atts['orderby'] : '';
	$meta_key  = isset($atts['meta_key']) ? $atts['meta_key'] : '';
	$meta_value  = isset($atts['meta_value']) ? $atts['meta_value'] : '';
	$class  = isset($atts['class']) ? $atts['class'] : '';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$slidesshow = isset($atts['slidesshow'])&& $atts['slidesshow']!='' ? $atts['slidesshow'] : '3';
	$autoplay 		= isset($atts['autoplay']) && $atts['autoplay'] == 1 ? 1 : 0;
	$autoplayspeed 		= isset($atts['autoplayspeed']) && is_numeric($atts['autoplayspeed']) ? $atts['autoplayspeed'] : '';
	$start_on 		= isset($atts['start_on']) ? $atts['start_on'] : '';
	$loading_effect 		= isset($atts['loading_effect']) ? $atts['loading_effect'] : '';
	$infinite 		= isset($atts['infinite']) ? $atts['infinite'] : '';
	$slidestoscroll 		= isset($atts['slidestoscroll']) ? $atts['slidestoscroll'] : '';
	$args = ex_teampress_query($posttype, $count, $order, $orderby, $cat, $tag, $taxonomy, $meta_key, $ids, $location, $meta_value);
	$the_query = new WP_Query( $args );
	ob_start();
	//$class = $class." column-".$column;
	$class = $class." style-".$style;
	if($style == 1 || $style == 3 || $style == 13 || $style == 14 || $style == 15 || $style == 16){
		$class = $class." style-classic";
	}
	$class = $class." fct-".$fullcontent_in;
	if($loading_effect == 1){
		$class = $class.' ld-screen';
	}
	$html_modal ='';
	wp_enqueue_style( 'wpex-ex_s_lick', TEAMPRESS_PATH .'js/ex_s_lick/ex_s_lick.css');
	wp_enqueue_style( 'wpex-ex_s_lick-theme', TEAMPRESS_PATH .'js/ex_s_lick/ex_s_lick-theme.css');
	wp_enqueue_script( 'wpex-ex_s_lick', TEAMPRESS_PATH.'js/ex_s_lick/ex_s_lick.js', array( 'jquery' ) );
	$extp_enable_rtl = extp_get_option('extp_enable_rtl');
	$m_brp = apply_filters('extp_mobile_breakpoint','480');
	$t_brp = apply_filters('extp_tablet_breakpoint','768');
	$tl_brp = apply_filters('extp_tablet_breakpoint_ls','1024');
	?>
	<div <?php if($extp_enable_rtl=='yes'){ echo 'dir="rtl"';} ?> class="ex-tplist ex-tpcarousel <?php echo esc_attr($class);?>" id="<?php echo esc_attr($ID);?>" data-autoplay="<?php echo esc_attr($autoplay)?>" data-speed="<?php echo esc_attr($autoplayspeed)?>" data-rtl="<?php echo esc_attr($extp_enable_rtl)?>" data-slidesshow="<?php echo esc_attr($slidesshow)?>"  data-start_on="<?php echo esc_attr($start_on)?>" data-infinite="<?php echo esc_attr($infinite);?>" data-slidestoscroll="<?php echo esc_attr($slidestoscroll);?>" data-mbrp="<?php echo esc_attr($m_brp)?>" data-tbrp="<?php echo esc_attr($t_brp)?>" data-tlbrp="<?php echo esc_attr($tl_brp)?>">
    	<?php if($loading_effect==1){?>
            <div class="extp-loadcont"><div class="extp-loadicon"></div></div>
            <script>
                jQuery(window).load(function(e) {
                    jQuery("#<?php echo esc_attr($ID);?>").addClass('at-childdiv');
                });
                setTimeout(function() {
                    jQuery("#<?php echo esc_attr($ID);?>").addClass('at-childdiv');
                }, 7000);
            </script>
        <?php } ?>
		<div class="ctgrid">
		<?php
		if ($the_query->have_posts()){ 
			while ($the_query->have_posts()) { $the_query->the_post();
				echo '<div class="item-grid" data-id="ex_id-'.$ID.'-'.get_the_ID().'"> ';
					?>
					<div class="exp-arrow <?php echo ex_teampress_lightbox($fullcontent_in,$ID,'class');?>" 
					<?php echo ex_teampress_lightbox($fullcontent_in,$ID,'data'); ?> >
						<?php ex_teampress_lightbox($fullcontent_in,$ID,'') ?>
						<?php 
						extp_template_plugin('grid-'.$style,1);
						?>
					<div class="exclearfix"></div>
					</div>
					<?php
					if ($fullcontent_in=='modal') {
						$getID = $ID.'-'.get_the_ID();
						$html_modal .= ex_teampress_modal_right_html($getID);
					}
				echo '</div>';
			}
		}
		?>
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
	</div>
	<?php
	wp_reset_postdata();
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode( 'ex_tpcarousel', 'extp_shortcode_carousel' );
add_action( 'after_setup_theme', 'wt_reg_carousel_vc' );
function wt_reg_carousel_vc(){
    if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("TeamPress - Carousel", "teampress"),
	   "base" => "ex_tpcarousel",
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
			"type" => "textfield",
			"heading" => esc_html__("Count", "teampress"),
			"param_name" => "count",
			"value" => "",
			"description" => esc_html__("Number of posts", 'teampress'),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Number item visible", "teampress"),
			"param_name" => "slidesshow",
			"value" => "",
			"description" => esc_html__("Enter number", 'teampress'),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Number item to scroll", "teampress"),
			"param_name" => "slidestoscroll",
			"value" => "",
			"description" => esc_html__("Enter number", 'teampress'),
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
		 //  array(
		 //  	"admin_label" => true,
			// "type" => "textfield",
			// "heading" => esc_html__("Tags", "teampress"),
			// "param_name" => "tag",
			// "value" => "",
			// "description" => esc_html__("List of tags, separated by a comma", "teampress"),
		 //  ),
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
			"description" => esc_html__("Enter number, Default:10", "teampress"),
		  ),
		  /*array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Slide to start on", "teampress"),
			"param_name" => "start_on",
			"value" => "",
			"description" => esc_html__("Enter number, Default:0", "teampress"),
		  ),*/
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Autoplay", 'teampress'),
			 "param_name" => "autoplay",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
				esc_html__('Yes', 'teampress') => '1',
			 ),
			 "description" => ''
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "textfield",
			 "class" => "",
			 "heading" => esc_html__("Autoplay Speed", "teampress"),
			 "param_name" => "autoplayspeed",
			 "value" => "",
			 "dependency" 	=> array(
				'element' => 'autoplay',
				'value'   => array('1'),
			 ),
			 "description" => esc_html__("Autoplay Speed in milliseconds. Default:3000", "teampress"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Enable Loading effect", "teampress"),
			 "param_name" => "loading_effect",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
			 	esc_html__('Yes', 'teampress') => '1',
			 ),
			 "description" => ""
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "hide-intable hide-ingrid",
			 "heading" => esc_html__("Infinite", "teampress"),
			 "param_name" => "infinite",
			 "value" => array(
			 	esc_html__('No', 'teampress') => '',
			 	esc_html__('Yes', 'teampress') => 'yes',
			 ),
			 "description" => esc_html__("Infinite loop sliding ( go to first item when end loop)", "teampress"),
		  ),
	   )
	));
	}
}