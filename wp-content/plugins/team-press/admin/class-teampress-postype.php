<?php
include 'inc/metadata-functions.php';
class EX_TeamPress_Posttype {
	public function __construct()
    {
        add_action( 'init', array( &$this, 'register_post_type' ) );
		add_action( 'init', array( &$this, 'register_category_taxonomies' ) );
		add_action( 'init', array( &$this, 'register_extp_location_taxonomies' ) );
		add_action( 'cmb2_admin_init', array( $this,'register_taxonomy_category_metabox') );
		add_action( 'cmb2_admin_init', array( $this,'register_taxonomy_location_metabox') );
		add_filter( 'manage_edit-extp_cat_columns', array( $this,'_edit_columns_extp_cat'));
		add_action( 'manage_extp_cat_custom_column', array( $this,'_custom_columns_content_extp_cat'),10,3);
		add_filter( 'manage_edit-extp_loc_columns', array( $this,'_edit_columns_extp_loc'));
		add_action( 'manage_extp_loc_custom_column', array( $this,'_custom_columns_content_extp_loc'),10,3);
    }

	function register_post_type(){
		$labels = array(
			'name'               => esc_html__('Team','teampress'),
			'singular_name'      => esc_html__('Team','teampress'),
			'add_new'            => esc_html__('Add New Member','teampress'),
			'add_new_item'       => esc_html__('Add New Member','teampress'),
			'edit_item'          => esc_html__('Edit Member','teampress'),
			'new_item'           => esc_html__('New Member','teampress'),
			'all_items'          => esc_html__('Members','teampress'),
			'view_item'          => esc_html__('View Member','teampress'),
			'search_items'       => esc_html__('Search Member','teampress'),
			'not_found'          => esc_html__('No Member found','teampress'),
			'not_found_in_trash' => esc_html__('No Member found in Trash','teampress'),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__('Team','teampress')
		);
		
		$extp_single_slug = extp_get_option('extp_single_slug');
		if($extp_single_slug==''){
			$extp_single_slug = 'member';
		}
		$rewrite =  array( 'slug' => untrailingslashit( $extp_single_slug ), 'with_front' => false, 'feeds' => true );
		
		$args = array(  
			'labels' => $labels,  
			'menu_position' => 8, 
			'supports' => array('title','editor','thumbnail', 'excerpt','custom-fields'),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			//'show_in_menu'       => 'edit.php?post_type=product',
			'menu_icon' =>  'dashicons-groups',
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'rewrite' => $rewrite,
		);  
		register_post_type('ex_team',$args);  
	}
	function register_category_taxonomies(){
		$labels = array(
			'name'              => esc_html__( 'Category', 'teampress' ),
			'singular_name'     => esc_html__( 'Category', 'teampress' ),
			'search_items'      => esc_html__( 'Search','teampress' ),
			'all_items'         => esc_html__( 'All category','teampress' ),
			'parent_item'       => esc_html__( 'Parent category' ,'teampress'),
			'parent_item_colon' => esc_html__( 'Parent category:','teampress' ),
			'edit_item'         => esc_html__( 'Edit category' ,'teampress'),
			'update_item'       => esc_html__( 'Update category','teampress' ),
			'add_new_item'      => esc_html__( 'Add New category' ,'teampress'),
			'menu_name'         => esc_html__( 'Categories','teampress' ),
		);			
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'timeline-category' ),
		);
		register_taxonomy('extp_cat', 'ex_team', $args);
	}
	function register_taxonomy_category_metabox() {
		$prefix = 'extp_cat_';
		/**
		 * Metabox to add fields to categories and tags
		 */
		$cmb_term = new_cmb2_box( array(
			'id'               => $prefix . 'data',
			'title'            => esc_html__( 'Category Metabox', 'teampress' ), // Doesn't output for term boxes
			'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
			'taxonomies'       => array( 'extp_cat'), // Tells CMB2 which taxonomies should have these fields
			'new_term_section' => true, // Will display in the "Add New Category" section
		) );
		$cmb_term->add_field( array(
			'name' => esc_html__( 'Order Category', 'teampress' ),
			'id'   => $prefix .'order',
			'type' => 'text',
				'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			'sanitization_cb' => 'absint',
		        'escape_cb'       => 'absint',
		) );
	}
	function register_extp_location_taxonomies(){
		$labels = array(
			'name'              => esc_html__( 'Location', 'teampress' ),
			'singular_name'     => esc_html__( 'Location', 'teampress' ),
			'search_items'      => esc_html__( 'Search','teampress' ),
			'all_items'         => esc_html__( 'All location','teampress' ),
			'parent_item'       => esc_html__( 'Parent location' ,'teampress'),
			'parent_item_colon' => esc_html__( 'Parent location:','teampress' ),
			'edit_item'         => esc_html__( 'Edit location' ,'teampress'),
			'update_item'       => esc_html__( 'Update location','teampress' ),
			'add_new_item'      => esc_html__( 'Add New location' ,'teampress'),
			'menu_name'         => esc_html__( 'Locations','teampress' ),
		);			
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'extp-location' ),
		);
		register_taxonomy('extp_loc', 'ex_team', $args);
	}
	function register_taxonomy_location_metabox() {
		$prefix = 'extp_loc_';
		/**
		 * Metabox to add fields to categories and tags
		 */
		$cmb_term = new_cmb2_box( array(
			'id'               => $prefix . 'data',
			'title'            => esc_html__( 'Category Metabox', 'teampress' ), // Doesn't output for term boxes
			'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
			'taxonomies'       => array( 'extp_loc'), // Tells CMB2 which taxonomies should have these fields
			'new_term_section' => true, // Will display in the "Add New Category" section
		) );
		$cmb_term->add_field( array(
			'name' => esc_html__( 'Order Location', 'teampress' ),
			'id'   => $prefix .'order',
			'type' => 'text',
				'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			'sanitization_cb' => 'absint',
		        'escape_cb'       => 'absint',
		) );
	}
	function _edit_columns_extp_cat($columns){
		$columns['_order'] = esc_html__( 'Order Category' , 'teampress' );	
		return $columns;
	}
	function _custom_columns_content_extp_cat( $content,$column_name,$term_id) {
		$term= get_term($term_id, 'extp_cat');
		switch ( $column_name ) {
			case '_order':
				$term_order = get_term_meta($term_id, 'extp_cat_order', true);
				echo '<input type="number" style="max-width:60px" data-id="' . $term_id . '" name="extp_sort_category" value="'.esc_attr($term_order).'">';
				break;	
		}
	}
	function _edit_columns_extp_loc($columns){
		$columns['_order'] = esc_html__( 'Order Location' , 'teampress' );	
		return $columns;
	}
	function _custom_columns_content_extp_loc( $content,$column_name,$term_id) {
		$term= get_term($term_id, 'extp_loc');
		switch ( $column_name ) {
			case '_order':
				$term_order = get_term_meta($term_id, 'extp_loc_order', true);
				echo '<input type="number" style="max-width:60px" data-id="' . $term_id . '" name="extp_sort_location" value="'.esc_attr($term_order).'">';
				break;	
		}
	}	
}
$EX_TeamPress_Posttype = new EX_TeamPress_Posttype();