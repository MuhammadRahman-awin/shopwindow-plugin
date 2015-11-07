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
        if ($this->layout) {
            return $this->printer->horizontalWidget($this->title, $data);
        }

        return $this->printer->verticalWidget($this->title, $data);
    }

    /**
     * @return array
     */
    private function getProducts()
    {
        $data = $this->db->getLimitedRows(10);
        $products = $this->getProductWithImage($data);

        return $products;
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

    public function getFreeDeliveryProducts()
    {
        return $this->db->getProductCountByFreeDeliveryCost();
    }

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
     * @param array $products
     * @return array
     */
    private function getProductWithImage($products)
    {
        $productWithImage = array();
        $handle = curl_init();

        foreach($products as $product) {
            curl_setopt($handle, CURLOPT_URL, $product['merchantImageUrl']);
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
            curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if($httpCode == 200) {
                $productWithImage[] = $product;
            }

            if (count($productWithImage) === (int)$this->productCount) {
                return $productWithImage;
            }
        }
    }
}
