<?php


class WidgetPrinter
{
    public function verticalWidget(array $data)
    {
        $productList = '<div class="vertical">';
        foreach($data as $product) {
            $productList .= '

                <a href='.$product['awDeepLink'].' target="_blank"> <img style="width:100px;height:100px;" src='.$product['merchantImageUrl'].' /></a>';
        }
        $productList .= '</div>';
        return $productList;
    }

    public function horizontalWidget(array $data)
    {
        $productList = '<table class="horizontal">';
        foreach($data as $product) {
            $productList .= '
                    <tr>
                        <td class="image"><a href='.$product['awDeepLink'].' target="_blank">
                        	<img src='.$product['merchantImageUrl'].' /></a>
                        </td>
                        <td class="name" rowspan="2">'. $product['productName'].'</td>
					</tr>
					<tr>
						<td class="price">'. $product['price'].'</td>
                    </tr>
            ';
        }
        $productList .= '</table>';
        return $productList;
        var_dump($data);
    }
}
