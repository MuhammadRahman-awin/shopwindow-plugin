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
		register_widget('Datafeed\Widget');
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

	private function add_stylesheet()
	{
		wp_register_style( 'awindatafeed-style', plugins_url('../../assets/aw-styles.css', __FILE__) );
		wp_enqueue_style( 'awindatafeed-style' );
	}

	private function setScript()
	{
		// Get the Path to this plugin's folder
		$path = plugin_dir_url( __FILE__ );

		// Enqueue our script
		wp_enqueue_script( 'awindatafeed',
			$path. '../../assets/awindatafeed.js',
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
