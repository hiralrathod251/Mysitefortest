<?php
class EXTP_SC_Builder {
	public function __construct(){
        add_action( 'init', array( &$this, 'register_post_type' ) );
		add_action( 'cmb2_admin_init', array(&$this,'register_metabox') );
		add_action( 'save_post', array($this,'save_shortcode'),1 );
		add_shortcode( 'extpsc', array($this,'run_extpsc') );
    }
	function run_extpsc($atts, $content){
		$id = isset($atts['id']) ? $atts['id'] : '';
		$sc = get_post_meta( $id, '_tpsc', true );
		if($id=='' || $sc==''){ return;}
		return do_shortcode($sc);
	}
	function save_shortcode($post_id){
		if('team_scbd' != get_post_type()){ return;}
		if(isset($_POST['sc_type'])){
			$style = isset($_POST['style']) ? $_POST['style'] : 1;
			$column = isset($_POST['column']) ? $_POST['column'] : 3;
			$count = isset($_POST['count']) && $_POST['count'] !=''? $_POST['count'] : '9';
			$posts_per_page = isset($_POST['posts_per_page']) ? $_POST['posts_per_page'] : '';
			$slidesshow = isset($_POST['slidesshow']) ? $_POST['slidesshow'] : '';
			$fullcontent_in = isset($_POST['fullcontent_in']) ? $_POST['fullcontent_in'] : '';
			$ids = isset($_POST['ids']) ? $_POST['ids'] : '';
			$cat = isset($_POST['cat']) ? $_POST['cat'] : '';
			$order_cat   = isset($_POST['order_cat']) ? $_POST['order_cat'] : '';
			$location = isset($_POST['location']) ? $_POST['location'] : '';
			$order_location   = isset($_POST['order_location']) ? $_POST['order_location'] : '';
			$order = isset($_POST['order']) ? $_POST['order'] : '';
			$orderby = isset($_POST['orderby']) ? $_POST['orderby'] : '';
			$meta_key = isset($_POST['meta_key']) ? $_POST['meta_key'] : '';
			$meta_value = isset($_POST['meta_value']) ? $_POST['meta_value'] : '';
			$number_excerpt = isset($_POST['number_excerpt']) ? $_POST['number_excerpt'] : '';
			$page_navi = isset($_POST['page_navi']) ? $_POST['page_navi'] : '';
			$alphab_filter = isset($_POST['alphab_filter']) ? $_POST['alphab_filter'] : '';
			$search_box = isset($_POST['search_box']) ? $_POST['search_box'] : '';
			$category_box = isset($_POST['category_box']) ? $_POST['category_box'] : '';
			$category_style = isset($_POST['category_style']) ? $_POST['category_style'] : '';
			$active_filter = isset($_POST['active_filter']) ? $_POST['active_filter'] : '';
			$location_box   = isset($_POST['location_box']) ? $_POST['location_box'] : 'hide';
			$location_style   = isset($_POST['location_style']) ? $_POST['location_style'] : '';
			$masonry = isset($_POST['masonry']) ? $_POST['masonry'] : '';
			$live_sort = isset($_POST['live_sort']) ? $_POST['live_sort'] : '';
			$autoplay = isset($_POST['autoplay']) ? $_POST['autoplay'] : '';
			$autoplayspeed = isset($_POST['autoplayspeed']) ? $_POST['autoplayspeed'] : '';
			$loading_effect = isset($_POST['loading_effect']) ? $_POST['loading_effect'] : '';
			$infinite = isset($_POST['infinite']) ? $_POST['infinite'] : '';
			$slidestoscroll = isset($_POST['slidestoscroll']) ? $_POST['slidestoscroll'] : '';

			if($_POST['sc_type'] == 'grid'){
				
				$sc = '[ex_tpgrid style="'.$style.'" column="'.$column.'" count="'.$count.'" posts_per_page="'.$posts_per_page.'" fullcontent_in="'.$fullcontent_in.'" ids="'.$ids.'" cat="'.$cat.'" location="'.$location.'" order="'.$order.'" orderby="'.$orderby.'" meta_key="'.$meta_key.'" meta_value="'.$meta_value.'" number_excerpt="'.$number_excerpt.'" alphab_filter="'.$alphab_filter.'" search_box="'.$search_box.'" category_box="'.$category_box.'" category_style="'.$category_style.'" active_filter="'.esc_attr($active_filter).'" order_cat="'.$order_cat.'" location_box="'.$location_box.'" location_style="'.$location_style.'" order_location="'.$order_location.'" masonry="'.$masonry.'" page_navi="'.$page_navi.'"]';
				
			}elseif($_POST['sc_type'] == 'list'){
				
				$sc = '[ex_tplist style="'.$style.'" count="'.$count.'" posts_per_page="'.$posts_per_page.'" fullcontent_in="'.$fullcontent_in.'" ids="'.$ids.'" cat="'.$cat.'" location="'.$location.'" order="'.$order.'" orderby="'.$orderby.'" meta_key="'.$meta_key.'" meta_value="'.$meta_value.'" number_excerpt="'.$number_excerpt.'" alphab_filter="'.$alphab_filter.'" search_box="'.$search_box.'" category_box="'.$category_box.'"  category_style="'.$category_style.'" active_filter="'.esc_attr($active_filter).'" order_cat="'.$order_cat.'" location_box="'.$location_box.'" location_style="'.$location_style.'" order_location="'.$order_location.'" page_navi="'.$page_navi.'"]';
				
			}elseif($_POST['sc_type'] == 'table'){
				
				$sc = '[ex_tptable style="'.$style.'" count="'.$count.'" posts_per_page="'.$posts_per_page.'" fullcontent_in="'.$fullcontent_in.'" ids="'.$ids.'" cat="'.$cat.'" location="'.$location.'" order="'.$order.'" orderby="'.$orderby.'" meta_key="'.$meta_key.'" meta_value="'.$meta_value.'" number_excerpt="'.$number_excerpt.'" alphab_filter="'.$alphab_filter.'" search_box="'.$search_box.'" category_box="'.$category_box.'" category_style="'.$category_style.'" active_filter="'.esc_attr($active_filter).'" order_cat="'.$order_cat.'" location_box="'.$location_box.'" location_style="'.$location_style.'" order_location="'.$order_location.'" live_sort="'.$live_sort.'"  page_navi="'.$page_navi.'"]';
				
			}else{
				
				$sc = '[ex_tpcarousel style="'.$style.'" count="'.$count.'" slidesshow="'.$slidesshow.'" fullcontent_in="'.$fullcontent_in.'" ids="'.$ids.'" cat="'.$cat.'" location="'.$location.'" order="'.$order.'" orderby="'.$orderby.'" meta_key="'.$meta_key.'" meta_value="'.$meta_value.'" number_excerpt="'.$number_excerpt.'"  autoplay="'.$autoplay.'"  autoplayspeed="'.$autoplayspeed.'" loading_effect="'.$loading_effect.'" infinite="'.$infinite.'" slidestoscroll="'.$slidestoscroll.'"]';
				
			}
			if($sc!=''){
				update_post_meta( $post_id, '_tpsc', $sc );
			}
			update_post_meta( $post_id, '_shortcode', '[extpsc id="'.$post_id.'"]' );
		}
	}
	function register_post_type(){
		$labels = array(
			'name'               => esc_html__('Shortcodes','teampress'),
			'singular_name'      => esc_html__('Shortcodes','teampress'),
			'add_new'            => esc_html__('Add New Shortcodes','teampress'),
			'add_new_item'       => esc_html__('Add New Shortcodes','teampress'),
			'edit_item'          => esc_html__('Edit Shortcodes','teampress'),
			'new_item'           => esc_html__('New Shortcode','teampress'),
			'all_items'          => esc_html__('Shortcodes builder','teampress'),
			'view_item'          => esc_html__('View Shortcodes','teampress'),
			'search_items'       => esc_html__('Search Shortcodes','teampress'),
			'not_found'          => esc_html__('No Shortcode found','teampress'),
			'not_found_in_trash' => esc_html__('No Shortcode found in Trash','teampress'),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__('Shortcodes','teampress')
		);
		$rewrite = false;
		$args = array(  
			'labels' => $labels,  
			'menu_position' => 8, 
			'supports' => array('title','custom-fields'),
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=ex_team',
			'menu_icon' =>  'dashicons-editor-ul',
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'rewrite' => $rewrite,
		);  
		register_post_type('team_scbd',$args);  
	}
	
