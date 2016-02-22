<?php
/*
Plugin Name: Awin Data Feed
Version: 1.0
Plugin URI: https://wordpress.org/plugins/awin-data-feed
Description: Sell your affiliate product from affiliate window product data feed
Author: digitalwindow
Author URI: http://mmrs151.wordpress.com/
*/

require_once('src/FeedProcessor.php');
require_once('src/sw-ajax.php');
require_once('src/OptionHandler.php');
require_once('src/ShortCodeHandler.php');

class AwinDataFeedWidget extends WP_Widget
{
    public function __construct()
    {
        $widget_details = array(
            'className' => 'AwinDataFeedWidget',
            'description' => 'Sell your affiliate product from Affiliate Window product data feed'
        );
        $this->add_stylesheet();
        $this->setScript();

        parent::__construct('AwinDataFeedWidget', 'Affiliate Window data feed', $widget_details);
    }

    public function form($instance)
    {
        ?>
        <div xmlns="http://www.w3.org/1999/html">
        <span>
            <table border="0">
                <tr><td>Title</td>
                    <td>
                        <input
                            name="<?php echo $this->get_field_name( 'title' ); ?>"
                            type="text"
                            value="<?php echo $instance["title"] ?>"
                        />
                    </td>
                </tr>
                <tr><td>Keywords</td>
                    <td>
                        <input
                            name="<?php echo $this->get_field_name( 'keywords' ); ?>"
                            type="text" placeholder="comma separated"
                            value="<?php echo $instance["keywords"] ?>"
                        />
                    </td>
                </tr>
                <tr><td>Number of product to display</td>
                    <td>
                        <input
                            name="<?php echo $this->get_field_name( 'displayCount' ); ?>"
                            type="number" min=1 step=1
                            value="<?php echo $instance["displayCount"] ?>"
                        />
                    </td>
                </tr>
                <tr><td>Display product horizontally</td>
                    <td>
                        <input
                            type="checkbox"
                            name="<?php echo $this->get_field_name( 'layout' ); ?>"
                            value="horizontal"
                            <?php if($instance["layout"] === 'horizontal'){ echo 'checked="checked"'; } ?>
                        />
                    </td>
                </tr>
            </table>
        </span>
        </div>

        <div class='mfc-text'>
        </div>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];


        $layout = $instance['layout'];
        $layout = empty($layout) ? 'vertical' : $layout;
        $layout = ucfirst($layout);

        echo '
        <form name="swFeed" id="swFeed'.$layout.'">
            <input name="title" type="hidden" value="' .$instance['title'].'"/>
            <input name="displayCount" type="hidden" value="' .$instance['displayCount'].'"/>
            <input name="layout" type="hidden" value="' .$instance['layout'].'"/>
            <input name="keywords" type="hidden" value="' .$instance['keywords'].'"/>
            <input name="action" type="hidden" value="get_sw_product"/>
        </form>
        <div class="widgetContent">
            <div class="ajaxResponse'.$layout.'" id="ajaxResponse'.$layout.'"></div>
            <div class="next'.$layout.'"><button id="next'.$layout.'" class="next" style="display:none"></button></div>
        </div>';

        echo $args['after_widget'];

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

add_action('widgets_init', 'init_awindatafeed_widget');
function init_awindatafeed_widget()
{
    register_widget('AwinDataFeedWidget');
}
############################# END OF WIDGET ############################################

##############################################
# SHORT CODES
##############################################

$shortCode = new ShortCodeHandler();
add_shortcode('AWIN_DATA_FEED', array($shortCode, 'initShortCode'));

############################# END OF SHORT CODES ############################################

#####################################################
# MENU PAGES #
#####################################################
add_action( 'admin_menu', "awin_data_feed_settings");
function awin_data_feed_settings(){
    add_menu_page (
        'Awin Data Feed',
        'Awin Data Feed',
        'manage_options',
        'awin-data-feed/AwinDataFeedAdmin.php',
        '',
        plugins_url( 'icon.png' , __FILE__)
    );
    add_submenu_page('awin-data-feed/AwinDataFeedAdmin.php', 'Settings', 'Settings', 'manage_options', 'awin-data-feed/AwinDataFeedAdmin.php');
    add_submenu_page('awin-data-feed/AwinDataFeedAdmin.php', 'Data Feed Guide', 'Data Feed Guide', 'manage_options', 'data-feed-guide', 'data_feed_guide');

    function data_feed_guide()
    {
        include(plugin_dir_path( __FILE__ ) . 'src/data-feed-guide.php');
    }
}


##########################################################
# ADMIN NOTIFICATION #
##########################################################
function my_admin_notice() {
    $fp = new FeedProcessor();
    if(! $fp->hasFeedInDb()) {
    ?>
        <div class="update-nag">
            <p><?php _e( '<a href="'.admin_url('admin.php?page=awin-data-feed/AwinDataFeedAdmin.php').'">Import  your affiliate window data feed to display in widget!</a>', 'my-text-domain' ); ?></p>
        </div>
    <?php
    }
}
add_action( 'admin_notices', 'my_admin_notice' );


##########################################################
# DEACTIVATION #
##########################################################
register_deactivation_hook( __FILE__, 'datafeedUninstall' );
function datafeedUninstall() {

    global $wpdb;
    $table = $wpdb->prefix."datafeed";
    $tableAnalytics = $wpdb->prefix."datafeed_analytics";

    delete_sw_options();

    $wpdb->query("DROP TABLE IF EXISTS $table");
    $wpdb->query("DROP TABLE IF EXISTS $tableAnalytics");
}