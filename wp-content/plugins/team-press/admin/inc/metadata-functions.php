<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/CMB2/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

function extp_get_option( $key = '', $tab=false, $default = false ) {
	if(isset($tab) && $tab!=''){
		$option_key = $tab;
	}else{
		$option_key = 'extp_options';
	}
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( $option_key, $key, $default );
	}
	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( $option_key, $default );
	$val = $default;
	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}
	return $val;
}

add_action( 'cmb2_admin_init', 'extp_register_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function extp_register_metabox() {
	$prefix = 'extp_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$team_info = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Team info', 'teampress' ),
		'object_types'  => array( 'ex_team' ), // Post type
	) );

	$team_info->add_field( array(
		'name'       => esc_html__( 'Position', 'teampress' ),
		'desc'       => esc_html__( 'Enter position of member', 'teampress' ),
		'id'         => $prefix . 'position',
		'classes'             => 'column-3',
		'type'       => 'text',
		'sanitization_cb' => 'extp_allow_metadata_save_html',
	) );

	$team_info->add_field( array(
		'name'       => esc_html__( 'Mobile phone', 'teampress' ),
		'desc'       => esc_html__( 'Enter position of member', 'teampress' ),
		'id'         => $prefix . 'phone',
		'classes'             => 'column-3',
		'type'       => 'text',
	) );
	$team_info->add_field( array(
		'name'       => esc_html__( 'Email', 'teampress' ),
		'desc'       => esc_html__( 'Enter email of member', 'teampress' ),
		'id'         => $prefix . 'email',
		'type'       => 'text',
		'classes'             => 'column-3',
	) );
	$team_info->add_field( array(
		'name'       => esc_html__( 'Color', 'teampress' ),
		'desc'       => esc_html__( 'Select Main Color for this member', 'teampress' ),
		'id'         => $prefix . 'color',
		'classes'             => '',
		'type'       => 'colorpicker',
	) );
	$team_info->add_field( array(
		'name'       => esc_html__( 'Second Image', 'teampress' ),
		'desc'       => esc_html__( 'Select second Image', 'teampress' ),
		'id'         => $prefix . 'image',
		'type'       => 'file',
	) );
	$team_info->add_field( array(
		'name'       => esc_html__( 'Custom/External Link', 'teampress' ),
		'desc'       => esc_html__( 'Enter custom link to replace single member link', 'teampress' ),
		'id'         => $prefix . 'link',
		'type'       => 'text',
		'classes'             => '',
	) );
	$group_ct_info = $team_info->add_field( array(
		'id'          => $prefix . 'custom_team_info',
		'type'        => 'group',
		'description' => esc_html__( 'Add Custom info', 'teampress' ),
		// 'repeatable'  => false, // use false if you want non-repeatable group
		'options'     => array(
			'group_title'   => esc_html__( 'Custom info {#}', 'teampress' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'    => esc_html__( 'Add Another Custom member info', 'teampress' ),
			'remove_button' => esc_html__( 'Remove', 'teampress' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
		'after_group' => 'extp_add_js_for_repeatable_titles',
	) );
	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$team_info->add_group_field( $group_ct_info, array(
		'name' => esc_html__( 'Name', 'teampress' ),
		'id'   => '_name',
		'type' => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );
	$team_info->add_group_field( $group_ct_info, array(
		'name' => esc_html__( 'Content', 'teampress' ),
		'description' => esc_html__( 'Enter content of custom info', 'teampress' ),
		'id'   => '_content',
		'type' => 'text',
	) );
	/*--Social account--*/
	if ( extp_get_option('extp_disable_social') !='yes' ) {
		$social = new_cmb2_box( array(
			'id'            => $prefix . 'social_metabox',
			'title'         => esc_html__( 'Social account info', 'teampress' ),
			'object_types'  => array( 'ex_team' ),
		) );
		$social->add_field( array(
			'name'       => esc_html__( 'Facebook', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'facebook',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
		$social->add_field( array(
			'name'       => esc_html__( 'Twitter', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'twitter',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
		$social->add_field( array(
			'name'       => esc_html__( 'Youtube', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'youtube',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
		$social->add_field( array(
			'name'       => esc_html__( 'Instagram', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'instagram',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
		$social->add_field( array(
			'name'       => esc_html__( 'Behance', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'behance',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
		$social->add_field( array(
			'name'       => esc_html__( 'Dribbble', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'dribble',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
		$social->add_field( array(
			'name'       => esc_html__( 'Flickr', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'flickr',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
	
		$social->add_field( array(
			'name'       => esc_html__( 'Github', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'github',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
	
		
		$social->add_field( array(
			'name'       => esc_html__( 'LinkedIn', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'linkedin',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
	
		$social->add_field( array(
			'name'       => esc_html__( 'Pinterest', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'pinterest',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
	
		$social->add_field( array(
			'name'       => esc_html__( 'Tumblr', 'teampress' ),
			'desc'       => esc_html__( 'Enter link of social account', 'teampress' ),
			'id'         => $prefix . 'tumblr',
			'type'       => 'text',
			'classes'             => 'column-3',
		) );
		/*-- Custom social field--*/
		$custom_social = new_cmb2_box( array(
			'id'            => $prefix . 'custom_social',
			'title'         => esc_html__( 'Custom Social Account', 'teampress' ),
			'object_types'  => array( 'ex_team' ),
		) );
		$group_ctsocial = $custom_social->add_field( array(
			'id'          => $prefix . 'custom_social_gr',
			'type'        => 'group',
			'description' => esc_html__( 'Add Custom Social Account', 'teampress' ),
			// 'repeatable'  => false, // use false if you want non-repeatable group
			'options'     => array(
				'group_title'   => esc_html__( 'Custom social {#}', 'teampress' ), // since version 1.1.4, {#} gets replaced by row number
				'add_button'    => esc_html__( 'Add Another Custom social', 'teampress' ),
				'remove_button' => esc_html__( 'Remove Custom social', 'teampress' ),
				'sortable'      => true, // beta
				// 'closed'     => true, // true to have the groups closed by default
			),
			'after_group' => 'extp_add_js_for_repeatable_titles',
		) );
		// Id's for group's fields only need to be unique for the group. Prefix is not needed.
		$custom_social->add_group_field( $group_ctsocial, array(
			'name' => esc_html__( 'Custom social Name', 'teampress' ),
			'id'   => '_name',
			'type' => 'text',
			// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
		) );
		$custom_social->add_group_field( $group_ctsocial, array(
			'name' => esc_html__( 'Custom social Link', 'teampress' ),
			'description' => esc_html__( 'Enter Custom social account Link', 'teampress' ),
			'id'   => '_url',
			'type' => 'text',
		) );
		$custom_social->add_group_field( $group_ctsocial, array(
			'name' => esc_html__( 'Custom social Link Icon', 'teampress' ),
			'id'   => '_icon',
			'description' => esc_html__( 'Enter Font Awesome 5 html: https://fontawesome.com/icons , example: <i class="fab fa-android"></i>', 'teampress' ),
			'type' => 'text',
			'sanitization_cb' => 'extp_allow_metadata_save_html',
		) );
	}
}
function extp_allow_metadata_save_html( $original_value, $args, $cmb2_field ) {
    return $original_value; // Unsanitized value.
}
function extp_add_js_for_repeatable_titles() {
	add_action( is_admin() ? 'admin_footer' : 'wp_footer', 'extp_add_js_for_repeatable_titles_to_footer' );
}
function extp_add_js_for_repeatable_titles_to_footer() {
	?>
	<script type="text/javascript">
	jQuery( function( $ ) {
		var $box = $( document.getElementById( 'extp_custom_social' ) );
		var replaceTitles = function() {
			$box.find( '.cmb-group-title' ).each( function() {
				var $this = $( this );
				var txt = $this.next().find( '[id$="_name"]' ).val();
				var rowindex;
				if ( ! txt ) {
					txt = $box.find( '[data-grouptitle]' ).data( 'grouptitle' );
					if ( txt ) {
						rowindex = $this.parents( '[data-iterator]' ).data( 'iterator' );
						txt = txt.replace( '{#}', ( rowindex + 1 ) );
					}
				}
				if ( txt ) {
					$this.text( txt );
				}
			});
		};
		var replaceOnKeyUp = function( evt ) {
			var $this = $( evt.target );
			var id = 'title';
			if ( evt.target.id.indexOf(id, evt.target.id.length - id.length) !== -1 ) {
				$this.parents( '.cmb-row.cmb-repeatable-grouping' ).find( '.cmb-group-title' ).text( $this.val() );
			}
		};
		$box
			.on( 'cmb2_add_row cmb2_shift_rows_complete', replaceTitles )
			.on( 'keyup', replaceOnKeyUp );
		replaceTitles();
	});
	</script>
	<?php
}
/**
 * Callback to define the optionss-saved message.
 *
 * @param CMB2  $cmb The CMB2 object.
 * @param array $args {
 *     An array of message arguments
 *
 *     @type bool   $is_options_page Whether current page is this options page.
 *     @type bool   $should_notify   Whether options were saved and we should be notified.
 *     @type bool   $is_updated      Whether options were updated with save (or stayed the same).
 *     @type string $setting         For add_settings_error(), Slug title of the setting to which
 *                                   this error applies.
 *     @type string $code            For add_settings_error(), Slug-name to identify the error.
 *                                   Used as part of 'id' attribute in HTML output.
 *     @type string $message         For add_settings_error(), The formatted message text to display
 *                                   to the user (will be shown inside styled `<div>` and `<p>` tags).
 *                                   Will be 'Settings updated.' if $is_updated is true, else 'Nothing to update.'
 *     @type string $type            For add_settings_error(), Message type, controls HTML class.
 *                                   Accepts 'error', 'updated', '', 'notice-warning', etc.
 *                                   Will be 'updated' if $is_updated is true, else 'notice-warning'.
 * }
 */
function extp_options_page_message_( $cmb, $args ) {
	if ( ! empty( $args['should_notify'] ) ) {

		if ( $args['is_updated'] ) {

			// Modify the updated message.
			$args['message'] = sprintf( esc_html__( '%s &mdash; Updated!', 'teampress' ), $cmb->prop( 'title' ) );
		}

		add_settings_error( $args['setting'], $args['code'], $args['message'], $args['type'] );
	}
}


function extp_register_setting_options() {
	/**
	 * Registers main options page menu item and form.
	 */
	$args = array(
		'id'           => 'extp_options_page',
		'title'        => esc_html__('Settings','teampress'),
		'object_types' => array( 'options-page' ),
		'option_key'   => 'extp_options',
		//'parent_slug'  => 'edit.php?post_type=ex_team',
		'tab_group'    => 'extp_options',
		'tab_title'    => esc_html__('General','teampress'),
		'message_cb'      => 'extp_options_page_message_',
	);
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'extp_options_display_with_tabs';
	}
	$main_options = new_cmb2_box( $args );
	/**
	 * Options fields ids only need
	 * to be unique within this box.
	 * Prefix is not needed.
	 */
	$main_options->add_field( array(
		'name'    => esc_html__('Main Color','teampress'),
		'desc'    => esc_html__('Choose Main Color for plugin','teampress'),
		'id'      => 'extp_color',
		'type'    => 'colorpicker',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'       => esc_html__( 'Content Font Family', 'teampress' ),
		'desc'       => esc_html__('Enter Google font-family name . For example, if you choose "Source Sans Pro" Google Font, enter Source Sans Pro','teampress'),
		'id'         => 'extp_font_family',
		'type'       => 'text',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'       => esc_html__( 'Content Font Size', 'teampress' ),
		'desc'       => esc_html__('Enter size of main font, default:13px, Ex: 14px','teampress'),
		'id'         => 'extp_font_size',
		'type'       => 'text',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'    => esc_html__('Content Font Color','teampress'),
		'desc'    => esc_html__('Choose Content Font Color for plugin','teampress'),
		'id'      => 'extp_ctcolor',
		'type'    => 'colorpicker',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'       => esc_html__( 'Heading Font Family', 'teampress' ),
		'desc'       => esc_html__('Enter Google font-family name. For example, if you choose "Oswald" Google Font, enter Oswald','teampress'),
		'id'         => 'extp_headingfont_family',
		'type'       => 'text',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'       => esc_html__( 'Heading Font Size', 'teampress' ),
		'desc'       => esc_html__('Enter size of heading font, default: 20px, Ex: 22px','teampress'),
		'id'         => 'extp_headingfont_size',
		'type'       => 'text',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'    => esc_html__('Heading Font Color','teampress'),
		'desc'    => esc_html__('Choose Heading Font Color for plugin','teampress'),
		'id'      => 'extp_hdcolor',
		'type'    => 'colorpicker',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'       => esc_html__( 'Meta Font Family', 'teampress' ),
		'desc'       => esc_html__('Enter Google font-family name. For example, if you choose "Ubuntu" Google Font, enter Ubuntu','teampress'),
		'id'         => 'extp_metafont_family',
		'type'       => 'text',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'       => esc_html__( 'Meta Font Size', 'teampress' ),
		'desc'       => esc_html__('Enter size of metadata font, default:13px, Ex: 12px','teampress'),
		'id'         => 'extp_metafont_size',
		'type'       => 'text',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'    => esc_html__('Meta Font Color','teampress'),
		'desc'    => esc_html__('Choose Meta Font Color for plugin','teampress'),
		'id'      => 'extp_mtcolor',
		'type'    => 'colorpicker',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'             => esc_html__( 'Disable link & Single member page', 'teampress' ),
		'desc'             => esc_html__( 'Select yes to disable link to single member page', 'teampress' ),
		'id'               => 'extp_disable_single',
		'type'             => 'select',
		'show_option_none' => false,
		'default' => '',
		'options'          => array(
			'' => esc_html__( 'No', 'teampress' ),
			'yes'   => esc_html__( 'Yes', 'teampress' ),
		),
	) );
	$main_options->add_field( array(
		'name'             => esc_html__( 'RTL mode', 'teampress' ),
		'desc'             => esc_html__( 'Enable RTL mode for RTL language', 'teampress' ),
		'id'               => 'extp_enable_rtl',
		'type'             => 'select',
		'show_option_none' => false,
		'default' => '',
		'options'          => array(
			'' => esc_html__( 'No', 'teampress' ),
			'yes'   => esc_html__( 'Yes', 'teampress' ),
		),
	) );
	$main_options->add_field( array(
		'name'             => esc_html__( 'Team slug', 'teampress' ),
		'desc'             => esc_html__( 'Remember to save the permalink settings again in Settings > Permalinks', 'teampress' ),
		'show_on_cb' => 'extp_hide_if_disable_single',
		'id'               => 'extp_single_slug',
		'type'       => 'text',
		'default' => '',
	) );
	$main_options->add_field( array(
		'name'             => esc_html__( 'Disable social account', 'teampress' ),
		'desc'             => esc_html__( 'Select yes to disable social account', 'teampress' ),
		'id'               => 'extp_disable_social',
		'type'             => 'select',
		'default' 		   => '',
		'show_option_none' => false,
		'options'          => array(
			'' => esc_html__( 'No', 'teampress' ),
			'yes'   => esc_html__( 'Yes', 'teampress' ),
		),
	) );

	/**
	 * Registers secondary options page, and set main item as parent.
	 */
	$args = array(
		'id'           => 'extp_custom_code',
		'menu_title'   => '',
		'object_types' => array( 'options-page' ),
		'option_key'   => 'extp_custom_code_options',
		//'parent_slug'  => 'edit.php?post_type=ex_team',
		'tab_group'    => 'extp_options',
		'tab_title'    => esc_html__('Custom Code','teampress'),
	);
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'extp_options_display_with_tabs';
	}
	$customcode_options = new_cmb2_box( $args );
	$customcode_options->add_field( array(
		'name' => esc_html__('Custom Css','teampress'),
		'desc' => esc_html__('Paste your custom Css code','teampress'),
		'id'   => 'extp_custom_css',
		'type' => 'textarea_code',
		'attributes' => array(
			'data-codeeditor' => json_encode( array(
				'codemirror' => array(
					'mode' => 'css'
				),
			) ),
		),
		'sanitization_cb' => 'extp_allow_metadata_save_html',
	) );
	$customcode_options->add_field( array(
		'name' => esc_html__('Custom Js','teampress'),
		'desc' => esc_html__('Paste your custom Js code','teampress'),
		'id'   => 'extp_custom_js',
		'type' => 'textarea_code',
		'attributes' => array(
			'data-codeeditor' => json_encode( array(
				'codemirror' => array(
					'mode' => 'javascript'
				),
			) ),
		),
		'sanitization_cb' => 'extp_allow_metadata_save_html',
	) );
	/**
	 * Registers tertiary options page, and set main item as parent.
	 */
	$args = array(
		'id'           => 'extp_js_css_file',
		'menu_title'   => '',
		'object_types' => array( 'options-page' ),
		'option_key'   => 'extp_js_css_file_options',
		//'parent_slug'  => 'edit.php?post_type=ex_team',
		'tab_group'    => 'extp_options',
		'tab_title'    => esc_html__('Js + Css file','teampress'),
	);
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'extp_options_display_with_tabs';
	}
	$file_options = new_cmb2_box( $args );
	$file_options->add_field( array(
		'name'             => esc_html__( 'Turn off Google Font', 'teampress' ),
		'desc'             => esc_html__( 'Turn off loading Google Font', 'teampress' ),
		'id'               => 'extp_disable_ggfont',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'no' => esc_html__( 'No', 'teampress' ),
			'yes'   => esc_html__( 'Yes', 'teampress' ),
		),
	) );
	$file_options->add_field( array(
		'name'             => esc_html__( 'Turn off Font Awesome', 'teampress' ),
		'desc'             => esc_html__( "Turn off loading plugin's Font Awesome. Check if your theme has already loaded this library", 'teampress' ),
		'id'               => 'extp_disable_awefont',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'no' => esc_html__( 'No', 'teampress' ),
			'yes'   => esc_html__( 'Yes', 'teampress' ),
		),
	) );


	/**
	 * Registers purchase code
	 */
	$args = array(
		'id'           => 'extp_verify_purchase',
		'menu_title'   => '',
		'object_types' => array( 'options-page' ),
		'option_key'   => 'extp_verify_options',
		//'parent_slug'  => 'edit.php?post_type=product',
		'tab_group'    => 'extp_options',
		'tab_title'    => esc_html__('Plugin License','teampress'),
	);
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'extp_options_display_with_tabs';
	}
	$vrtf_purc_options = new_cmb2_box( $args );
	$vrtf_purc_options->add_field( array(
		'name'             => esc_html__( 'Envato Username', 'teampress' ),
		'desc'             => esc_html__( 'Enter Envato username which you have purchased this plugin', 'teampress' ),
		'id'               => 'extp_evt_name',
		'type'             => 'text',
	) );
	$vrtf_purc_options->add_field( array(
		'name'             => esc_html__( 'Purchase Code', 'teampress' ),
		'desc'             => sprintf(esc_html__( 'Enter your %s purcahse code %s of this plugin', 'teampress' ), '<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">', '</a>'),
		'id'               => 'extp_evt_pcode',
		'type'             => 'text',
		'after_row'     => 'extp_delete_license_html',
	) );

}
add_action( 'cmb2_admin_init', 'extp_register_setting_options' );

function extp_delete_license_html(){
	$_name = extp_get_option('extp_evt_name','extp_verify_options');
	$_pcode = extp_get_option('extp_evt_pcode','extp_verify_options');
	if($_name!='' && $_pcode!=''){
		echo '<p><a href="?page=extp_verify_options&delete_license=yes">Deactivate license from this site ?</a><p>';
	}
}
function extp_remove_vali_ppr( $op ) { 
    if($op=='extp_verify_options'){
    	update_option( 'extp_license', '');
    }
}; 
         
// add the action 
add_action( 'cmb2_save_options-page_fields', 'extp_remove_vali_ppr', 10, 1 ); 

function extp_hide_if_disable_single( $field ) {
	if ( extp_get_option('extp_disable_single') =='yes' ) {
		return false;
	}
	return true;
}
/**
 * A CMB2 options-page display callback override which adds tab navigation among
 * CMB2 options pages which share this same display callback.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 */
function extp_options_display_with_tabs( $cmb_options ) {
	$tabs = extp_options_page_tabs( $cmb_options );
	?>
	<div class="wrap cmb2-options-page option-<?php echo $cmb_options->option_key; ?>">
		<?php if ( get_admin_page_title() ) : ?>
			<h2><?php echo wp_kses_post( get_admin_page_title() ); ?></h2>
		<?php endif; ?>
		<h2 class="nav-tab-wrapper">
			<?php foreach ( $tabs as $option_key => $tab_title ) : ?>
				<a class="nav-tab<?php if ( isset( $_GET['page'] ) && $option_key === $_GET['page'] ) : ?> nav-tab-active<?php endif; ?>" href="<?php menu_page_url( $option_key ); ?>"><?php echo wp_kses_post( $tab_title ); ?></a>
			<?php endforeach; ?>
		</h2>
		<form class="cmb-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="<?php echo $cmb_options->cmb->cmb_id; ?>" enctype="multipart/form-data" encoding="multipart/form-data">
			<input type="hidden" name="action" value="<?php echo esc_attr( $cmb_options->option_key ); ?>">
			<?php $cmb_options->options_page_metabox(); ?>
			<?php submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' ); ?>
		</form>
	</div>
	<?php
}
/**
 * Gets navigation tabs array for CMB2 options pages which share the given
 * display_cb param.
 *
 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
 *
 * @return array Array of tab information.
 */
function extp_options_page_tabs( $cmb_options ) {
	$tab_group = $cmb_options->cmb->prop( 'tab_group' );
	$tabs      = array();
	foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
		if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
			$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
				? $cmb->prop( 'tab_title' )
				: $cmb->prop( 'title' );
		}
	}
	return $tabs;
}