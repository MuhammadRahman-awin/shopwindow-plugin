<?php
require_once('FeedProcessor.php');

add_action( 'wp_ajax_get_sw_product', 'get_sw_product' );
add_action( 'wp_ajax_nopriv_get_sw_product', 'get_sw_product' );

function get_sw_product()
{
    $feedProcessor = new FeedProcessor();

    if (! empty($instance['title'])) {
        $feedProcessor->setTitle($instance['title']);
    }
    if (! empty($instance['displayCount'])) {
        $feedProcessor->setProductCount($instance['displayCount']);
    }
    if ($instance['layout'] === 'horizontal') {
        $feedProcessor->setLayout($instance['layout']);
    }

    echo $feedProcessor->displayWidget();

	die();
}
