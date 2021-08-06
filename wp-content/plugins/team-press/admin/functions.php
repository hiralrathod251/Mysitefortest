<?php
include 'class-teampress-postype.php';
include 'shortcode-builder.php';
add_action( 'admin_enqueue_scripts', 'extp_admin_scripts' );
function extp_admin_scripts(){
	$js_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'jquery', 'extp_ajax', $js_params  );
	wp_enqueue_style('extp-admin_style', TEAMPRESS_PATH . 'admin/css/style.css','','1.4.5');
	wp_enqueue_script('extp-admin-js', TEAMPRESS_PATH . 'admin/js/admin.js', array( 'jquery' ),'1.4' );
}

add_filter( 'manage_ex_team_posts_columns', 'extp_edit_columns',99 );
function extp_edit_columns( $columns ) {
	global $wpdb;
	unset($columns['date']);
	$columns['extp_id'] = esc_html__( 'ID' , 'teampress' );
	$columns['extp_position'] = esc_html__( 'Position' , 'teampress' );
	$columns['extp_order'] = esc_html__( 'Order' , 'teampress' );
	$columns['extp_color'] = esc_html__( 'Color' , 'teampress' );
	$columns['date'] = esc_html__( 'Publish date' , 'teampress' );		
	return $columns;
}
add_action( 'manage_ex_team_posts_custom_column', 'ex_team_custom_columns',12);
function ex_team_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'extp_id':
			$extp_id = $post->ID;
			echo '<span class="extp_id">'.$extp_id.'</span>';
			break;
		case 'extp_position':
			$extp_position = get_post_meta($post->ID, 'extp_position', true);
			echo '<input type="text" style="max-width:100%" data-id="' . $post->ID . '" name="extp_position" value="'.esc_attr($extp_position).'">';
			break;	
		case 'extp_order':
			$extp_order = get_post_meta($post->ID, 'extp_order', true);
			echo '<input type="number" style="max-width:60px" data-id="' . $post->ID . '" name="extp_sort" value="'.esc_attr($extp_order).'">';
			break;
		case 'extp_color':
			$extp_color = get_post_meta($post->ID, 'extp_color', true);
			echo '<span style=" background-color:'.esc_attr($extp_color).'; width: 15px;
    height: 15px; border-radius: 50%; display: inline-block;"></span>';
			break;	
	}
}


add_filter( 'manage_team_scbd_posts_columns', 'extp_edit_scbd_columns',99 );
function extp_edit_scbd_columns( $columns ) {
	global $wpdb;
	unset($columns['date']);
	$columns['layout'] = esc_html__( 'Type' , 'teampress' );
	$columns['shortcode'] = esc_html__( 'Shortcode' , 'teampress' );
	$columns['date'] = esc_html__( 'Publish date' , 'teampress' );		
	return $columns;
}
add_action( 'manage_team_scbd_posts_custom_column', 'extp_scbd_custom_columns',12);
function extp_scbd_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'layout':
			$sc_type = get_post_meta($post->ID, 'sc_type', true);
			$extp_id = $post->ID;
			echo '<span class="layout">'.$sc_type.'</span>';
			break;
		case 'shortcode':
			$_shortcode = get_post_meta($post->ID, '_shortcode', true);
			echo '<input type="text" style="max-width:100%" readonly name="_shortcode" value="'.esc_attr($_shortcode).'">';
			break;	
	}
}

add_action( 'wp_ajax_extp_change_sort_mb', 'extp_change_sort' );
function extp_change_sort(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if(isset($post_id) && $post_id != 0)
	{
		update_post_meta($post_id, 'extp_order', esc_attr(str_replace(' ', '', $value)));
	}
	die;
}
add_action('wp_ajax_extp_change_position', 'extp_change_position' );
function extp_change_position(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if(isset($post_id) && $post_id != 0)
	{
		update_post_meta($post_id, 'extp_position', esc_attr($value));
	}
	die;
}
function extp_id_taxonomy_columns( $columns ){
	$columns['cat_id'] = esc_html__('ID','teampress');

	return $columns;
}
add_filter('manage_edit-extp_cat_columns' , 'extp_id_taxonomy_columns');
function extp_taxonomy_columns_content( $content, $column_name, $term_id ){
    if ( 'cat_id' == $column_name ) {
        $content = $term_id;
    }
	return $content;
}
add_filter( 'manage_extp_cat_custom_column', 'extp_taxonomy_columns_content', 10, 3 );

