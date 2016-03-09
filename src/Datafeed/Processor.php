<?php

namespace Datafeed;

use Datafeed\DBAdapter;
use Datafeed\Printer;

class Processor
{
	/** @var  string */
	private $layout='vertical';

	/**
	 * @param string $layout */
	private $title = "Shop Window Products";

	/** @var  integer */
	private $productCount = 5;

	/** @var  DBAdapter */
	private $db;

	/** @var  Printer */
	private $printer;

	/** @var  array */
	private $keywords;

	/**
	 * @param DBAdapter $adapter
	 * @param Printer $printer
	 */
	public function __construct(DBAdapter $adapter, Printer $printer)
	{
		$this->db = $adapter;
		$this->printer = $printer;
	}

	/**
	 * @param int $productCount
	 */
	public function setProductCount( $productCount ) {
		$this->productCount = $productCount;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * @param string $layout
	 */
	public function setLayout( $layout ) {
		if (! empty($layout)) {
			$this->layout = $layout;
		}
	}

	public function setKeywords($keywords)
	{
		$this->keywords = $keywords;
	}

	/**
	 * @return string
	 */
	public function displayWidget()
	{
		$data = $this->getProducts();
		if (empty($data)) {
			return '<p class="info">No product found with image and description</p>';
		}

		return $this->printer->getWidget($this->layout, $this->title, $data);
	}

	/**
	 * @return bool
	 */
	public function hasFeedInDb()
	{
		return $this->db->countFeedInDb() > 0;
	}

	/**
	 * @return bool
	 */
	public function getFeedCount()
	{
		return $this->db->countFeedInDb();
	}

	/**
	 * @return mixed
	 */
	public function getFreeDeliveryProducts()
	{
		return $this->db->getProductCountByFreeDeliveryCost();
	}

	/**
	 * @return mixed
	 */
	public function getProductCountByCategory()
	{
		return $this->db->getProductCountByCategory();
	}

	/**
	 * @param integer $price
	 *
	 * @return mixed
	 */
	public function getProductCountForPrice($price)
	{
		return $this->db->getProductCountByPrice($price);
	}

	/**
	 * @return array
	 */
	private function getProducts()
	{
		$data = $this->db->getLimitedRows((int)$this->productCount, $this->keywords);

		return $data;
	}
}
