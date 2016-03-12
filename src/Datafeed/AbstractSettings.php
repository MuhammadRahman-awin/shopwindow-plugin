<?php
namespace Datafeed;

use Datafeed\Processor;
use Datafeed\UploadErrorHandler;
use Datafeed\Importer;
use Datafeed\OptionHandler;
use Datafeed\DBAdapter;

abstract class AbstractSettings
{
	/** @var  array */
	protected $settings_page_properties;

	/** @var  Processor */
	protected $processor;

	/** @var  UploadErrorHandler */
	protected $errorHandler;

	/** @var  Importer */
	protected $importer;

	/** @var  OptionHandler */
	protected $optionHandler;

	/** * @var DBAdapter */
	protected $adapter;


	/**
	 * @param \Datafeed\DBAdapter $adapter
	 * @param OptionHandler $optionHandler
	 * @param Importer $importer
	 * @param UploadErrorHandler $handler
	 * @param Processor $processor
	 * @param array $settings_page_properties
	 */
	public function __construct(
		DBAdapter $adapter,
		OptionHandler $optionHandler,
		Importer $importer,
		UploadErrorHandler $handler,
		Processor $processor,
		array $settings_page_properties
	) {
		$this->adapter = $adapter;
		$this->optionHandler = $optionHandler;
		$this->importer = $importer;
		$this->errorHandler = $handler;
		$this->processor = $processor;
		$this->settings_page_properties = $settings_page_properties;
	}

	public function run()
	{
		add_action( 'admin_menu', array( $this, 'add_menu_and_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_notices', array( $this, 'datafeed_admin_notice' ) );

	}

	public function add_menu_and_page()
	{
		add_menu_page(
			$this->settings_page_properties['page_title'],
			$this->settings_page_properties['menu_title'],
			$this->settings_page_properties['capability'],
			$this->settings_page_properties['menu_slug'],
			array( $this, 'render_settings_page' ),
			$this->settings_page_properties['icon']
		);
		add_submenu_page(
			$this->settings_page_properties['parent_slug'],
			$this->settings_page_properties['sub_menu_title'],
			$this->settings_page_properties['sub_menu_title'],
			$this->settings_page_properties['capability'],
			$this->settings_page_properties['menu_slug']
		);
		add_submenu_page(
			$this->settings_page_properties['parent_slug'],
			$this->settings_page_properties['help_menu_title'],
			$this->settings_page_properties['help_menu_title'],
			$this->settings_page_properties['capability'],
			$this->settings_page_properties['help_menu_slug'],
			array( $this, 'render_guide' )
		);
	}

	public function register_settings()
	{
		register_setting(
			$this->settings_page_properties['option_group'],
			$this->settings_page_properties['option_name']
		);
	}

	public function datafeed_admin_notice() {
		if(! $this->processor->hasFeedInDb()) {
			?>
			<div class="update-nag">
				<p><?php _e( '<a href="'.admin_url('admin.php?page=datafeed-settings').'">Import  your affiliate window data feed to display in widget!</a>', 'my-text-domain' ); ?></p>
			</div>
			<?php
		}
	}

	public function get_settings_data()
	{
		return get_option( $this->settings_page_properties['option_name'], $this->get_default_settings_data() );
	}

	public function render_settings_page() {}

	public function render_guide() {}

}
