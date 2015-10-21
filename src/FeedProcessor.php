<?php

require_once("DataFeedDBConnection.php");
require_once("WidgetPrinter.php");

class FeedProcessor
{
    /** @var  string */
    private $layout;

    /**
     * @param string $layout */
    private $title = "Shop Window Products";

    /** @var  integer */
    private $productCount = 5;

    /** @var  DataFeedDBConnection */
    private $db;

	/** @var  WidgetPrinter */
	private $printer;

    public function __construct()
    {
        $this->db = new DataFeedDBConnection();
	    $this->printer = new WidgetPrinter();
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
        $this->layout = $layout;
    }

	/**
	 * @return string
	 */
    public function displayWidget()
    {
	    $data = $this->getProducts();
	    var_dump($data[0]);
        if ($this->layout) {
            return $this->printer->verticalWidget($data);
        }

        return $this->printer->horizontalWidget($data);
    }

	/**
	 * @return array
	 */
	private function getProducts()
	{
		return $this->db->getLimitedRows($this->productCount);
	}
}