add_action('wp_ajax_extp_change_sort_category', 'extp_change_sort_category' );
function extp_change_sort_category(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if ($value == '') {
		$value = 0;
	}
	if(isset($post_id) && $post_id != 0)
	{
		update_term_meta($post_id, 'extp_cat_order', esc_attr($value));
	}
	die;
}
add_action('wp_ajax_extp_change_sort_location', 'extp_change_sort_location' );
function extp_change_sort_location(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if ($value == '') {
		$value = 0;
	}
	if(isset($post_id) && $post_id != 0)
	{
		update_term_meta($post_id, 'extp_loc_order', esc_attr($value));
	}
	die;
}
//
add_action( 'init', 'extp_update_option_settings' );
if(!function_exists('extp_update_option_settings')){
	function extp_update_option_settings() {
		if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_GET['page']) && $_GET['page']=='extp_verify_options' && isset($_GET['delete_license']) && $_GET['delete_license']=='yes' ){
			$_name = extp_get_option('extp_evt_name','extp_verify_options');
			$_pcode = extp_get_option('extp_evt_pcode','extp_verify_options');
			$site = get_site_url();
			$url = 'https://exthemes.net/verify-purchase-code/';
			$data = array('buyer' => $_name, 'code' => $_pcode, 'item_id' =>'22952433', 'site' => $site, 'delete'=>'yes');
			$options = array(
			        'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'POST',
			        'content' => http_build_query($data),
			    )
			);

			$context  = stream_context_create($options);
			$res = file_get_contents($url, false, $context);
			delete_option( 'extp_verify_options');
			delete_option( 'extp_ckforupdate');
			delete_option( 'extp_li_mes');
			update_option( 'extp_license','');
			wp_redirect( ( admin_url( '?page=extp_verify_options' ) ) );
			die;
		}
		if(is_user_logged_in() && current_user_can( 'manage_options' )){
			if(isset($_GET['exot_reset']) && $_GET['exot_reset']=='yes' && isset($_GET['page']) && strpos($_GET['page'], 'extp') !== false ){
				update_option( $_GET['page'], '' );
			}
		}
	}
}
if(!function_exists('extp_check_purchase_code') && is_admin()){
	function extp_check_purchase_code() {
		$class = 'notice notice-error';
		$message =  'You are using an unregistered version of TeamPress, please <a href="'.esc_url(admin_url('admin.php?page=extp_verify_options')).'">active your license</a> of TeamPress to receive support and update';
	
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
	function extp_invalid_pr_code() {
		$class = 'notice notice-error';
		$get_mes = get_option( 'extp_li_mes');
		$get_mes = $get_mes!='' ? explode('|', $get_mes) : '';
		if(is_array($get_mes) && !empty($get_mes)){
			$message =  'Invalid purchase code for TeamPress plugin, This license has registered for: '. $get_mes[0] .' - '. $get_mes[1] ;
		}else{
			$message =  'Invalid purchase code for TeamPress plugin, please find check how to find your purchase code <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">here </a>';
		}
	
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
	$scd_ck = get_option( 'extp_ckforupdate');
	$crt = strtotime('now');
	$_name = extp_get_option('extp_evt_name','extp_verify_options');
	$_pcode = extp_get_option('extp_evt_pcode','extp_verify_options');
	if ($_name =='' || $_pcode=='' ) {
		add_action( 'admin_notices', 'extp_check_purchase_code' );
	}
	if($scd_ck=='' || $crt > $scd_ck ){
		$check_version = '';
		global $pagenow;
		if((isset($_GET['page']) && ($_GET['page'] =='extp_options' || $_GET['page'] =='extp_verify_options' )) || (isset($_GET['post_type']) && $_GET['post_type']=='ex_team') || $pagenow == 'plugins.php' ){
			
			$site = get_site_url();
			$url = 'https://exthemes.net/verify-purchase-code/';
			$myvars = 'buyer=' . $_name . '&code=' . $_pcode. '&site='.$site.'&item_id=22952433';
			$res = '';
			if(function_exists('stream_context_create')){
				$data = array('buyer' => $_name, 'code' => $_pcode, 'item_id' =>'22952433', 'site' => $site);
				$options = array(
				        'http' => array(
				        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				        'method'  => 'POST',
				        'content' => http_build_query($data),
				    )
				);

				$context  = stream_context_create($options);
				$res = file_get_contents($url, false, $context);
			}
			if($res!=''){
				$res = json_decode($res);
			}else{
				$ch = curl_init( $url );
				curl_setopt( $ch, CURLOPT_POST, 1);
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt( $ch, CURLOPT_HEADER, 0);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
				curl_setopt($ch, CURLOPT_TIMEOUT, 2);
				$res=json_decode(curl_exec($ch),true);
				curl_close($ch);
			}
			$check_version = isset($res[5]) ? $res[5] : '';
			update_option( 'extp_version', $check_version );
			//print_r( $res) ;exit;
			if(isset($res[0]) && $res[0] == 'error' && $_name!='' && $_pcode!=''){
				update_option( 'extp_ckforupdate', strtotime('+3 day') );
				if(isset($res[2]) && isset($res[2][0]) && $res[2][0] == 'invalid'){
					update_option( 'extp_li_mes', $res[2][1][0] );
				}
				update_option( 'extp_license', 'invalid');
			}else if(isset($res[0]) && $res[0] == 'success'){
				update_option( 'extp_ckforupdate', strtotime('+10 day') );
				delete_option( 'extp_li_mes');
			}else{
				update_option( 'extp_ckforupdate', strtotime('+5 day') );
			}
		}
	}
	if(get_option('extp_license') =='invalid'){
		add_action( 'admin_notices', 'extp_invalid_pr_code' );
	}

	if( ! function_exists('get_plugin_data') ){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    if (file_exists( WP_PLUGIN_DIR.'/teampress/teampress.php' ) ) {
	    $plugin_data = get_plugin_data( WP_PLUGIN_DIR  . '/teampress/teampress.php' );
	}else{
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR  . '/team-press/teampress.php' );
	}
    $plugin_version = str_replace('.', '',$plugin_data['Version']);
    $check_version = get_option( 'exwptl_version');
    $check_version = $check_version !='' ? str_replace('.', '',$check_version) : '';
    if(strlen($check_version) > strlen($plugin_version)){
    	$plugin_version = is_numeric($plugin_version) ?  $plugin_version *10 : '';
    }else if(strlen($check_version) < strlen($plugin_version)){
    	$check_version = is_numeric($check_version) ?  $check_version *10 : '';
    }
 	if($check_version!='' && $check_version > $plugin_version){
 		add_filter('wp_get_update_data','extp_up_count_pl',10);
 		function extp_up_count_pl($update_data){
 			$update_data['counts']['plugins'] =  $update_data['counts']['plugins'] + 1;
 			return $update_data;
 		}
 		if (file_exists( WP_PLUGIN_DIR.'/teampress/teampress.php' ) ) {
			add_action( 'after_plugin_row_teampress/teampress.php', 'extp_show_purchase_notice_under_plugin', 10 );
		}else{
			add_action( 'after_plugin_row_teampress/team-press.php', 'extp_show_purchase_notice_under_plugin', 10 );
		}
		function extp_show_purchase_notice_under_plugin(){
			$text = sprintf(
				esc_html__( 'There is a new version of TeamPress available. %1$s View details %2$s and please check how to update plugin %3$s here%4$s.', 'teampress' ),
					'<a href="https://codecanyon.net/item/teampress-team-showcase-plugin/22952433#item-description__changelog" target="_blank">',
					'</a>', 
					'<a href="https://exthemes.net/teampress/doc/#!/install-file" target="_blank">',
					'</a>'
				);
			echo '
			<style>[data-slug="teampress"].active td,[data-slug="teampress"].active th { box-shadow: none;}</style>
			<tr class="plugin-update-tr active">
				<td colspan="4" class="plugin-update">
					<div class="update-message notice inline notice-alt"><p>'.$text.'</p></div>
				</td>
			</tr>';
		}
	}
}