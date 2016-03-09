<?php

namespace Datafeed;

class OptionHandler
{
	/**
	 * @var array
	 */
	private $data;

	/**
	 * @param array $data
	 */
	public function __construct(array $data=null)
	{
		$this->data = $data;
	}

	public function updateOptions()
	{
		$deliveryMethod = sanitize_text_field($this->data['deliveryMethod']);
		delete_option('sw_deliveryMethod');
		add_option('sw_deliveryMethod', $deliveryMethod);

		$categories = $this->data['categories'];
		delete_option('sw_categories');
		add_option('sw_categories', $categories);

		$maxPriceRadio = sanitize_text_field($this->data['maxPriceRadio']);
		delete_option('sw_maxPriceRadio');
		add_option('sw_maxPriceRadio', $maxPriceRadio);

		if ($maxPriceRadio == 'range') {
			$minPrice = sanitize_text_field($this->data['minPrice']);
			$minPrice = intval($minPrice);
			delete_option('sw_minPrice');
			add_option('sw_minPrice', $minPrice);

			$maxPrice = sanitize_text_field($this->data['maxPrice']);
			$maxPrice = intval($maxPrice);
			delete_option('sw_maxPrice');
			add_option('sw_maxPrice', $maxPrice);
		} else {
			delete_option('sw_minPrice');
			delete_option('sw_maxPrice');
		}

	}

	public function delete_sw_options()
	{
		delete_option('sw_deliveryMethod');
		delete_option('sw_categories');
		delete_option('sw_maxPriceRadio');
		delete_option('sw_minPrice');
		delete_option('sw_maxPrice');
	}
}