	function register_metabox() {
		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$layout = new_cmb2_box( array(
			'id'            => 'sc_shortcode',
			'title'         => esc_html__( 'Shortcode type', 'teampress' ),
			'object_types'  => array( 'team_scbd' ), // Post type
		) );
	
		$layout->add_field( array(
			'name'             => esc_html__( 'Type', 'teampress' ),
			'desc'             => esc_html__( 'Select type of shortcode', 'teampress' ),
			'id'               => 'sc_type',
			'type'             => 'select',
			'show_option_none' => false,
			'default'          => 'grid',
			'options'          => array(
				'grid' => esc_html__( 'Grid', 'teampress' ),
				'table'   => esc_html__( 'Table', 'teampress' ),
				'list'   => esc_html__( 'List', 'teampress' ),
				'carousel'     => esc_html__( 'Carousel', 'teampress' ),
			),
		) );
		if(isset($_GET['post']) && is_numeric($_GET['post'])){
			$layout->add_field( array(
				'name'       => esc_html__( 'Shortcode', 'teampress' ),
				'desc'       => esc_html__( 'Copy this shortcode and paste it into your post, page, or text widget content:', 'teampress' ),
				'id'         => '_shortcode',
				'type'       => 'text',
				'classes'             => '',
				'attributes'  => array(
					'readonly' => 'readonly',
				),
			) );
		}
		$sc_option = new_cmb2_box( array(
			'id'            => 'sc_option',
			'title'         => esc_html__( 'Option', 'teampress' ),
			'object_types'  => array( 'team_scbd' ),
		) );
		
		$sc_option->add_field( array(
			'name'             => esc_html__( 'Style', 'teampress' ),
			'desc'             => esc_html__( 'Select style of shortcode', 'teampress' ),
			'id'               => 'style',
			'type'             => 'select',
			'classes'             => 'column-2',
			'show_option_none' => false,
			'default'          => '1',
			'options'          => array(
				'1' => esc_html__('1', 'teampress'),
				'2' => esc_html__('2', 'teampress'),
				'3' => esc_html__('3', 'teampress'),
				'4' => esc_html__('4', 'teampress'),
				'5' => esc_html__('5', 'teampress'),
				'6' => esc_html__('6', 'teampress'),
				'7' => esc_html__('7', 'teampress'),
				'8' => esc_html__('8', 'teampress'),
				'9' => esc_html__('9', 'teampress'),
				'10' => esc_html__('10', 'teampress'),
				'11' => esc_html__('11', 'teampress') ,
				'12' => esc_html__('12', 'teampress'),
				'13' => esc_html__('13', 'teampress'),
				'14' => esc_html__('14', 'teampress'),
				'15' => esc_html__('15', 'teampress'),
				'16' => esc_html__('16', 'teampress'),
				'17' => esc_html__('17', 'teampress'),
				'18' => esc_html__('18', 'teampress'),
				'19' => esc_html__('19', 'teampress'),
				'20' => esc_html__('20', 'teampress'),
				'img-1' => esc_html__('Image hover 1', 'teampress'),
				'img-2' => esc_html__('Image hover 2', 'teampress'),
				'img-3' => esc_html__('Image hover 3', 'teampress'),
				'img-4' => esc_html__('Image hover 4', 'teampress'),
				'img-5' => esc_html__('Image hover 5', 'teampress'),
				'img-6' => esc_html__('Image hover 6', 'teampress'),
				'img-7' => esc_html__('Image hover 7', 'teampress'),
				'img-8' => esc_html__('Image hover 8', 'teampress'),
				'img-9' => esc_html__('Image hover 9', 'teampress'),
				'img-10'=> esc_html__('Image hover 10', 'teampress'),
			),
		) );
		
		$sc_option->add_field( array(
			'name'             => esc_html__( 'Columns', 'teampress' ),
			'desc'             => esc_html__( 'Select Columns of shortcode', 'teampress' ),
			'id'               => 'column',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel hide-intable hide-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '3',
			'options'          => array(
				'2' => esc_html__( '2 columns', 'teampress' ),
				'3'   => esc_html__( '3 columns', 'teampress' ),
				'4'   => esc_html__( '4 columns', 'teampress' ),
				'5'     => esc_html__( '5 columns', 'teampress' ),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Count', 'teampress' ),
			'desc'       => esc_html__( 'Number of posts', 'teampress' ),
			'id'         => 'count',
			'type'       => 'text',
			'classes'             => 'column-2',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Posts per page', 'teampress' ),
			'desc'       => esc_html__( 'Number items per page', 'teampress' ),
			'id'         => 'posts_per_page',
			'type'       => 'text',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Number items visible', 'teampress' ),
			'desc'       => esc_html__( 'Enter number', 'teampress' ),
			'id'         => 'slidesshow',
			'type'       => 'text',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Number item to scroll', 'teampress' ),
			'desc'       => esc_html__( 'Enter number', 'teampress' ),
			'id'         => 'slidestoscroll',
			'type'       => 'text',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Full content in', 'teampress' ),
			'desc'       => esc_html__( 'Show full infomartion member in', 'teampress' ),
			'id'         => 'fullcontent_in',
			'type'             => 'select',
			'classes'             => 'column-2',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__( 'None', 'teampress' ),
				'lightbox'   => esc_html__( 'Lightbox', 'teampress' ),
				'collapse'   => esc_html__( 'Collapse', 'teampress' ),
				'modal'     => esc_html__( 'Modal', 'teampress' ),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'IDs', 'teampress' ),
			'desc'       => esc_html__( 'Specify post IDs to retrieve', 'teampress' ),
			'id'         => 'ids',
			'type'       => 'text',
			'classes'             => 'column-2',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Category', 'teampress' ),
			'desc'       => esc_html__( 'List of cat ID (or slug), separated by a comma', 'teampress' ),
			'id'         => 'cat',
			'type'       => 'text',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Location', 'teampress' ),
			'desc'       => esc_html__( 'List of location ID (or slug), separated by a comma', 'teampress' ),
			'id'         => 'location',
			'type'       => 'text',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Order', 'teampress' ),
			'desc'       => '',
			'id'         => 'order',
			'type'             => 'select',
			'classes'             => 'column-2',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'DESC' => esc_html__('DESC', 'teampress'),
				'ASC'   => esc_html__('ASC', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Order by', 'teampress' ),
			'desc'       => '',
			'id'         => 'orderby',
			'type'             => 'select',
			'classes'             => 'column-2',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'date' => esc_html__('Date', 'teampress'),
				'ID'   => esc_html__('ID', 'teampress'),
				'author' => esc_html__('Author', 'teampress'),
				'title'   => esc_html__('Title', 'teampress'),
				'name' => esc_html__('Name', 'teampress'),
				'modified'   => esc_html__('Modified', 'teampress'),
				'parent' => esc_html__('Parent', 'teampress'),
				'rand'   => esc_html__('Rand', 'teampress'),
				'menu_order' => esc_html__('Menu order', 'teampress'),
				'meta_value'   => esc_html__('Meta value', 'teampress'),
				'meta_value_num' => esc_html__('Meta value num', 'teampress'),
				'post__in'   => esc_html__('Post__in', 'teampress'),
				'None'   => esc_html__('None', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Meta key', 'teampress' ),
			'desc'       => esc_html__( 'Enter meta key to query', 'teampress' ),
			'id'         => 'meta_key',
			'type'       => 'text',
			'classes'             => 'column-2',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Meta value', 'teampress' ),
			'desc'       => esc_html__( 'Enter meta value to query', 'teampress' ),
			'id'         => 'meta_value',
			'type'       => 'text',
			'classes'             => 'column-2',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Number of Excerpt', 'teampress' ),
			'desc'       => esc_html__( 'Enter number', 'teampress' ),
			'id'         => 'number_excerpt',
			'type'       => 'text',
			'classes'             => 'column-2',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Page navi', 'teampress' ),
			'desc'       => esc_html__( 'Select type of page navigation', 'teampress' ),
			'id'         => 'page_navi',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('Number', 'teampress'),
				'loadmore'   => esc_html__('Load more', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Search box', 'teampress' ),
			'desc'       => esc_html__( 'Show search box', 'teampress' ),
			'id'         => 'search_box',
			'type'             => 'select',
			'classes'             => 'column-3 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'hide' => esc_html__('Hide', 'teampress'),
				'show'   => esc_html__('Show', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Category box', 'teampress' ),
			'desc'       => esc_html__( 'Show Category filter', 'teampress' ),
			'id'         => 'category_box',
			'type'             => 'select',
			'classes'             => 'column-3 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'show'   => esc_html__('Show', 'teampress'),
				'hide' => esc_html__('Hide', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Category Style', 'teampress' ),
			'desc'       => esc_html__( 'Choice Category Style', 'teampress' ),
			'id'         => 'category_style',
			'type'             => 'select',
			'classes'             => 'column-3 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('Select box', 'teampress'),
				'inline'   => esc_html__('Inline', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Active filter', 'teampress' ),
			'desc'       => esc_html__( 'Enter slug of category to active instead of active "All"', 'teampress' ),
			'id'         => 'active_filter',
			'type'       => 'text',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Order Category Filter', 'teampress' ),
			'desc'       => esc_html__( 'Order Category with custom order', 'teampress' ),
			'id'         => 'order_cat',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'yes'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Location box', 'teampress' ),
			'desc'       => esc_html__( 'Show Location box', 'teampress' ),
			'id'         => 'location_box',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'hide' => esc_html__('Hide', 'teampress'),
				'show'   => esc_html__('Show', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Location Style', 'teampress' ),
			'desc'       => esc_html__( 'Choice Location Style', 'teampress' ),
			'id'         => 'location_style',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('Select box', 'teampress'),
				'inline'   => esc_html__('Inline', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Order Location Filter', 'teampress' ),
			'desc'       => esc_html__( 'Order Location with custom order', 'teampress' ),
			'id'         => 'order_location',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'yes'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Alphabetical filter', 'teampress' ),
			'desc'       => esc_html__( 'Show Alphabetical filter', 'teampress' ),
			'id'         => 'alphab_filter',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel show-intable show-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'yes'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Masonry layout', 'teampress' ),
			'desc'       => esc_html__( 'Enable Masonry layout', 'teampress' ),
			'id'         => 'masonry',
			'type'             => 'select',
			'classes'             => 'column-2 hide-incarousel hide-intable hide-inlist show-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'yes'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Live Sort', 'teampress' ),
			'desc'       => esc_html__( 'Enable Live Sort', 'teampress' ),
			'id'         => 'live_sort',
			'type'             => 'select',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'1'   => esc_html__('Yes', 'teampress'),
			),
			'classes'             => 'column-2 hide-incarousel show-intable hide-inlist hide-ingrid',
		) );
		
		/*$sc_option->add_field( array(
			'name'       => esc_html__( 'Slide to start on', 'teampress' ),
			'desc'       => esc_html__( 'Enter number, Default:0', 'teampress' ),
			'id'         => 'start_on',
			'type'             => 'text',
		) );*/
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Autoplay', 'teampress' ),
			'desc'       => esc_html__( 'Enable Autoplay', 'teampress' ),
			'id'         => 'autoplay',
			'type'             => 'select',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'1'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Autoplay Speed', 'teampress' ),
			'desc'       => esc_html__( 'Autoplay Speed in milliseconds. Default:3000', 'teampress' ),
			'id'         => 'autoplayspeed',
			'type'             => 'text',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Loading effect', 'teampress' ),
			'desc'       => esc_html__( 'Enable Loading effect', 'teampress' ),
			'id'         => 'loading_effect',
			'type'             => 'select',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'1'   => esc_html__('Yes', 'teampress'),
			),
		) );
		$sc_option->add_field( array(
			'name'       => esc_html__( 'Infinite', 'teampress' ),
			'desc'       => esc_html__( 'Infinite loop sliding ( go to first item when end loop)', 'teampress' ),
			'id'         => 'infinite',
			'type'             => 'select',
			'classes'             => 'column-2 show-incarousel hide-intable hide-inlist hide-ingrid',
			'show_option_none' => false,
			'default'          => '',
			'options'          => array(
				'' => esc_html__('No', 'teampress'),
				'yes'   => esc_html__('Yes', 'teampress'),
			),
		) );
	
	}

	
}
$EXTP_SC_Builder = new EXTP_SC_Builder();