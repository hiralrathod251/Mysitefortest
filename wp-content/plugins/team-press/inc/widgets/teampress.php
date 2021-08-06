<?php
class Extp_Widget_TeamPress extends WP_Widget {	
	function __construct() {
    	$widget_ops = array(
			'classname'   => 'extp-teampress-widget', 
			'description' => esc_html__('Display your team from shortcode builder via widget','teampress')
		);
    	parent::__construct('extp-widget', esc_html__('TeamPress','teampress'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args);
		$title 			= empty($instance['title']) ? '' : $instance['title'];
		$title          = apply_filters('widget_title', $title);
		$id_sc 			= empty($instance['id_sc']) ? '' : $instance['id_sc'];
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		echo do_shortcode('[extpsc id="'.$id_sc.'"]');
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['id_sc'] = strip_tags($new_instance['id_sc']);
		return $instance;
	}
	function form( $instance ) {
		if ( (isset($_GET['action'])  && $_GET['action'] === 'edit') || isset($_GET['post_type'])  && $_GET['post_type'] !== '' ){
			return;
		}
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$id_sc = isset($instance['id_sc']) ? esc_attr($instance['id_sc']) : '';
		$html_option = '<option value="0" '.selected( $id_sc, "date",0 ).'>'. esc_html__('Choose a Team','teampress').'</option>';
		$id_query = new WP_Query( 'post_type=team_scbd&posts_per_page=-1' );
		if ( $id_query->have_posts() ) {
			while ( $id_query->have_posts() ) {
				$id_query->the_post();
				$id_array[get_the_ID()] = get_the_title();
				$html_option .= '<option value="'.get_the_ID().'" '.selected( $id_sc, get_the_ID(),0 ).'>'. get_the_title().'</option>';
			}
		}
		wp_reset_postdata();?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:','teampress'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <p>
            <label for="<?php echo $this->get_field_id("sort_by"); ?>">
            <?php esc_html_e('Select Team','teampress');	 ?>:
            <select class="widefat" id="<?php echo $this->get_field_id("id_sc"); ?>" name="<?php echo $this->get_field_name("id_sc"); ?>">
            	<?php echo $html_option; ?>
            </select>
            </label>
        </p>
<?php
	}
}
// register widget
if(!function_exists('extp_register_widgets')){
	function extp_register_widgets() {
		register_widget( 'Extp_Widget_TeamPress' );
	}
	add_action( 'widgets_init', 'extp_register_widgets' );
}

