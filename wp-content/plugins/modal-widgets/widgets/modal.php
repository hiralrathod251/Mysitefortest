<?php

namespace Modal_Widgets\Widgets;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Background;
use Elementor\Control_Media;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Modal extends Widget_Base
{
  public function get_name()
  {
    return 'modal';
  }

  public function get_title()
  {
    return esc_html__('Modal Popup', 'fel');
  }

  public function get_icon()
  {
    return 'fas fa-ticket-alt';
  }

  public function get_categories()
  {
    return ['themesflat_addons'];
  }

  public function get_script_depends()
  {
    return ['js-cookie', 'jquery-resize', 'modal-script'];
  }

  public function get_style_depends()
  {
    return ['font-awesome', 'modal-widgets-style'];
  }

  protected function _register_controls()
  {
    $this->normal_content_controls();
    $this->modal_popup_content_controls();
    $this->close_content_controls();
    $this->display_content_controls();
    $this->register_controls_style();
  }

  function register_controls_style () {
    $this->title_style_controls();
    $this->content_style_controls();
    $this->button_style_controls();
    $this->cta_style_controls();
    
  }

  protected function modal_popup_content_controls()
        {
        $this->start_controls_section(
          'section_modal',
          array(
            'label' => esc_html__('Modal Popup', 'fel'),
            'tab'   => Controls_Manager::TAB_LAYOUT,
          )
        );

        $this->add_responsive_control(
          'modal_width',
          array(
            'label' => esc_html__('Modal Popup Width', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => array('px', 'em', '%'),
            'default' => array(
              'size' => '500',
              'unit' => 'px',
            ),
            'tablet_default' => array(
              'size' => '500',
              'unit' => 'px',
            ),
            'mobile_default' => array(
              'size' => '300',
              'unit' => 'px',
            ),
            'range' => array(
              'px' => array(
                'min' => 0,
                'max' => 1500,
              ),
              'em' => array(
                'min' => 0,
                'max' => 100,
              ),
              '%' => array(
                'min' => 0,
                'max' => 100,
              ),
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-content' => 'width: {{SIZE}}{{UNIT}}',
            ),
          )
        );

        $this->add_responsive_control(
          'iframe_height',
          array(
            'label' => esc_html__('Height of Iframe', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => array('px', 'em'),
            'default' => array(
              'size' => '500',
              'unit' => 'px',
            ),
            'range' => array(
              'px' => array(
                'min' => 0,
                'max' => 2000,
              ),
              'em' => array(
                'min' => 0,
                'max' => 100,
              ),
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-iframe .fel-modal-content-data' => 'height: {{SIZE}}{{UNIT}}',
            ),
            'condition' => array(
              'content_type' => 'iframe',
            ),
          )
        );

        $this->add_control(
          'modal_effect',
          array(
            'label' => esc_html__('Modal Appear Effect', 'fel'),
            'type' => Controls_Manager::SELECT,
            'default' => 'fel-effect-1',
            'label_block' => true,
            'options' => array(
              'fel-effect-1' => esc_html__('Fade in &amp; Scale', 'fel'),
              'fel-effect-2' => esc_html__('Slide in (right)', 'fel'),
              'fel-effect-3' => esc_html__('Slide in (bottom)', 'fel'),
              'fel-effect-4' => esc_html__('Newspaper', 'fel'),
              'fel-effect-5' => esc_html__('Fall', 'fel'),
              'fel-effect-6' => esc_html__('Side Fall', 'fel'),
              'fel-effect-8' => esc_html__('3D Flip (horizontal)', 'fel'),
              'fel-effect-9' => esc_html__('3D Flip (vertical)', 'fel'),
              'fel-effect-10' => esc_html__('3D Sign', 'fel'),
              'fel-effect-11' => esc_html__('Super Scaled', 'fel'),
              'fel-effect-13' => esc_html__('3D Slit', 'fel'),
              'fel-effect-14' => esc_html__('3D Rotate Bottom', 'fel'),
              'fel-effect-15' => esc_html__('3D Rotate In Left', 'fel'),
              'fel-effect-17' => esc_html__('Let me in', 'fel'),
              'fel-effect-18' => esc_html__('Make way!', 'fel'),
              'fel-effect-19' => esc_html__('Slip from top', 'fel'),
            ),
          )
        );

        $this->add_control(
          'overlay_color',
          array(
            'label' => esc_html__('Overlay Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(0,0,0,0.75)',
            'selectors' => array(
              '.felmodal-{{ID}} .fel-overlay' => 'background: {{VALUE}};',
            ),
          )
        );

        $this->end_controls_section();

  }

  protected function normal_content_controls()
      {

        $this->start_controls_section(
          'content',
          array(
            'label' => esc_html__('Content', 'fel'),
          )
        );

        $this->add_control(
          'title',
          array(
            'label' => esc_html__('Title', 'fel'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => array(
              'active' => true,
            ),
            'default' => esc_html__('This is Modal Title', 'fel'),
          )
        );

        $this->add_control(
          'content_type',
          array(
            'label' => esc_html__('Content Type', 'fel'),
            'type' => Controls_Manager::SELECT,
            'default' => 'photo',
            'options' => $this->get_content_type(),
          )
        );

        $this->add_control(
          'ct_content',
          array(
            'label' => esc_html__('Description', 'fel'),
            'type' => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Enter content here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'fel'),
            'rows' => 10,
            'show_label' => false,
            'dynamic' => array(
              'active' => true,
            ),
            'condition' => array(
              'content_type' => 'content',
            ),
          )
        );

        $this->add_control(
          'ct_photo',
          array(
            'label' => esc_html__('Photo', 'fel'),
            'type' => Controls_Manager::MEDIA,
            'default' => array(
              'url' => Utils::get_placeholder_image_src(),
            ),
            'dynamic' => array(
              'active' => true,
            ),
            'condition' => array(
              'content_type' => 'photo',
            ),
          )
        );

        $this->add_control(
          'ct_video',
          array(
            'label' => esc_html__('Embed Code / URL', 'fel'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'dynamic' => array(
              'active' => true,
              'categories' => array(
                TagsModule::URL_CATEGORY,
              ),
            ),
            'condition' => array(
              'content_type' => 'video',
            ),
          )
        );

        $this->add_control(
          'ct_saved_rows',
          array(
            'label' => esc_html__('Select Section', 'fel'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_saved_data('section'),
            'default' => '-1',
            'condition' => array(
              'content_type' => 'saved_rows',
            ),
          )
        );

        $this->add_control(
          'ct_saved_modules',
          array(
            'label' => esc_html__('Select Widget', 'fel'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_saved_data('widget'),
            'default' => '-1',
            'condition' => array(
              'content_type' => 'saved_modules',
            ),
          )
        );

        $this->add_control(
          'ct_page_templates',
          array(
            'label' => esc_html__('Select Page', 'fel'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->get_saved_data('page'),
            'default' => '-1',
            'condition' => array(
              'content_type' => 'saved_page_templates',
            ),
          )
        );

        $this->add_control(
          'video_url',
          array(
            'label' => esc_html__('Video URL', 'fel'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'dynamic' => array(
              'active' => true,
              'categories' => array(
                TagsModule::URL_CATEGORY,
              ),
            ),
            'condition' => array(
              'content_type' => array('youtube', 'vimeo'),
            ),
          )
        );

        $this->add_control(
          'youtube_link_doc',
          array(
            'type' => Controls_Manager::RAW_HTML,
            /* translators: %1$s doc link */
            'raw' => sprintf('<b>Note:</b> Make sure you add the actual URL of the video and not the share URL.</br></br><b>Valid:</b>&nbsp;https://www.youtube.com/watch?v=HJRzUQMhJMQ</br><b>Invalid:</b>&nbsp;https://youtu.be/HJRzUQMhJMQ'),
            'content_classes' => 'fel-editor-doc',
            'condition' => array(
              'content_type' => 'youtube',
            ),
            'separator' => 'none',
          )
        );

        $this->add_control(
          'vimeo_link_doc',
          array(
            'type' => Controls_Manager::RAW_HTML,
            /* translators: %1$s doc link */
            'raw' => sprintf('<b>Note:</b> Make sure you add the actual URL of the video and not the categorized URL.</br></br><b>Valid:</b>&nbsp;https://vimeo.com/274860274</br><b>Invalid:</b>&nbsp;https://vimeo.com/channels/staffpicks/274860274'),
            'content_classes' => 'fel-editor-doc',
            'condition' => array(
              'content_type' => 'vimeo',
            ),
            'separator' => 'none',
          )
        );

        $this->add_control(
          'iframe_url',
          array(
            'label' => esc_html__('Iframe URL', 'fel'),
            'type' => Controls_Manager::TEXT,
            'label_block' => true,
            'dynamic' => array(
              'active' => true,
              'categories' => array(
                TagsModule::URL_CATEGORY,
              ),
            ),
            'condition' => array(
              'content_type' => 'iframe',
            ),
          )
        );

        $this->add_control(
          'async_iframe',
          array(
            'label' => esc_html__('Async Iframe Load', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
            'description' => esc_html__('Enabling this option will reduce the page size and page loading time. The related CSS and JS scripts will load on request. A loader will appear during loading of the Iframe.', 'fel'),
            'condition' => array(
              'content_type' => 'iframe',
            ),
          )
        );

        $this->add_control(
          'video_ratio',
          array(
            'label' => esc_html__('Aspect Ratio', 'fel'),
            'type' => Controls_Manager::SELECT,
            'options' => array(
              '16_9' => '16:9',
              '4_3' => '4:3',
              '3_2' => '3:2',
            ),
            'default' => '16_9',
            'prefix_class' => 'fel-aspect-ratio-',
            'frontend_available' => true,
            'condition' => array(
              'content_type' => array('youtube', 'vimeo'),
            ),
          )
        );

        $this->add_control(
          'video_autoplay',
          array(
            'label' => esc_html__('Autoplay', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
            'condition' => array(
              'content_type' => array('youtube', 'vimeo'),
            ),
          )
        );

        $this->add_control(
          'youtube_related_videos',
          array(
            'label' => esc_html__('Related Videos From', 'fel'),
            'type' => Controls_Manager::SELECT,
            'default' => 'no',
            'options' => array(
              'no' => esc_html__('Current Video Channel', 'fel'),
              'yes' => esc_html__('Any Random Video', 'fel'),
            ),
            'condition' => array(
              'content_type' => 'youtube',
            ),
          )
        );

        $this->add_control(
          'youtube_player_controls',
          array(
            'label' => esc_html__('Disable Player Controls', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
            'condition' => array(
              'content_type' => 'youtube',
            ),
          )
        );

        $this->add_control(
          'video_controls_adv',
          array(
            'label' => esc_html__('Advanced Settings', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
            'condition' => array(
              'content_type' => array('youtube', 'vimeo'),
            ),
          )
        );

        $this->add_control(
          'start',
          array(
            'label' => esc_html__('Start Time', 'fel'),
            'type' => Controls_Manager::NUMBER,
            'description' => esc_html__('Specify a start time (in seconds)', 'fel'),
            'condition' => array(
              'content_type' => array('youtube', 'vimeo'),
              'video_controls_adv' => 'yes',
            ),
          )
        );

        $this->add_control(
          'end',
          array(
            'label' => esc_html__('End Time', 'fel'),
            'type' => Controls_Manager::NUMBER,
            'description' => esc_html__('Specify an end time (in seconds)', 'fel'),
            'condition' => array(
              'content_type' => 'youtube',
              'video_controls_adv' => 'yes',
            ),
          )
        );

        $this->add_control(
          'yt_mute',
          array(
            'label' => esc_html__('Mute', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => array(
              'content_type' => 'youtube',
              'video_controls_adv' => 'yes',
            ),
          )
        );

        $this->add_control(
          'yt_modestbranding',
          array(
            'label' => esc_html__('Modest Branding', 'fel'),
            'description' => esc_html__('This option lets you use a YouTube player that does not show a YouTube logo.', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
            'condition' => array(
              'content_type' => 'youtube',
              'video_controls_adv' => 'yes',
              'youtube_player_controls!' => 'yes',
            ),
          )
        );

        $this->add_control(
          'vimeo_loop',
          array(
            'label' => esc_html__('Loop', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => array(
              'content_type' => 'vimeo',
              'video_controls_adv' => 'yes',
            ),
          )
        );

        $this->end_controls_section();
  }

  protected function close_content_controls()
      {

        $this->start_controls_section(
          'close_options',
          array(
            'label' => esc_html__('Close Button', 'fel'),
            'tab'   => Controls_Manager::TAB_LAYOUT,
          )
        );

        $this->add_control(
          'close_source',
          array(
            'label' => esc_html__('Close As', 'fel'),
            'type' => Controls_Manager::CHOOSE,
            'options' => array(
              'img' => array(
                'title' => esc_html__('Image', 'fel'),
                'icon' => 'fa fa-picture-o',
              ),
              'icon' => array(
                'title' => esc_html__('Icon', 'fel'),
                'icon' => 'fa fa-info-circle',
              ),
            ),
            'default' => 'icon',
          )
        );

        /**
         * Condition: 'close_source' => 'img'
         */
        $this->add_control(
          'close_photo',
          array(
            'label' => esc_html__('Close Image', 'fel'),
            'type' => Controls_Manager::MEDIA,
            'default' => array(
              'url' => Utils::get_placeholder_image_src(),
            ),
            'condition' => array(
              'close_source' => 'img',
            ),
          )
        );

        /**
         * Condition: 'close_source' => 'icon'
         */

        if ($this->is_elementor_updated()) {

          $this->add_control(
            'new_close_icon',
            array(
              'label' => esc_html__('Close Icon', 'fel'),
              'type' => Controls_Manager::ICONS,
              'fa4compatibility' => 'close_icon',
              'default' => array(
                'value' => 'fa fa-close',
                'library' => 'fa-solid',
              ),
              'condition' => array(
                'close_source' => 'icon',
              ),
              'render_type' => 'template',
            )
          );
        } else {
          $this->add_control(
            'close_icon',
            array(
              'label' => esc_html__('Close Icon', 'fel'),
              'type' => Controls_Manager::ICON,
              'default' => 'fa fa-close',
              'condition' => array(
                'close_source' => 'icon',
              ),
            )
          );
        }

        if ($this->is_elementor_updated()) {
          $this->add_responsive_control(
            'close_icon_size',
            array(
              'label' => esc_html__('Size', 'fel'),
              'type' => Controls_Manager::SLIDER,
              'default' => array(
                'size' => 20,
              ),
              'range' => array(
                'px' => array(
                  'max' => 500,
                ),
              ),
              'selectors' => array(
                '.felmodal-{{ID}} .fel-modal-close' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;',
                '.felmodal-{{ID}} .fel-modal-close svg' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
              ),
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'close_source',
                    'operator' => '==',
                    'value' => 'icon',
                  ),
                ),
              ),
            )
          );
        }
        else {
          $this->add_responsive_control(
            'close_icon_size',
            array(
              'label' => esc_html__('Size', 'fel'),
              'type' => Controls_Manager::SLIDER,
              'default' => array(
                'size' => 20,
              ),
              'range' => array(
                'px' => array(
                  'max' => 500,
                ),
              ),
              'selectors' => array(
                '.felmodal-{{ID}} .fel-modal-close' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;',
                '.felmodal-{{ID}} .fel-modal-close svg' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
              ),
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'close_icon',
                    'operator' => '!=',
                    'value' => '',
                  ),
                  array(
                    'name' => 'close_source',
                    'operator' => '==',
                    'value' => 'icon',
                  ),
                ),
              ),
            )
          );
        }

        $this->add_responsive_control(
          'close_img_size',
          array(
            'label' => esc_html__('Size', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 20,
            ),
            'range' => array(
              'px' => array(
                'max' => 500,
              ),
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-close' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
            ),
            'condition' => array(
              'close_source' => 'img',
            ),
          )
        );

        if ($this->is_elementor_updated()) {
          $this->add_control(
            'close_icon_color',
            array(
              'label' => esc_html__('Color', 'fel'),
              'type' => Controls_Manager::COLOR,
              'default' => '#ffffff',
              'selectors' => array(
                '.felmodal-{{ID}} .fel-modal-close i' => 'color: {{VALUE}};',
                '.felmodal-{{ID}} .fel-modal-close svg' => 'fill: {{VALUE}};',
              ),
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'close_source',
                    'operator' => '==',
                    'value' => 'icon',
                  ),
                ),
              ),
            )
          );
        }
        else {
          $this->add_control(
            'close_icon_color',
            array(
              'label' => esc_html__('Color', 'fel'),
              'type' => Controls_Manager::COLOR,
              'default' => '#ffffff',
              'selectors' => array(
                '.felmodal-{{ID}} .fel-modal-close i' => 'color: {{VALUE}};',
                '.felmodal-{{ID}} .fel-modal-close svg' => 'fill: {{VALUE}};',
              ),
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'close_icon',
                    'operator' => '!=',
                    'value' => '',
                  ),
                  array(
                    'name' => 'close_source',
                    'operator' => '==',
                    'value' => 'icon',
                  ),
                ),
              ),
            )
          );
        }

        $this->add_responsive_control(
          'close_border',
          array(
            'label' => esc_html__('width', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 0,
            ),
            'range' => array(
              'px' => array(
                'max' => 500,
              ),
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-close' => 'border: {{SIZE}}px solid;',
            )
          )
        );    

        $this->add_control(
          'close_border_color',
          array(
            'label' => esc_html__('Background Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-close' => 'border-color: {{VALUE}};background-color: {{VALUE}};',
            ),
          )
        );

        $this->add_control(
          'close_border_color_hover',
          array(
            'label' => esc_html__('Background Color Hover', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-close:hover' => 'border-color: {{VALUE}};background-color: {{VALUE}};',
            ),
          )
        );

        $this->add_responsive_control(
          'close_border_radius',
          array(
            'label' => esc_html__('Border radius', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 0,
            ),
            'range' => array(
              'px' => array(
                'max' => 500,
              ),
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-close' => 'border-radius: {{SIZE}}px;',
            )
          )
        );

        $this->add_control(
          'icon_position',
          array(
            'label' => esc_html__('Image / Icon Position', 'fel'),
            'type' => Controls_Manager::SELECT,
            'default' => 'top-right',
            'label_block' => true,
            'options' => array(
              'top-left' => esc_html__('Window - Top Left', 'fel'),
              'top-right' => esc_html__('Window - Top Right', 'fel'),
              'popup-top-left' => esc_html__('Popup - Top Left', 'fel'),
              'popup-top-right' => esc_html__('Popup - Top Right', 'fel'),
              'popup-edge-top-left' => esc_html__('Popup Edge - Top Left', 'fel'),
              'popup-edge-top-right' => esc_html__('Popup Edge - Top Right', 'fel'),
            ),
          )
        );

        $this->add_control(
          'esc_keypress',
          array(
            'label' => esc_html__('Close on ESC Keypress', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
          )
        );

        $this->add_control(
          'overlay_click',
          array(
            'label' => esc_html__('Close on Overlay Click', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
          )
        );

        $this->end_controls_section();
  }

  protected function display_content_controls()
      {

        $this->start_controls_section(
          'modal',
          array(
            'label' => esc_html__('Display Settings', 'fel'),
            'tab'   => Controls_Manager::TAB_LAYOUT,
          )
        );

        $this->add_control(
          'modal_on',
          array(
            'label' => esc_html__('Display Modal On', 'fel'),
            'type' => Controls_Manager::SELECT,
            'default' => 'button',
            'options' => array(
              'icon' => esc_html__('Icon', 'fel'),
              'photo' => esc_html__('Image', 'fel'),
              'text' => esc_html__('Text', 'fel'),
              'button' => esc_html__('Button', 'fel'),
              'custom' => esc_html__('Custom Class', 'fel'),
              'custom_id' => esc_html__('Custom ID', 'fel'),
              'automatic' => esc_html__('Automatic', 'fel'),
              'via_url' => esc_html__('Via URL', 'fel'),
            ),
          )
        );

        $this->add_control(
          'via_url_message',
          array(
            'type' => Controls_Manager::RAW_HTML,
            'raw' => sprintf('<p style="font-size: 11px;font-style: italic;line-height: 1.4;color: #a4afb7;">%s</p>', esc_html__('Append the "?fel-modal-action=modal-popup-id" at the end of your URL.', 'fel')),
            'condition' => array(
              'modal_on' => 'via_url',
            ),
          )
        );

        if ($this::is_elementor_updated()) {

          $this->add_control(
            'new_icon',
            array(
              'label' => esc_html__('Icon', 'fel'),
              'type' => Controls_Manager::ICONS,
              'fa4compatibility' => 'icon',
              'default' => array(
                'value' => 'fa fa-home',
                'library' => 'fa-solid',
              ),
              'condition' => array(
                'modal_on' => 'icon',
              ),
              'render_type' => 'template',
            )
          );
        } else {
          $this->add_control(
            'icon',
            array(
              'label' => esc_html__('Icon', 'fel'),
              'type' => Controls_Manager::ICON,
              'default' => 'fa fa-home',
              'condition' => array(
                'modal_on' => 'icon',
              ),
            )
          );
        }

        $this->add_control(
          'icon_size',
          array(
            'label' => esc_html__('Size', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 60,
            ),
            'range' => array(
              'px' => array(
                'max' => 500,
              ),
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action i, {{WRAPPER}} .fel-modal-action svg' => 'font-size: {{SIZE}}px;width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px;',
            ),
            'condition' => array(
              'modal_on' => 'icon',
            ),
          )
        );

        $this->add_control(
          'icon_color',
          array(
            'label' => esc_html__('Icon Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'scheme' => array(
              'type' => Scheme_Color::get_type(),
              'value' => Scheme_Color::COLOR_3,
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action i' => 'color: {{VALUE}};',
              '{{WRAPPER}} .fel-modal-action svg' => 'fill: {{VALUE}};',
            ),
            'condition' => array(
              'modal_on' => 'icon',
            ),
          )
        );

        $this->add_control(
          'icon_hover_color',
          array(
            'label' => esc_html__('Icon Hover Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'scheme' => array(
              'type' => Scheme_Color::get_type(),
              'value' => Scheme_Color::COLOR_3,
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action i:hover' => 'color: {{VALUE}};',
              '{{WRAPPER}} .fel-modal-action svg:hover' => 'fill: {{VALUE}};',
            ),
            'condition' => array(
              'modal_on' => 'icon',
            ),
          )
        );

        $this->add_control(
          'photo',
          array(
            'label' => esc_html__('Image', 'fel'),
            'type' => Controls_Manager::MEDIA,
            'default' => array(
              'url' => Utils::get_placeholder_image_src(),
            ),
            'dynamic' => array(
              'active' => true,
            ),
            'condition' => array(
              'modal_on' => 'photo',
            ),
          )
        );

        $this->add_control(
          'img_size',
          array(
            'label' => esc_html__('Size', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 60,
            ),
            'range' => array(
              'px' => array(
                'max' => 500,
              ),
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action img' => 'width: {{SIZE}}px;',
            ),
            'condition' => array(
              'modal_on' => 'photo',
            ),
          )
        );

        $this->add_control(
          'modal_text',
          array(
            'label' => esc_html__('Text', 'fel'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Click Here', 'fel'),
            'dynamic' => array(
              'active' => true,
            ),
            'condition' => array(
              'modal_on' => 'text',
            ),
          )
        );

        $this->add_control(
          'modal_custom',
          array(
            'label' => esc_html__('Class', 'fel'),
            'type' => Controls_Manager::TEXT,
            'description' => esc_html__('Add your custom class without the dot. e.g: my-class', 'fel'),
            'condition' => array(
              'modal_on' => 'custom',
            ),
          )
        );

        $this->add_control(
          'modal_custom_id',
          array(
            'label' => esc_html__('Custom ID', 'fel'),
            'type' => Controls_Manager::TEXT,
            'description' => esc_html__('Add your custom id without the Pound key. e.g: my-id', 'fel'),
            'condition' => array(
              'modal_on' => 'custom_id',
            ),
          )
        );

        $this->add_control(
          'exit_intent',
          array(
            'label' => esc_html__('Exit Intent', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
            'condition' => array(
              'modal_on' => 'automatic',
            ),
            'selectors' => array(
              '.felmodal-{{ID}}' => '',
            ),
          )
        );

        $this->add_control(
          'after_second',
          array(
            'label' => esc_html__('After Few Seconds', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
            'condition' => array(
              'modal_on' => 'automatic',
            ),
            'selectors' => array(
              '.felmodal-{{ID}}' => '',
            ),
          )
        );

        $this->add_control(
          'after_second_value',
          array(
            'label' => esc_html__('Load After Seconds', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 1,
            ),
            'condition' => array(
              'after_second' => 'yes',
              'modal_on' => 'automatic',
            ),
            'selectors' => array(
              '.felmodal-{{ID}}' => '',
            ),
          )
        );

        $this->add_control(
          'enable_cookies',
          array(
            'label' => esc_html__('Enable Cookies', 'fel'),
            'type' => Controls_Manager::SWITCHER,
            'default' => 'no',
            'return_value' => 'yes',
            'label_off' => esc_html__('No', 'fel'),
            'label_on' => esc_html__('Yes', 'fel'),
            'condition' => array(
              'modal_on' => 'automatic',
            ),
            'selectors' => array(
              '.felmodal-{{ID}}' => '',
            ),
          )
        );

        $this->add_control(
          'close_cookie_days',
          array(
            'label' => esc_html__('Do Not Show After Closing (days)', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 1,
            ),
            'condition' => array(
              'enable_cookies' => 'yes',
              'modal_on' => 'automatic',
            ),
            'selectors' => array(
              '.felmodal-{{ID}}' => '',
            ),
          )
        );

        $this->add_control(
          'btn_text',
          array(
            'label' => esc_html__('Button Text', 'fel'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Click Me', 'fel'),
            'placeholder' => esc_html__('Click Me', 'fel'),
            'dynamic' => array(
              'active' => true,
            ),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_responsive_control(
          'btn_align',
          array(
            'label' => esc_html__('Alignment', 'fel'),
            'type' => Controls_Manager::CHOOSE,
            'options' => array(
              'left' => array(
                'title' => esc_html__('Left', 'fel'),
                'icon' => 'fa fa-align-left',
              ),
              'center' => array(
                'title' => esc_html__('Center', 'fel'),
                'icon' => 'fa fa-align-center',
              ),
              'right' => array(
                'title' => esc_html__('Right', 'fel'),
                'icon' => 'fa fa-align-right',
              ),
              'justify' => array(
                'title' => esc_html__('Justified', 'fel'),
                'icon' => 'fa fa-align-justify',
              ),
            ),
            'default' => 'left',
            'condition' => array(
              'modal_on' => 'button',
            ),
            'toggle' => false,
          )
        );

        $this->add_control(
          'btn_size',
          array(
            'label' => esc_html__('Size', 'fel'),
            'type' => Controls_Manager::SELECT,
            'default' => 'sm',
            'options' => array(
              'xs' => esc_html__('Extra Small', 'fel'),
              'sm' => esc_html__('Small', 'fel'),
              'md' => esc_html__('Medium', 'fel'),
              'lg' => esc_html__('Large', 'fel'),
              'xl' => esc_html__('Extra Large', 'fel'),
              'full' => esc_html__('Full width', 'fel'),
            ),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_responsive_control(
          'btn_padding',
          array(
            'label' => esc_html__('Padding', 'fel'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => array('px', 'em', '%'),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action-wrap a.elementor-button, {{WRAPPER}} .fel-modal-action-wrap .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        if ($this::is_elementor_updated()) {

          $this->add_control(
            'new_btn_icon',
            array(
              'label' => esc_html__('Icon', 'fel'),
              'type' => Controls_Manager::ICONS,
              'fa4compatibility' => 'btn_icon',
              'label_block' => true,
              'condition' => array(
                'modal_on' => 'button',
              ),
              'render_type' => 'template',
            )
          );
        } else {
          $this->add_control(
            'btn_icon',
            array(
              'label' => esc_html__('Icon', 'fel'),
              'type' => Controls_Manager::ICON,
              'label_block' => true,
              'condition' => array(
                'modal_on' => 'button',
              ),
            )
          );
        }

        if ($this::is_elementor_updated()) {
          $this->add_control(
            'btn_icon_align',
            array(
              'label' => esc_html__('Icon Position', 'fel'),
              'type' => Controls_Manager::SELECT,
              'default' => 'left',
              'options' => array(
                'left' => esc_html__('Before', 'fel'),
                'right' => esc_html__('After', 'fel'),
              ),
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'modal_on',
                    'operator' => '==',
                    'value' => 'button',
                  ),
                ),
              ),
            )
          );
        }
        else {
          $this->add_control(
            'btn_icon_align',
            array(
              'label' => esc_html__('Icon Position', 'fel'),
              'type' => Controls_Manager::SELECT,
              'default' => 'left',
              'options' => array(
                'left' => esc_html__('Before', 'fel'),
                'right' => esc_html__('After', 'fel'),
              ),
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'btn_icon',
                    'operator' => '!=',
                    'value' => '',
                  ),
                  array(
                    'name' => 'modal_on',
                    'operator' => '==',
                    'value' => 'button',
                  ),
                ),
              ),
            )
          );
        }

        if ($this::is_elementor_updated()) {
          $this->add_control(
            'btn_icon_indent',
            array(
              'label' => esc_html__('Icon Spacing', 'fel'),
              'type' => Controls_Manager::SLIDER,
              'range' => array(
                'px' => array(
                  'max' => 50,
                ),
              ),
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'modal_on',
                    'operator' => '==',
                    'value' => 'button',
                  ),
                ),
              ),
              'selectors' => array(
                '{{WRAPPER}} .fel-modal-action-wrap .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .fel-modal-action-wrap .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
              ),
            )
          );
        }
        else {
          $this->add_control(
            'btn_icon_indent',
            array(
              'label' => esc_html__('Icon Spacing', 'fel'),
              'type' => Controls_Manager::SLIDER,
              'range' => array(
                'px' => array(
                  'max' => 50,
                ),
              ),
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'btn_icon',
                    'operator' => '!=',
                    'value' => '',
                  ),
                  array(
                    'name' => 'modal_on',
                    'operator' => '==',
                    'value' => 'button',
                  ),
                ),
              ),
              'selectors' => array(
                '{{WRAPPER}} .fel-modal-action-wrap .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .fel-modal-action-wrap .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
              ),
            )
          );
        }

        $this->add_responsive_control(
          'all_align',
          array(
            'label' => esc_html__('Alignment', 'fel'),
            'type' => Controls_Manager::CHOOSE,
            'options' => array(
              'left' => array(
                'title' => esc_html__('Left', 'fel'),
                'icon' => 'fa fa-align-left',
              ),
              'center' => array(
                'title' => esc_html__('Center', 'fel'),
                'icon' => 'fa fa-align-center',
              ),
              'right' => array(
                'title' => esc_html__('Right', 'fel'),
                'icon' => 'fa fa-align-right',
              ),
            ),
            'default' => 'left',
            'condition' => array(
              'modal_on' => array('icon', 'photo', 'text'),
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action-wrap' => 'text-align: {{VALUE}};',
            ),
            'toggle' => false,
          )
        );

        $this->end_controls_section();
  }

  protected function title_style_controls()
      {

        $this->start_controls_section(
          'section_title_typography',
          array(
            'label' => esc_html__('Title', 'fel'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => array(
              'title!' => '',
            ),
          )
        );

        $this->add_responsive_control(
          'title_alignment',
          array(
            'label' => esc_html__('Alignment', 'fel'),
            'type' => Controls_Manager::CHOOSE,
            'options' => array(
              'left' => array(
                'title' => esc_html__('Left', 'fel'),
                'icon' => 'fa fa-align-left',
              ),
              'center' => array(
                'title' => esc_html__('Center', 'fel'),
                'icon' => 'fa fa-align-center',
              ),
              'right' => array(
                'title' => esc_html__('Right', 'fel'),
                'icon' => 'fa fa-align-right',
              ),
            ),
            'default' => 'left',
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-title-wrap' => 'text-align: {{VALUE}};',
            ),
            'toggle' => false,
          )
        );

        $this->add_responsive_control(
          'title_spacing',
          array(
            'label' => esc_html__('Padding', 'fel'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => array('px', 'em', '%'),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
            'default' => array(
              'top' => '15',
              'bottom' => '15',
              'left' => '25',
              'right' => '25',
              'unit' => 'px',
            ),
          )
        );

        $this->add_control(
          'title_color',
          array(
            'label' => esc_html__('Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'scheme' => array(
              'type' => Scheme_Color::get_type(),
              'value' => Scheme_Color::COLOR_1,
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-title-wrap .fel-modal-title' => 'color: {{VALUE}};',
              '{{WRAPPER}} .fel-modal-title-wrap .fel-modal-title' => 'color: {{VALUE}};',
            ),
          )
        );

        $this->add_control(
          'title_bg_color',
          array(
            'label' => esc_html__('Background Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'scheme' => array(
              'type' => Scheme_Color::get_type(),
              'value' => Scheme_Color::COLOR_2,
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-modal-title-wrap' => 'background-color: {{VALUE}};',
              '{{WRAPPER}} .fel-modal-title-wrap' => 'background-color: {{VALUE}};',
            ),
          )
        );

        $this->add_control(
          'title_tag',
          array(
            'label' => esc_html__('HTML Tag', 'fel'),
            'type' => Controls_Manager::SELECT,
            'options' => array(
              'h1' => esc_html__('H1', 'fel'),
              'h2' => esc_html__('H2', 'fel'),
              'h3' => esc_html__('H3', 'fel'),
              'h4' => esc_html__('H4', 'fel'),
              'h5' => esc_html__('H5', 'fel'),
              'h6' => esc_html__('H6', 'fel'),
              'div' => esc_html__('div', 'fel'),
              'span' => esc_html__('span', 'fel'),
              'p' => esc_html__('p', 'fel'),
            ),
            'default' => 'h3',
          )
        );

        $this->add_group_control(
          Group_Control_Typography::get_type(),
          array(
            'name' => 'title_typography',
            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            'selector' => '.felmodal-{{ID}} .fel-modal-title-wrap .fel-modal-title, {{WRAPPER}} .fel-modal-title-wrap .fel-modal-title',
          )
        );

        $this->end_controls_section();
  }

  protected function content_style_controls()
      {

        $this->start_controls_section(
          'section_content_typography',
          array(
            'label' => esc_html__('Content', 'fel'),
            'tab' => Controls_Manager::TAB_STYLE,
          )
        );

        $this->add_control(
          'content_text_color',
          array(
            'label' => esc_html__('Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'scheme' => array(
              'type' => Scheme_Color::get_type(),
              'value' => Scheme_Color::COLOR_3,
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-content' => 'color: {{VALUE}};',
              '{{WRAPPER}} .fel-content' => 'color: {{VALUE}};',
            ),
            'condition' => array(
              'content_type' => 'content',
            ),
          )
        );

        $this->add_control(
          'content_bg_color',
          array(
            'label' => esc_html__('Background Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => array(
              '.felmodal-{{ID}} .fel-content' => 'background-color: {{VALUE}};',
            ),
          )
        );

        $this->add_responsive_control(
          'modal_spacing',
          array(
            'label' => esc_html__('Content Padding', 'fel'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => array('px', 'em', '%'),
            'selectors' => array(
              '.felmodal-{{ID}} .fel-content .fel-modal-content-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
            'default' => array(
              'top' => '25',
              'bottom' => '25',
              'left' => '25',
              'right' => '25',
              'unit' => 'px',
            ),
          )
        );

        $this->add_control(
          'vplay_icon_header',
          array(
            'label' => esc_html__('Play Icon', 'fel'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'conditions' => array(
              'relation' => 'and',
              'terms' => array(
                array(
                  'name' => 'video_autoplay',
                  'operator' => '!=',
                  'value' => 'yes',
                ),
                array(
                  'name' => 'content_type',
                  'operator' => '==',
                  'value' => 'vimeo',
                ),
              ),
            ),
          )
        );

        if ($this::is_elementor_updated()) {

          $this->add_control(
            'new_vimeo_play_icon',
            array(
              'label' => esc_html__('Select Icon', 'fel'),
              'description' => esc_html__('Note: The Upload SVG option is not supported for the Vimeo play icon.', 'fel'),
              'type' => Controls_Manager::ICONS,
              'fa4compatibility' => 'vimeo_play_icon',
              'default' => array(
                'value' => 'fa fa-play-circle',
                'library' => 'fa-solid',
              ),
              'render_type' => 'template',
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'video_autoplay',
                    'operator' => '!=',
                    'value' => 'yes',
                  ),
                  array(
                    'name' => 'content_type',
                    'operator' => '==',
                    'value' => 'vimeo',
                  ),
                ),
              ),
            )
          );
        } else {
          $this->add_control(
            'vimeo_play_icon',
            array(
              'label' => esc_html__('Select Icon', 'fel'),
              'type' => Controls_Manager::ICON,
              'default' => 'fa fa-play-circle',
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'video_autoplay',
                    'operator' => '!=',
                    'value' => 'yes',
                  ),
                  array(
                    'name' => 'content_type',
                    'operator' => '==',
                    'value' => 'vimeo',
                  ),
                ),
              ),
            )
          );
        }

        $this->add_control(
          'vplay_size',
          array(
            'label' => esc_html__('Icon Size', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 72,
            ),
            'range' => array(
              'px' => array(
                'min' => 10,
                'max' => 200,
              ),
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .play' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
              '.felmodal-{{ID}} .play:before' => 'font-size: {{SIZE}}px; line-height: {{SIZE}}px;',
            ),
            'conditions' => array(
              'relation' => 'and',
              'terms' => array(
                array(
                  'name' => 'video_autoplay',
                  'operator' => '!=',
                  'value' => 'yes',
                ),
                array(
                  'name' => 'content_type',
                  'operator' => '==',
                  'value' => 'vimeo',
                ),
              ),
            ),
          )
        );

        $this->add_control(
          'vplay_color',
          array(
            'label' => esc_html__('Icon Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba( 0,0,0,0.8 )',
            'selectors' => array(
              '.felmodal-{{ID}} .play:before' => 'color: {{VALUE}};',
            ),
            'conditions' => array(
              'relation' => 'and',
              'terms' => array(
                array(
                  'name' => 'video_autoplay',
                  'operator' => '!=',
                  'value' => 'yes',
                ),
                array(
                  'name' => 'content_type',
                  'operator' => '==',
                  'value' => 'vimeo',
                ),
              ),
            ),
          )
        );

        $this->add_control(
          'yplay_icon_header',
          array(
            'label' => esc_html__('Play Icon', 'fel'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
            'conditions' => array(
              'relation' => 'and',
              'terms' => array(
                array(
                  'name' => 'video_autoplay',
                  'operator' => '!=',
                  'value' => 'yes',
                ),
                array(
                  'name' => 'content_type',
                  'operator' => '==',
                  'value' => 'youtube',
                ),
              ),
            ),
          )
        );

        if ($this::is_elementor_updated()) {

          $this->add_control(
            'new_youtube_play_icon',
            array(
              'label' => esc_html__('Select Icon', 'fel'),
              'description' => esc_html__('Note: The Upload SVG option is not supported for the YouTube play icon.', 'fel'),
              'type' => Controls_Manager::ICONS,
              'fa4compatibility' => 'youtube_play_icon',
              'default' => array(
                'value' => 'fa fa-play-circle',
                'library' => 'fa-solid',
              ),
              'render_type' => 'template',
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'video_autoplay',
                    'operator' => '!=',
                    'value' => 'yes',
                  ),
                  array(
                    'name' => 'content_type',
                    'operator' => '==',
                    'value' => 'youtube',
                  ),
                ),
              ),
            )
          );
        } else {
          $this->add_control(
            'youtube_play_icon',
            array(
              'label' => esc_html__('Select Icon', 'fel'),
              'type' => Controls_Manager::ICON,
              'default' => 'fa fa-play-circle',
              'conditions' => array(
                'relation' => 'and',
                'terms' => array(
                  array(
                    'name' => 'video_autoplay',
                    'operator' => '!=',
                    'value' => 'yes',
                  ),
                  array(
                    'name' => 'content_type',
                    'operator' => '==',
                    'value' => 'youtube',
                  ),
                ),
              ),
            )
          );
        }

        $this->add_control(
          'yplay_size',
          array(
            'label' => esc_html__('Icon Size', 'fel'),
            'type' => Controls_Manager::SLIDER,
            'default' => array(
              'size' => 72,
            ),
            'range' => array(
              'px' => array(
                'min' => 10,
                'max' => 200,
              ),
            ),
            'selectors' => array(
              '.felmodal-{{ID}} .play' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
              '.felmodal-{{ID}} .play:before' => 'font-size: {{SIZE}}px; line-height: {{SIZE}}px;',
            ),
            'conditions' => array(
              'relation' => 'and',
              'terms' => array(
                array(
                  'name' => 'video_autoplay',
                  'operator' => '!=',
                  'value' => 'yes',
                ),
                array(
                  'name' => 'content_type',
                  'operator' => '==',
                  'value' => 'youtube',
                ),
              ),
            ),
          )
        );

        $this->add_control(
          'yplay_color',
          array(
            'label' => esc_html__('Icon Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba( 0,0,0,0.8 )',
            'selectors' => array(
              '.felmodal-{{ID}} .play:before' => 'color: {{VALUE}};',
            ),
            'conditions' => array(
              'relation' => 'and',
              'terms' => array(
                array(
                  'name' => 'video_autoplay',
                  'operator' => '!=',
                  'value' => 'yes',
                ),
                array(
                  'name' => 'content_type',
                  'operator' => '==',
                  'value' => 'youtube',
                ),
              ),
            ),
          )
        );

        $this->add_control(
          'loader_color',
          array(
            'label' => esc_html__('Iframe Loader Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba( 0,0,0,0.8 )',
            'selectors' => array(
              '.felmodal-{{ID}} .fel-loader::before' => 'border: 3px solid {{VALUE}}; border-left-color: transparent;border-right-color: transparent;',
            ),
            'conditions' => array(
              'relation' => 'and',
              'terms' => array(
                array(
                  'name' => 'async_iframe',
                  'operator' => '==',
                  'value' => 'yes',
                ),
                array(
                  'name' => 'content_type',
                  'operator' => '==',
                  'value' => 'iframe',
                ),
              ),
            ),
          )
        );

        $this->add_group_control(
          Group_Control_Typography::get_type(),
          array(
            'name' => 'content_typography',
            'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            'selector' => '.felmodal-{{ID}} .fel-content .fel-text-editor',
            'separator' => 'before',
            'condition' => array(
              'content_type' => 'content',
            ),
          )
        );

        $this->end_controls_section();
  }

  protected function button_style_controls()
      {

        $this->start_controls_section(
          'section_button_style',
          array(
            'label' => esc_html__('Button', 'fel'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_group_control(
          Group_Control_Typography::get_type(),
          array(
            'name' => 'btn_typography',
            'label' => esc_html__('Typography', 'fel'),
            'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            'selector' => '{{WRAPPER}} .fel-modal-action-wrap a.elementor-button, {{WRAPPER}} .fel-modal-action-wrap .elementor-button',
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
          'tab_button_normal',
          array(
            'label' => esc_html__('Normal', 'fel'),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_control(
          'button_text_color',
          array(
            'label' => esc_html__('Text Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action-wrap a.elementor-button, {{WRAPPER}} .fel-modal-action-wrap .elementor-button' => 'color: {{VALUE}};',
            ),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_group_control(
          Group_Control_Background::get_type(),
          array(
            'name' => 'btn_background_color',
            'label' => esc_html__('Background Color', 'fel'),
            'types' => array('classic', 'gradient'),
            'selector' => '{{WRAPPER}} .fel-modal-action-wrap .elementor-button',
            'separator' => 'before',
            'condition' => array(
              'modal_on' => 'button',
            ),
            'fields_options' => array(
              'color' => array(
                'scheme' => array(
                  'type' => Scheme_Color::get_type(),
                  'value' => Scheme_Color::COLOR_4,
                ),
              ),
            ),
          )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
          'tab_button_hover',
          array(
            'label' => esc_html__('Hover', 'fel'),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_control(
          'btn_hover_color',
          array(
            'label' => esc_html__('Text Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .fel-modal-action-wrap .elementor-button:hover' => 'color: {{VALUE}};',
            ),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_control(
          'button_background_hover_color',
          array(
            'label' => esc_html__('Background Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'scheme' => array(
              'type' => Scheme_Color::get_type(),
              'value' => Scheme_Color::COLOR_4,
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .fel-modal-action-wrap .elementor-button:hover' => 'background-color: {{VALUE}};',
            ),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_control(
          'button_hover_border_color',
          array(
            'label' => esc_html__('Border Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'condition' => array(
              'border_border!' => '',
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action-wrap a.elementor-button:hover, {{WRAPPER}} .fel-modal-action-wrap .elementor-button:hover' => 'border-color: {{VALUE}};',
            ),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_control(
          'btn_hover_animation',
          array(
            'label' => esc_html__('Hover Animation', 'fel'),
            'type' => Controls_Manager::HOVER_ANIMATION,
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
          Group_Control_Border::get_type(),
          array(
            'name' => 'btn_border',
            'label' => esc_html__('Border', 'fel'),
            'placeholder' => '1px',
            'default' => '1px',
            'selector' => '{{WRAPPER}} .fel-modal-action-wrap .elementor-button',
            'separator' => 'before',
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_control(
          'btn_border_radius',
          array(
            'label' => esc_html__('Border Radius', 'fel'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => array('px', '%'),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action-wrap a.elementor-button, {{WRAPPER}} .fel-modal-action-wrap .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->add_group_control(
          Group_Control_Box_Shadow::get_type(),
          array(
            'name' => 'button_box_shadow',
            'selector' => '{{WRAPPER}} .fel-modal-action-wrap .elementor-button',
            'condition' => array(
              'modal_on' => 'button',
            ),
          )
        );

        $this->end_controls_section();
  }

  protected function cta_style_controls()
      {

        $this->start_controls_section(
          'section_cta_style',
          array(
            'label' => esc_html__('Display Text', 'fel'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => array(
              'modal_on' => 'text',
            ),
          )
        );

        $this->add_control(
          'text_color',
          array(
            'label' => esc_html__('Text Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'scheme' => array(
              'type' => Scheme_Color::get_type(),
              'value' => Scheme_Color::COLOR_3,
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action' => 'color: {{VALUE}};',
            ),
            'condition' => array(
              'modal_on' => 'text',
            ),
          )
        );

        $this->add_control(
          'text_hover_color',
          array(
            'label' => esc_html__('Text Hover Color', 'fel'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'scheme' => array(
              'type' => Scheme_Color::get_type(),
              'value' => Scheme_Color::COLOR_3,
            ),
            'selectors' => array(
              '{{WRAPPER}} .fel-modal-action:hover' => 'color: {{VALUE}};',
            ),
            'condition' => array(
              'modal_on' => 'text',
            ),
          )
        );

        $this->add_group_control(
          Group_Control_Typography::get_type(),
          array(
            'name' => 'cta_text_typography',
            'label' => esc_html__('Typography', 'fel'),
            'scheme' => Scheme_Typography::TYPOGRAPHY_4,
            'selector' => '{{WRAPPER}} .fel-modal-action-wrap .fel-modal-action',
            'condition' => array(
              'modal_on' => 'text',
            ),
          )
        );

        $this->end_controls_section();
  }

  public function get_content_type()
      {

        $content_type = array(
          'content' => esc_html__('Content', 'fel'),
          'photo' => esc_html__('Photo', 'fel'),
          'video' => esc_html__('Video Embed Code', 'fel'),
          'saved_rows' => esc_html__('Saved Section', 'fel'),
          'saved_page_templates' => esc_html__('Saved Page', 'fel'),
          'youtube' => esc_html__('YouTube', 'fel'),
          'vimeo' => esc_html__('Vimeo', 'fel'),
          'iframe' => esc_html__('Iframe', 'fel'),
        );

        if (defined('ELEMENTOR_PRO_VERSION')) {
          $content_type['saved_modules'] = esc_html__('Saved Widget', 'fel');
        }

        return $content_type;
  }

  public function get_modal_content($settings, $node_id)
      {

        $content_type = $settings['content_type'];
        $dynamic_settings = $this->get_settings_for_display();

        switch ($content_type) {
          case 'content':
            global $wp_embed;
            return '<div class="fel-text-editor elementor-inline-editing" data-elementor-setting-key="ct_content" data-elementor-inline-editing-toolbar="advanced">' . wpautop($wp_embed->autoembed($dynamic_settings['ct_content'])) . '</div>';
            break;
          case 'photo':
            if (isset($dynamic_settings['ct_photo']['url'])) {
              return '<img src="' . $dynamic_settings['ct_photo']['url'] . '" alt="' . Control_Media::get_image_alt($dynamic_settings['ct_photo']) . '" />';
            }
            return '<img src="" alt="" />';
            break;

          case 'video':
            global $wp_embed;
            return $wp_embed->autoembed($dynamic_settings['ct_video']);
            break;
          case 'iframe':
            if ('yes' === $dynamic_settings['async_iframe']) {

              return '<div class="fel-modal-content-type-iframe" data-src="' . $dynamic_settings['iframe_url'] . '" frameborder="0" allowfullscreen></div>';
            } else {
              return '<iframe src="' . $dynamic_settings['iframe_url'] . '" class="fel-content-iframe" frameborder="0" width="100%" height="100%" allowfullscreen></iframe>';
            }
            break;
          case 'saved_rows':
            return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display(apply_filters('wpml_object_id', $settings['ct_saved_rows'], 'page'));
          case 'saved_modules':
            return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($settings['ct_saved_modules']);
          case 'saved_page_templates':
            return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($settings['ct_page_templates']);
            break;
          case 'youtube':
          case 'vimeo':
            return $this->get_video_embed($dynamic_settings, $node_id);
          default:
            return;
            break;
        }
  }

  public function get_video_embed($settings, $node_id)
      {

        if ('' === $settings['video_url']) {
          return '';
        }

        $url = $settings['video_url'];
        $vid_id = '';
        $html = '<div class="fel-video-wrap">';

        $embed_param = $this->get_embed_params();
        $video_data = $this->get_url($embed_param, $node_id);

        $params = array();

        $play_icon = '';
        if ('youtube' === $settings['content_type']) {
          if ($this::is_elementor_updated()) {

            $youtube_migrated = isset($settings['__fa4_migrated']['new_youtube_play_icon']);
            $youtube_is_new = !isset($settings['youtube_play_icon']);

            if ($youtube_is_new || $youtube_migrated) {
              $play_icon = $settings['new_youtube_play_icon']['value'];
            } else {
              $play_icon = $settings['youtube_play_icon'];
            }
          } else {
            $play_icon = $settings['youtube_play_icon'];
          }
        } else {

          if ($this::is_elementor_updated()) {

            $vimeo_migrated = isset($settings['__fa4_migrated']['new_vimeo_play_icon']);
            $vimeo_is_new = !isset($settings['vimeo_play_icon']);

            if ($vimeo_is_new || $vimeo_migrated) {
              $play_icon = $settings['new_vimeo_play_icon']['value'];
            } else {
              $play_icon = $settings['vimeo_play_icon'];
            }
          } else {
            $play_icon = $settings['vimeo_play_icon'];
          }
        }

        if ('youtube' === $settings['content_type']) {

          if (preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches)) {
            $vid_id = $matches[1];
          }

          $thumb = 'https://i.ytimg.com/vi/' . $vid_id . '/hqdefault.jpg';

          $html .= '<div class="fel-modal-iframe fel-video-player" data-src="youtube" data-id="' . $vid_id . '" data-thumb="' . $thumb . '" data-sourcelink="https://www.youtube.com/embed/' . $vid_id . $video_data . '" data-play-icon="' . $play_icon . '"></div>';

        } elseif ('vimeo' === $settings['content_type']) {

          $vid_id = preg_replace('/[^\/]+[^0-9]|(\/)/', '', rtrim($url, '/'));

          if ('' !== $vid_id && 0 !== $vid_id) {

            // @codingStandardsIgnoreStart
            $vimeo = unserialize(@file_get_contents("https://vimeo.com/api/v2/video/$vid_id.php"));
            // @codingStandardsIgnoreEnd

            $thumb = $vimeo[0]['thumbnail_large'];

            $html .= '<div class="fel-modal-iframe fel-video-player" data-src="vimeo" data-id="' . $vid_id . '" data-thumb="' . $thumb . '" data-sourcelink="https://player.vimeo.com/video/' . $vid_id . $video_data . '" data-play-icon="' . $play_icon . '" ></div>';
          }
        }
        $html .= '</div>';
        return $html;
  }

  public function render_button($node_id, $settings)
      {

        $this->add_render_attribute('wrapper', 'class', 'fel-button-wrapper elementor-button-wrapper');
        $this->add_render_attribute('button', 'href', 'javascript:void(0);');
        $this->add_render_attribute('button', 'class', 'fel-trigger elementor-button-link elementor-button elementor-clickable');

        if (!empty($settings['btn_size'])) {
          $this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['btn_size']);
        }

        if (!empty($settings['btn_align'])) {
          $this->add_render_attribute('wrapper', 'class', 'elementor-align-' . $settings['btn_align']);
          $this->add_render_attribute('wrapper', 'class', 'elementor-tablet-align-' . $settings['btn_align_tablet']);
          $this->add_render_attribute('wrapper', 'class', 'elementor-mobile-align-' . $settings['btn_align_mobile']);
        }

        if ($settings['btn_hover_animation']) {
          $this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['btn_hover_animation']);
        }

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
          <a <?php echo $this->get_render_attribute_string('button'); ?> data-modal="<?php echo $node_id; ?>">
            <?php $this->render_button_text(); ?>
          </a>
        </div>
        <?php
  }

  protected function render_button_text()
      {

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('content-wrapper', 'class', 'elementor-button-content-wrapper');
        $this->add_render_attribute(
          'icon-align',
          'class',
          array(
            'elementor-align-icon-' . $settings['btn_icon_align'],
            'elementor-button-icon',
          )
        );

        $this->add_render_attribute(
          'btn-text',
          array(
            'class' => 'elementor-button-text elementor-inline-editing',
            'data-elementor-setting-key' => 'btn_text',
            'data-elementor-inline-editing-toolbar' => 'none',
          )
        );

        ?>
        <span <?php echo $this->get_render_attribute_string('content-wrapper'); ?>>

    			<?php
          if ($this::is_elementor_updated()) {

            $button_migrated = isset($settings['__fa4_migrated']['new_btn_icon']);
            $button_is_new = !isset($settings['btn_icon']);
            ?>
            <?php if (!empty($settings['btn_icon']) || !empty($settings['new_btn_icon'])) : ?>
              <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
    						<?php
                if ($button_is_new || $button_migrated) {
                  \Elementor\Icons_Manager::render_icon($settings['new_btn_icon'], array('aria-hidden' => 'true'));
                } elseif (!empty($settings['btn_icon'])) {
                  ?>
                  <i class="<?php echo esc_attr($settings['btn_icon']); ?>" aria-hidden="true"></i>
                <?php } ?>
    					</span>
            <?php endif; ?>
            <?php
          } elseif (!empty($settings['btn_icon'])) {
            ?>
            <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
    					<i class="<?php echo esc_attr($settings['btn_icon']); ?>" aria-hidden="true"></i>
    				</span>
          <?php } ?>
          <span <?php echo $this->get_render_attribute_string('btn-text'); ?> ><?php echo $this->get_settings_for_display('btn_text'); ?></span>
    		</span>
        <?php
  }

  protected function render_close_icon()
      {

        $settings = $this->get_settings_for_display();
        ?>

        <span
          class="fel-modal-close fel-close-icon elementor-clickable fel-close-custom-<?php echo $settings['icon_position']; ?>">
    		<?php
        if ('icon' === $settings['close_source']) {
          if ($this::is_elementor_updated()) {
            $close_migrated = isset($settings['__fa4_migrated']['new_close_icon']);
            $close_is_new = !isset($settings['close_icon']);

            if ($close_is_new || $close_migrated) {
              \Elementor\Icons_Manager::render_icon($settings['new_close_icon'], array('aria-hidden' => 'true'));
            } elseif (!empty($settings['close_icon'])) {
              ?>
              <i class="<?php echo $settings['close_icon']; ?>" aria-hidden="true"></i>
              <?php
            }
          } elseif (!empty($settings['close_icon'])) {
            ?>
            <i class="<?php echo $settings['close_icon']; ?>" aria-hidden="true"></i>
            <?php
          }
        } else {
          ?>
          <img class="fel-close-image"
               src="<?php echo (isset($settings['close_photo']['url'])) ? $settings['close_photo']['url'] : ''; ?>"
               alt="<?php echo (isset($settings['close_photo']['url'])) ? Control_Media::get_image_alt($settings['close_photo']) : ''; ?>"/>
          <?php
        }
        ?>
    		</span>
        <?php
  }

  protected function render_action_html()
      {

        $settings = $this->get_settings_for_display();

        $is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

        if ('button' === $settings['modal_on']) {

          $this->render_button($this->get_id(), $settings);

        } elseif (
          (
            'custom' === $settings['modal_on'] ||
            'custom_id' === $settings['modal_on'] ||
            'automatic' === $settings['modal_on'] ||
            'via_url' === $settings['modal_on']
          ) &&
          $is_editor
        ) {

          ?>
          <div class="fel-builder-msg" style="text-align: center;">
            <h5><?php _e('Modal Popup - ID ', 'fel'); ?><?php echo $this->get_id(); ?></h5>
            <p><?php _e('Click here to edit the "Modal Popup" settings. This text will not be visible on frontend.', 'fel'); ?></p>
          </div>
          <?php

        } else {

          $inner_html = '';

          $this->add_render_attribute(
            'action-wrap',
            'class',
            array(
              'fel-modal-action',
              'elementor-clickable',
              'fel-trigger',
            )
          );

          if ('custom' === $settings['modal_on'] ||
            'custom_id' === $settings['modal_on'] ||
            'automatic' === $settings['modal_on'] ||
            'via_url' === $settings['modal_on']
          ) {
            $this->add_render_attribute('action-wrap', 'class', 'fel-modal-popup-hide');
          }

          $this->add_render_attribute('action-wrap', 'data-modal', $this->get_id());

          switch ($settings['modal_on']) {
            case 'text':
              $this->add_render_attribute(
                'action-wrap',
                array(
                  'data-elementor-setting-key' => 'modal_text',
                  'data-elementor-inline-editing-toolbar' => 'basic',
                )
              );

              $this->add_render_attribute('action-wrap', 'class', 'elementor-inline-editing');

              $inner_html = $settings['modal_text'];

              break;

            case 'icon':
              $this->add_render_attribute('action-wrap', 'class', 'fel-modal-icon-wrap fel-modal-icon');

              if ($this::is_elementor_updated()) {

                $icon_migrated = isset($settings['__fa4_migrated']['new_icon']);
                $icon_is_new = !isset($settings['icon']);
                if ($icon_is_new || $icon_migrated) {
                  ob_start();
                  \Elementor\Icons_Manager::render_icon($settings['new_icon'], array('aria-hidden' => 'true'));
                  $inner_html = ob_get_clean();
                } elseif (!empty($settings['icon'])) {
                  $inner_html = '<i class="' . $settings['icon'] . '" aria-hidden="true"></i>';
                }
              } elseif (!empty($settings['icon'])) {
                $inner_html = '<i class="' . $settings['icon'] . '" aria-hidden="true"></i>';
              }

              break;

            case 'photo':
              $this->add_render_attribute('action-wrap', 'class', 'fel-modal-photo-wrap');

              $url = (isset($settings['photo']['url']) && !empty($settings['photo']['url'])) ? $settings['photo']['url'] : '';

              $inner_html = '<img class="fel-modal-photo" src="' . $url . '" alt="' . Control_Media::get_image_alt($settings['photo']) . '">';

              break;
          }
          ?>
          <div <?php echo $this->get_render_attribute_string('action-wrap'); ?>>
            <?php echo $inner_html; ?>
          </div>

          <?php
        }
  }

  public function get_parent_wrapper_attributes($settings)
      {

        $this->add_render_attribute(
          'parent-wrapper',
          array(
            'id' => $this->get_id() . '-overlay',
            'data-trigger-on' => $settings['modal_on'],
            'data-close-on-esc' => $settings['esc_keypress'],
            'data-close-on-overlay' => $settings['overlay_click'],
            'data-exit-intent' => $settings['exit_intent'],
            'data-after-sec' => $settings['after_second'],
            'data-after-sec-val' => $settings['after_second_value']['size'],
            'data-cookies' => $settings['enable_cookies'],
            'data-cookies-days' => $settings['close_cookie_days']['size'],
            'data-custom' => $settings['modal_custom'],
            'data-custom-id' => $settings['modal_custom_id'],
            'data-content' => $settings['content_type'],
            'data-autoplay' => $settings['video_autoplay'],
            'data-device' => (false !== (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) ? 'true' : 'false'),
            'data-async' => ('yes' === $settings['async_iframe']) ? true : false,
          )
        );

        $this->add_render_attribute(
          'parent-wrapper',
          'class',
          array(
            'fel-modal-parent-wrapper',
            'fel-module-content',
            'felmodal-' . $this->get_id(),
            'fel-aspect-ratio-' . $settings['video_ratio'],
            $settings['_css_classes'] . '-popup',
          )
        );

        return $this->get_render_attribute_string('parent-wrapper');
  }

  public function get_saved_data($type = 'page')
      {
        $saved_widgets = $this->get_post_template($type);
        $options[-1] = esc_html__('Select', 'fel');
        if (count($saved_widgets)) {
          foreach ($saved_widgets as $saved_row) {
            $content_id = $saved_row['id'];
            $content_id = apply_filters('wpml_object_id', $content_id);
            $options[$content_id] = $saved_row['name'];
          }
        } else {
          $options['no_template'] = esc_html__('It seems that, you have not saved any template yet.', 'fel');
        }
        return $options;
  }

  public function get_post_template($type = 'page')
      {
        $posts = get_posts(
          array(
            'post_type' => 'elementor_library',
            'orderby' => 'title',
            'order' => 'ASC',
            'posts_per_page' => '-1',
            'tax_query' => array(
              array(
                'taxonomy' => 'elementor_library_type',
                'field' => 'slug',
                'terms' => $type,
              ),
            ),
          )
        );

        $templates = array();

        foreach ($posts as $post) {

          $templates[] = array(
            'id' => $post->ID,
            'name' => $post->post_title,
          );
        }

        return $templates;
  }

  public function get_embed_params()
      {

        $settings = $this->get_settings();

        $params = array();

        if ('youtube' === $settings['content_type']) {
          $youtube_options = array('rel', 'controls', 'mute', 'modestbranding');

          $params['version'] = 3;
          $params['enablejsapi'] = 1;

          $params['autoplay'] = ('yes' === $settings['video_autoplay']) ? 1 : 0;

          foreach ($youtube_options as $option) {

            if ('rel' === $option) {
              $params[$option] = ('yes' === $settings['youtube_related_videos']) ? 1 : 0;
              continue;
            }

            if ('controls' === $option) {
              if ('yes' === $settings['youtube_player_controls']) {
                $params[$option] = 0;
              }
              continue;
            }

            if ('yes' === $settings['video_controls_adv']) {
              $value = ('yes' === $settings['yt_' . $option]) ? 1 : 0;
              $params[$option] = $value;
              $params['start'] = $settings['start'];
              $params['end'] = $settings['end'];
            }
          }
        }

        if ('vimeo' === $settings['content_type']) {

          if ('yes' === $settings['video_controls_adv'] && 'yes' === $settings['vimeo_loop']) {
            $params['loop'] = 1;
          }

          $params['title'] = 0;
          $params['byline'] = 0;
          $params['portrait'] = 0;
          $params['badge'] = 0;

          if ('yes' === $settings['video_autoplay']) {
            $params['autoplay'] = 1;
            $params['muted'] = 1;
          } else {
            $params['autoplay'] = 0;
          }
        }
        return $params;
  }

  public function get_url($params, $node_id)
      {

        $settings = $this->get_settings_for_display();
        $url = '';

        $url = add_query_arg($params, $url);

        $url .= (empty($params)) ? '?' : '&';

        if ('vimeo' === $settings['content_type']) {

          if ('yes' === $settings['video_controls_adv']) {
            if ('' !== $settings['start']) {

              $time = gmdate('H\hi\ms\s', $settings['start']);
              $url .= '#t=' . $time;
            }
          }
        }

        return $url;
  }

  protected function render()
      {

        $settings = $this->get_settings();
        $node_id = $this->get_id();
        $is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

        $this->add_inline_editing_attributes('ct_content', 'advanced');
        $this->add_inline_editing_attributes('title', 'basic');
        $this->add_inline_editing_attributes('modal_text', 'basic');
        $this->add_inline_editing_attributes('btn_text', 'none');

        $this->add_render_attribute('inner-wrapper', 'id', 'modal-' . $node_id);

        $this->add_render_attribute(
          'inner-wrapper',
          'class',
          array(
            'fel-modal',
            'fel-center-modal',
            'fel-modal-custom',
            'fel-modal-' . $settings['content_type'],
            $settings['modal_effect'],
            ($is_editor) ? 'fel-modal-editor' : '',
            'fel-aspect-ratio-' . $settings['video_ratio'],
          )
        );

        ?>
        <div <?php echo $this->get_parent_wrapper_attributes($settings); ?>>
          <div <?php echo $this->get_render_attribute_string('inner-wrapper'); ?>>
            <div class="fel-content">
              <?php
              if (
                (
                  ('icon' === $settings['close_source'] && (!empty($settings['close_icon']) || !empty($settings['new_close_icon']))) ||
                  ('img' === $settings['close_source'] && '' !== $settings['close_photo']['url'])
                ) &&
                (
                  'top-left' !== $settings['icon_position'] &&
                  'top-right' !== $settings['icon_position']
                )
              ) {
                $this->render_close_icon();
              }
              if ('' !== $settings['title']) {
              ?>
              <div class="fel-modal-title-wrap">
                <<?php echo $settings['title_tag']; ?> class="fel-modal-title elementor-inline-editing"
                data-elementor-setting-key="title"
                data-elementor-inline-editing-toolbar="basic"><?php echo $this->get_settings_for_display('title'); ?></<?php echo $settings['title_tag']; ?>
              >
            </div>
            <?php } ?>
            <div class="fel-modal-text fel-modal-content-data clearfix">
              <?php echo do_shortcode($this->get_modal_content($settings, $node_id)); ?>
            </div>
          </div>
        </div>

        <?php
        if (
          (
            ('icon' === $settings['close_source'] && (!empty($settings['close_icon']) || !empty($settings['new_close_icon']))) ||
            ('img' === $settings['close_source'] && '' !== $settings['close_photo'])
          ) &&
          (
            'top-left' === $settings['icon_position'] ||
            'top-right' === $settings['icon_position']
          )
        ) {
          $this->render_close_icon();
        }
        ?>
        <div class="fel-overlay"></div>
        </div>

        <div class="fel-modal-action-wrap">
          <?php echo $this->render_action_html(); ?>
        </div> <?php        
    }    

  protected function is_elementor_updated()
      {
        if (class_exists('Elementor\Icons_Manager')) {
          return true;
        } else {
          return false;
        }
  }

}






