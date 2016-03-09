<?php
namespace Datafeed;

abstract class AbstractSubPage
{
	/** @var  array */
	protected $settings_page_properties;

	/** @var  Processor */
	protected $processor;

	/**
	 * @param Processor $processor
	 * @param array $settings_page_properties
	 */
	public function __construct(Processor $processor, array $settings_page_properties )
	{
		$this->settings_page_properties = $settings_page_properties;
		$this->processor = $processor;
	}

	public function run()
	{
		add_action( 'admin_menu', array( $this, 'add_menu_and_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
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

	public function get_settings_data()
	{
		return get_option( $this->settings_page_properties['option_name'], $this->get_default_settings_data() );
	}

	public function render_settings_page() {}

	public function render_guide() {}

}
