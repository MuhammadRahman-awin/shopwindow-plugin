<?php
/*
Plugin Name: Datafeed
Description: A plugin with DI
Version: 1.0.0
License: GPL-2.0+
*/

use Datafeed\PluginContainer;

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
	$container = new PluginContainer(); // Create container
	$container['path'] = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
	$container['url'] = plugin_dir_url( __FILE__ );
	$container['version'] = '1.0.0';
	$container['settings_page_properties'] = array(
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
	$container['settings_page'] = function ( $container ) {
		return new Datafeed\SettingsMenu(
			$container['option_handler'],
			$container['importer'],
			$container['upload_error_handler'],
			$container['processor'],
			$container['settings_page_properties']
		);
	};

	$container['option_handler'] = function ( $container ) {
		return new \Datafeed\OptionHandler(array());
	};

	$container['db_adapter'] = function ( $container ) {
		return new \Datafeed\DBAdapter($container['option_handler']);
	};

	$container['widget_printer'] = function ( $container ) {
		return new \Datafeed\WidgetPrinter();
	};

	$container['processor'] = function ( $container ) {
		return new \Datafeed\Processor($container['db_adapter'], $container['widget_printer']);
	};

	$container['upload_error_handler'] = function ( $container ) {
		return new \Datafeed\UploadErrorHandler(array());
	};

	$container['importer'] = function ( $container ) {
		return new \Datafeed\Importer( $container['db_adapter']);
	};

	$container['shortcode_handler'] = function ( $container ) {
		return new \Datafeed\ShortcodeHandler();
	};

	$container['widget'] = function ( $container ) {
		return new \Datafeed\Widget();
	};

	$container['ajax_handler'] = function ( $container ) {
		return new \Datafeed\AjaxHandler($container['processor'], $container['db_adapter']);
	};

	$container->run();
}

