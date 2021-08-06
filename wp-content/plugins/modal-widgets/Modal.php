<?php
/*
Plugin Name: Modal Popup Widgets for Elementor
Description: Modal Popup Widgets for Elementor allows to WP Cute
Author: Themes Flat
Author URI: https://codecanyon.net/user/themesflat
Version: 1.0
Text Domain: fel
Domain Path: /languages
License: GNU General Public License v3.0
*/

if (!defined('ABSPATH'))
  exit;

final class Modal_Widgets
{

  const VERSION = '1.0.0';
  const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
  const MINIMUM_PHP_VERSION = '5.2';


  public function __construct()
  {
    add_action('init', array(
      $this,
      'i18n'
    ));
    add_action('plugins_loaded', array(
      $this,
      'init'
    ));
    define('URL_PLUGIN_MD_ELEMENTOR', plugins_url('/', __FILE__));
  }

  public function i18n()
  {
    load_plugin_textdomain('fel', false, basename(dirname(__FILE__)) . '/languages');
  }

  public function init()
  {
    if (!did_action('elementor/loaded')) {
      add_action('admin_notices', array(
        $this,
        'admin_notice_missing_main_plugin'
      ));
      return;
    }

    if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
      add_action('admin_notices', array(
        $this,
        'admin_notice_minimum_elementor_version'
      ));
      return;
    }

    if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
      add_action('admin_notices', array(
        $this,
        'admin_notice_minimum_php_version'
      ));
      return;
    }
    require_once('plugin.php');

    add_action('elementor/elements/categories_registered', function () {

      $elementsManager = \Elementor\Plugin::instance()->elements_manager;

      $elementsManager->add_category(
        'themesflat_addons',
        array(
          'title' => 'THEMESFLAT ADDONS',
          'icon' => 'fonts',
        ));
    });
  }

  public function admin_notice_missing_main_plugin()
  {
    if (isset($_GET['activate'])) {
      unset($_GET['activate']);
    }

    $message = sprintf(('"%1$s" requires "%2$s" to be installed and activated.'), '<strong>' . esc_attr('Modal Popup Widgets for Elementor', 'fel') . '</strong>', '<strong>' . esc_attr('Elementor', 'fel') . '</strong>');

    printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
  }

  public function admin_notice_minimum_elementor_version()
  {
    if (isset($_GET['activate'])) {
      unset($_GET['activate']);
    }

    $message = sprintf(('"%1$s" requires "%2$s" version %3$s or greater.'), '<strong>' . esc_attr('Modal Popup Widgets for Elementor', 'fel') . '</strong>', '<strong>' . esc_attr('Elementor', 'fel') . '</strong>', self::MINIMUM_ELEMENTOR_VERSION);

    printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
  }

  public function admin_notice_minimum_php_version()
  {
    if (isset($_GET['activate'])) {
      unset($_GET['activate']);
    }

    $message = sprintf(('"%1$s" requires "%2$s" version %3$s or greater.'), '<strong>' . esc_attr('Modal Popup Widgets for Elementor', 'fel') . '</strong>', '<strong>' . esc_attr('PHP', 'fel') . '</strong>', self::MINIMUM_PHP_VERSION);
    printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
  }


}

new Modal_Widgets();