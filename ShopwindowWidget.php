<?php
/*
Plugin Name: ShopWindow feed
Version: 1.0
Plugin URI: https://wordpress.org/plugins/shopwindow-feed
Description: Sell your affiliate product from shopwindow product feed
Author: mmrs151
Author URI: http://mmrs151.tumblr.com/
*/

require_once('src/FeedProcessor.php');

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
                <tr><td>Number of product to display</td>
                    <td>
                        <input
                            name="<?php echo $this->get_field_name( 'displayCount' ); ?>"
                            type="number" min=1 step=1 value=5
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

        echo $args['after_widget'];

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

###############################################
# MENU PAGES #
###############################################
add_action( 'admin_menu', "shopwindow_settings");
function shopwindow_settings(){
    add_menu_page (
        'Shopwindow',
        'Shopwindow',
        'manage_options',
        'shopwindow-feed/ShopwindowAdmin.php',
        '',
        plugins_url( 'shopwindow-feed/icon.png' )
    );
    add_submenu_page('shopwindow-feed/ShopwindowAdmin.php', 'Settings', 'Settings', 'manage_options', 'shopwindow-feed/ShopwindowAdmin.php');
    add_submenu_page('shopwindow-feed/ShopwindowAdmin.php', 'Data Feed Guide', 'Data Feed Guide', 'manage_options', 'data-feed-guide', 'data_feed_guide');

    function data_feed_guide()
    {
        include(plugin_dir_path( __FILE__ ) . 'src/data-feed-guide.php');
    }
}


##########################################################
# DEACTIVATION #
##########################################################
register_deactivation_hook( __FILE__, 'datafeedUninstall' );
function datafeedUninstall() {

    global $wpdb;
    $table = $wpdb->prefix."datafeed";

    $wpdb->query("DROP TABLE IF EXISTS $table");
}
