<?php


class WidgetPrinter
{
    /**
     * @param $title
     * @param array $data
     *
     * @return string
     */
    public function horizontalWidget($title, array $data)
    {
        $productList = '
            <table class="horizontal">
                <tr><th class="title" colspan="'.count($data).';">'. $title.'</th></tr>
                <tr class="image">';
        foreach($data as $product) {
            $productList .= '
                <td class="hover image">
                    <a class="trackImage-'.$product['id'].'" href='.$product['awDeepLink'].' target="_blank" alt="'. $product['productName'].'" title="'. $product['productName'].'">
                        <img src='.$product['merchantImageUrl'].' />
                    </a>
                </td>
            ';
        }

        $productList .= '
                </tr>
                <tr class="name">';
        foreach($data as $product) {
            $productList .= '
                <td class="name">
                    '. $product['productName'].'
                </td>
            ';
        }

        $productList .= '
                </tr>
                <tr class="price">';
        foreach($data as $product) {
            $productList .= '
                <td class="price">'. $this->getCurrencySymbol($product['currency']).''.number_format($product['price'], 2).'</td>
            ';
        }
        $productList .= '
                </tr>';
        return $productList;
    }

    /**
     * @param $title
     * @param array $data
     *
     * @return string
     */
    public function verticalWidget($title, array $data)
    {
        $productList = '<table class="vertical">
                            <tr><th class="title" colspan="2">'. $title.'</th></tr>';
        foreach($data as $product) {
            $productList .= '
                    <tr class="image">
                        <td class="hover image">
                            <a class="trackImage-'.$product['id'].'" href='.$product['awDeepLink'].' target="_blank" alt="'. $product['productName'].'" title="'. $product['productName'].'">
                                <img src='.$product['merchantImageUrl'].' />
                            </a>
                        </td>
                        <td class="description" rowspan="2">
                            '. substr($product['description'], 0, 130).'
                                ...
                        </td>
                    </tr>
                    <tr>
                        <td class="price">'. $this->getCurrencySymbol($product['currency']).''.number_format($product['price'], 2).'</td>
                    </tr>
            ';
        }
        $productList .= '</table>';

        return $productList;
    }

	/**
	 * @param Let$name
	 * @return string
	 */
    private function getCurrencySymbol($name)
    {
        if (strtoupper($name) === 'GBP') {
            return "&pound";
        } elseif(strtoupper($name) === 'USD') {
            return "$";
        }
    }
}
