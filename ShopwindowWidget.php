<?php
/*
Plugin Name: ShopWindow product
Version: 1.0
Plugin URI: https://wordpress.org/plugins/shopwindow-product
Description: Sell your affiliate product from shopwindow product feed
Author: digitalwindow
Author URI: http://mmrs151.tumblr.com/
*/

class ShopwindowWidget extends WP_Widget
{
    public function __construct()
    {
        $widget_details = array(
            'className' => 'ShopwindowWidget',
            'description' => 'Sell your affiliate product from shopwindow product feed'
        );
        $this->add_stylesheet();
        parent::__construct('ShopwindowWidget', 'Shopwindow product feed', $widget_details);
    }

    public function form($instance)
    {

    }

    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

    public function widget($args, $instance)
    {
        echo 'hello world';
    }

    public function add_stylesheet() {
        wp_register_style( 'shopwindow-style', plugins_url('assets/styles.css', __FILE__) );
        wp_enqueue_style( 'shopwindow-style' );
    }
}

add_action('widgets_init', 'init_shopwindow_widget');
function init_shopwindow_widget()
{
    register_widget('ShopwindowWidget');
}

add_action( 'admin_menu', "shopwindow_settings");
function shopwindow_settings(){
    add_menu_page (
        'Shopwindow',
        'Shopwindow',
        'manage_options',
        'shopwindow-plugin/ShopwindowAdmin.php',
        '',
        plugins_url( 'shopwindow-plugin/icon.png' ),
        79
    );
}
