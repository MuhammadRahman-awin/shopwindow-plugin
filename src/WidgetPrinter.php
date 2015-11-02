<?php


class WidgetPrinter
{
    public function horizontalWidget(array $data)
    {
        $productList = '<div class="vertical">';
        foreach($data as $product) {
            $productList .= '

                <a href='.$product['awDeepLink'].' target="_blank"> <img style="width:100px;height:100px;" src='.$product['merchantImageUrl'].' /></a>';
        }
        $productList .= '</div>';
        return $productList;
    }

    public function verticalWidget($title, array $data)
    {
        $productList = '<table class="vertical">
                            <tr><th class="title" colspan="2">'. $title.'</th></tr>';
        foreach($data as $product) {
            $productList .= '
                    <tr class="image">
                        <td class="image">
                            <a href='.$product['awDeepLink'].' target="_blank" alt="'. $product['productName'].'" title="'. $product['productName'].'">
                                <img src='.$product['merchantImageUrl'].' />
                            </a>
                        </td>
                        <td class="description" rowspan="2">
                            '. substr($product['description'], 0, 130).'
                            <a href='.$product['awDeepLink'].' target="_blank" alt="'. $product['productName'].'" title="'. $product['productName'].'">
                                ...more
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="price">'. $this->getCurrencySymbol($product['currency']).''.$product['price'].'</td>
                    </tr>
            ';
        }
        $productList .= '<tr><td colspan="2" class="next"><span id="next" class="next"> next > </span></td></tr>';
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
