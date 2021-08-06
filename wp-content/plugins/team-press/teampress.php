<?php
/*
Plugin Name: TeamPress
Plugin URI: https://exthemes.net/teampress/
Description: Awesome team showcase wordpress plugin with a lot of great features
Version: 1.4.7
Author: Ex-Themes
Author URI: https://exthemes.net
Text Domain: teampress
License: Envato Split Licence
Domain Path: /languages/
*/
define( 'TEAMPRESS_PATH', plugin_dir_url( __FILE__ ) );
// Make sure we don't expose any info if called directly
if ( !defined('TEAMPRESS_PATH') ){
	die('-1');
}
if(!function_exists('extp_get_plugin_url')){
	function extp_get_plugin_url(){
		return plugin_dir_path(__FILE__);
	}
}
class EX_TeamPress{
	public $template_url;
	public $plugin_path;
	public function __construct(){
		$this->includes();
		add_action( 'wp_enqueue_scripts', array($this, 'frontend_scripts') );
		add_filter( 'template_include', array( $this, 'template_loader' ),99 );
		add_action('wp_enqueue_scripts', array($this, 'frontend_style'),99 );
		add_action('wp_head',array( $this, 'custom_css'),100);
		add_action('plugins_loaded',array( $this, 'load_textdomain'));
		add_action( 'wp_footer', array( $this,'enqueue_customjs'),99 );
    }
	function load_textdomain() {
		$textdomain = 'teampress';
		$locale = '';
		if ( empty( $locale ) ) {
			if ( is_textdomain_loaded( $textdomain ) ) {
				return true;
			} else {
				return load_plugin_textdomain( $textdomain, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
			}
		} else {
			return load_textdomain( $textdomain, plugin_basename( dirname( __FILE__ ) ) . '/' . $textdomain . '-' . $locale . '.mo' );
		}
	}
	function custom_css(){
		echo '<style type="text/css">';
			require extp_get_plugin_url(). 'css/custom.css.php';
		echo '</style>';
	}

	function template_loader($template){
		$find = array('archive-team.php');
		$file = '';			
		if(is_post_type_archive( 'ex_team' ) || is_tax('team_cat') || is_tax('team_tag')){
			$extp_disable_single = extp_get_option('extp_disable_single');
			if($extp_disable_single=='yes'){
				wp_redirect( get_template_part( '404' ) ); exit;
			}
			$file = 'archive-team.php';
			$find[] = $file;
			$find[] = $this->template_url . $file;
			if ( $file ) {
				$template = locate_template( $find );
				if ( ! $template ){
					$file = 'teampress/archive-team.php';
					$find[] = $file;
					$find[] = $this->template_url . $file;
					$template = locate_template( $find );
					if ( ! $template ){
						$template = $this->plugin_path() . '/templates/archive-team.php';
					}
				}
			}
		}
		if(is_singular('ex_team')){
			$extp_disable_single = extp_get_option('extp_disable_single');
			if($extp_disable_single=='yes'){
				wp_redirect( get_template_part( '404' ) ); exit;
			}
			$file = 'single-team.php';
			$find[] = $file;
			$find[] = $this->template_url . $file;
			if ( $file ) {
				$template = locate_template( $find );
				if ( ! $template ){
					$file = 'teampress/single-team.php';
					$find[] = $file;
					$find[] = $this->template_url . $file;
					$template = locate_template( $find );
					if ( ! $template ){
						$template = $this->plugin_path() . '/templates/single-team.php';
					}
				}
			}
		}
		return $template;		
	}
	public function plugin_path() {
		if ( $this->plugin_path ) return $this->plugin_path;
		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	function includes(){
		include_once extp_get_plugin_url().'admin/functions.php';
		include_once extp_get_plugin_url().'inc/functions.php';
	}
	// Load js and css
	function frontend_scripts(){
		$main_font_default='Source Sans Pro';
		$g_fonts = array($main_font_default);
		$extp_font_family = extp_get_option('extp_font_family');
		if($extp_font_family!=''){
			$extp_font_family = extp_get_google_font_name($extp_font_family);
			array_push($g_fonts, $extp_font_family);
		}
		$extp_headingfont_family = extp_get_option('wt_hfont');
		if($extp_headingfont_family!=''){
			$extp_headingfont_family = extp_get_google_font_name($extp_headingfont_family);
			array_push($g_fonts, $extp_headingfont_family);
		}
		$wt_googlefont_js = extp_get_option('extp_disable_ggfont','extp_js_css_file_options');
		if($wt_googlefont_js!='yes'){
			wp_enqueue_style( 'extp-google-fonts', extp_get_google_fonts_url($g_fonts), array(), '1.0.0' );
		}
	}
	function frontend_style(){
		$extp_disable_awefont = extp_get_option('extp_disable_awefont','extp_js_css_file_options');
		if($extp_disable_awefont!='yes'){
			wp_enqueue_style('extp-font-awesome', TEAMPRESS_PATH.'css/font-awesome/css/fontawesome-all.min.css');
		}
		wp_enqueue_style('extp-lightbox', TEAMPRESS_PATH.'css/glightbox.css','1.0');
		wp_register_script( 'extp-lightbox',plugins_url('/js/glightbox.min.js', __FILE__) , array( 'jquery' ),'1.2', true );
		wp_register_script( 'extp-nicescroll',plugins_url('/js/jquery.nicescroll.min.js', __FILE__) , array( 'jquery' ),'1.0', true );
		
		wp_enqueue_script( 'extp-teampress',plugins_url('/js/teampress.min.js', __FILE__) , array( 'jquery' ),'1.4.1' );
		wp_enqueue_style('extp-teampress', TEAMPRESS_PATH.'css/style.css','1.4');
		wp_enqueue_style('extp-teampress-imghover', TEAMPRESS_PATH.'css/imghover-style.css','1.0');
		wp_enqueue_style('extp-teampress-list', TEAMPRESS_PATH.'css/style-list.css','1.0');
		wp_enqueue_style('extp-teampress-tablecss', TEAMPRESS_PATH.'css/style-table.css','1.0');
		wp_register_style('extp-teampress-expand', TEAMPRESS_PATH.'css/collapse.css','1.0');
		wp_register_style('extp-teampress-modal', TEAMPRESS_PATH.'css/modal.css','1.0');
		
		wp_enqueue_style('extp-teampress-expand');
		wp_enqueue_style('extp-lightbox');
		wp_enqueue_style('extp-teampress-lbcustom');
		wp_enqueue_style('extp-teampress-modal');
		wp_enqueue_script( 'extp-lightbox');
		wp_enqueue_script( 'extp-nicescroll');
		if(is_singular('ex_team')){
			wp_enqueue_style('extp-single-member', TEAMPRESS_PATH.'css/single-member.css','1.0');
		}
		$extp_enable_rtl = extp_get_option('extp_enable_rtl');
		if($extp_enable_rtl=='yes' || is_rtl()){
			wp_enqueue_style('extp-rtl', TEAMPRESS_PATH.'css/rtl.css');
		}
	}
	function enqueue_customjs() {
		$extp_custom_js = extp_get_option('extp_custom_js','extp_custom_code_options');
		if($extp_custom_js!=''){
			echo '<script>'.$extp_custom_js.'</script>';
		}
	}
}
$EX_TeamPress = new EX_TeamPress();