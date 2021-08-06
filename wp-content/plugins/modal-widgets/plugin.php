<?php

namespace Modal_Widgets;


class Plugin
{

  private static $_instance = null;

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function widget_scripts()
  {
    wp_register_script('jquery-resize', plugins_url('/assets/js/jquery_resize.js', __FILE__), ['jquery'], false, true);
    wp_register_script('js-cookie', plugins_url('/assets/js/js_cookie.js', __FILE__), ['jquery'], false, true);
    wp_register_script('modal-script', plugins_url('/assets/js/modal.js', __FILE__), ['jquery'], false, true);
  }

  public function widget_styles()
  {
    wp_register_style('font-awesome', ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/font-awesome.min.css', __FILE__);
    wp_register_style('modal-widgets-style', plugins_url('/assets/css/modal.css', __FILE__));
  }

  private function include_widgets_files()
  {
    require_once(__DIR__ . '/widgets/modal.php');
  }

  public function register_widgets()
  {
    $this->include_widgets_files();
    $this->reflection = new \ReflectionClass( $this );

    $class_name = $this->reflection->getNamespaceName() . '\Widgets\\' . 'Modal';

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new $class_name());
  }

  public static function tf_admin_enqueue_scripts( $hook ) {

    // Register styles.
    wp_register_style(
      'tf-style',
      plugins_url('/assets/css/admin/styles.css', __FILE__),
      array(),
      null
    );

    wp_enqueue_style( 'tf-style' );
  }

  public static function load_admin() {
    add_action( 'elementor/editor/after_enqueue_styles', __CLASS__ . '::tf_admin_enqueue_scripts' );
  }

  public function __construct()
  {
    add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);

    add_action('elementor/frontend/after_register_styles', [$this, 'widget_styles']);

    add_action('elementor/preview/enqueue_styles', function () {
      wp_enqueue_style('modal_widgets_style');
    });

    add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
    add_action( 'elementor/init', __CLASS__ . '::load_admin', 0 );
  }

}

Plugin::instance();