<?php
require_once('FeedProcessor.php');

add_action( 'wp_ajax_get_sw_product', 'get_sw_product' );
add_action( 'wp_ajax_nopriv_get_sw_product', 'get_sw_product' );

function get_sw_product()
{
    $title = $_REQUEST['title'];
    $count = $_REQUEST['displayCount'];
    $layout = $_REQUEST['layout'];

    $feedProcessor = new FeedProcessor();

    if (! empty($title)) {
        $feedProcessor->setTitle($title);
    }
    if (! empty($count)) {
        $feedProcessor->setProductCount($count);
    }
    if ($layout === 'horizontal') {
        $feedProcessor->setLayout($layout);
    }

    echo $feedProcessor->displayWidget();

	die();
}
