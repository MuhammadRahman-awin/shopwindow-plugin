<?php
require_once('FeedProcessor.php');
require_once('DataFeedDBConnection.php');

add_action( 'wp_ajax_get_sw_product', 'get_sw_product' );
add_action( 'wp_ajax_nopriv_get_sw_product', 'get_sw_product' );

function get_sw_product()
{
//    var_dump($_REQUEST);
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


add_action( 'wp_ajax_track_user_click', 'track_user_click' );
add_action( 'wp_ajax_nopriv_track_user_click', 'track_user_click' );

function track_user_click()
{
    $db = new DataFeedDBConnection();
    $row = array(
        'clickIp' => getUserIp(),
        'clickDateTime' => current_time( 'mysql' ),
        'feedId'    => $_REQUEST['feedId']
    );
    $db->saveAnalytics($row);
}


function getUserIp()
{
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return apply_filters( 'wpb_get_ip', $ip );
}
