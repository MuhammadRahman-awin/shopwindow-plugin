<?php
/*
Plugin Name: Datafeed
Description: A plugin with DI
Version: 1.0.0
License: GPL-2.0+
*/

use Datafeed\Plugin;
use Datafeed\SettingsPage;
use Datafeed\DBAdapter;
use Datafeed\Processor;
use Datafeed\Printer;
use Datafeed\OptionHandler;
use Datafeed\UploadErrorHandler;

spl_autoload_register( 'datafeed_autoloader' );
function datafeed_autoloader( $class_name ) {
	if ( false !== strpos( $class_name, 'Datafeed' ) ) {
		$classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
		$class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name ) . '.php';
		require_once $classes_dir . $class_file;
	}
}

add_action( 'plugins_loaded', 'datafeed_init' ); // Hook initialization function
function datafeed_init() {
	$plugin = new Plugin(); // Create container
	$plugin['path'] = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
	$plugin['url'] = plugin_dir_url( __FILE__ );
	$plugin['version'] = '1.0.0';
	$plugin['settings_page_properties'] = array(
		'parent_slug'       => 'datafeed-settings',
		'page_title'        => 'Datafeed',
		'menu_title'        => 'Datafeed',
		'sub_menu_title'    => 'Settings',
		'help_menu_title'   => 'Datafeed Guide',
		'help_menu_slug'    => 'data-feed-guide',
		'capability'        => 'manage_options',
		'menu_slug'         => 'datafeed-settings',
		'option_group'      => 'datafeed_option_group',
		'option_name'       => 'datafeed_option_name',
		'icon'              => plugins_url( 'icon.png' , __FILE__),
	);
	$plugin['settings_page'] = function ( $plugin ) {
		return new SettingsPage( $plugin['upload_error_handler'], $plugin['processor'], $plugin['settings_page_properties'] );
	};

	$plugin['option_handler'] = function ( $plugin ) {
		return new OptionHandler(array());
	};

	$plugin['db_adapter'] = function ( $plugin ) {
		return new DBAdapter($plugin['option_handler']);
	};

	$plugin['printer'] = function ( $plugin ) {
		return new Printer();
	};

	$plugin['processor'] = function ( $plugin ) {
		return new Processor($plugin['db_adapter'], $plugin['printer']);
	};

	$plugin['upload_error_handler'] = function ( $plugin ) {
		return new UploadErrorHandler(array());
	};


	$plugin->run();
}

