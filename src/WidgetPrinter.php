<?php


class WidgetPrinter
{
	public function verticalWidget(array $data)
	{
		$productList = '<div class="vertical">';
		foreach($data as $product) {
			$productList .= '
				<a href='.$product['awDeepLink'].' target="_blank"> <img width="100" height="100" src='.$product['merchantImageUrl'].' /></a>';
		}
		$productList .= '</div>';
		return $productList;
	}

	public function horizontalWidget(array $data)
	{
		$productList = '<div class="horizontal">';
		foreach($data as $product) {
			$productList .= '
			<p>
				<a href='.$product['awDeepLink'].' target="_blank"> <img width="100" height="100" src='.$product['merchantImageUrl'].' /></a>
			</p>';
		}
		$productList .= '</div>';
		return $productList;
		var_dump($data);
	}
}
