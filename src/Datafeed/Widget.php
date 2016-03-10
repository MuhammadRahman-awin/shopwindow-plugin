<?php

namespace Datafeed;

class Widget extends \WP_Widget
{
	public function __construct()
	{
		$widget_details = array(
			'className' => 'Widget',
			'description' => 'Sell your affiliate product from Affiliate Window product data feed'
		);
		$this->add_stylesheet();
		$this->setScript();

		parent::__construct('Widget', 'Affiliate Window data feed', $widget_details);
	}

	public function run()
	{
		add_action('widgets_init', array( $this, 'init_datafeed_widget' ));
	}

	public function init_datafeed_widget()
	{
		register_widget('Widget');
	}

	private function add_stylesheet() {
		wp_register_style( 'awindatafeed-style', plugins_url('assets/aw-styles.css', __FILE__) );
		wp_enqueue_style( 'awindatafeed-style' );
	}

	private function setScript()
	{
		// Get the Path to this plugin's folder
		$path = plugin_dir_url( __FILE__ );

		// Enqueue our script
		wp_enqueue_script( 'awindatafeed',
			$path. 'assets/awindatafeed.js',
			array( 'jquery' ),
			'1.0.0', true );

		// Get the protocol of the current page
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';

		// Set the ajaxurl Parameter which will be output right before
		// our ajax-delete-posts.js file so we can use ajaxurl
		$params = array(
			// Get the url to the admin-ajax.php file using admin_url()
			'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
		);
		// Print the script to our page
		wp_localize_script( 'awindatafeed', 'awindatafeed_params', $params );
	}
}
