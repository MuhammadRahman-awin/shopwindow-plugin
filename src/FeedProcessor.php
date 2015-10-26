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
            return $this->printer->verticalWidget($data);
        }

        return $this->printer->horizontalWidget($data);
    }

    /**
     * @return array
     */
    private function getProducts()
    {
        $data = $this->db->getLimitedRows(20);
        $products = $this->getProductWithImage($data);

        return $products;
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
        }
        $productWithImage = array_slice($productWithImage, 0, $this->productCount);

        return $productWithImage;
    }
}
