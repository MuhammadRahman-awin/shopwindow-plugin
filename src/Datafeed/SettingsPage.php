<?php
namespace Datafeed;

class SettingsPage extends AbstractSubPage
{
	public function render_settings_page()
	{
		$max_file_size = ini_get('upload_max_filesize');

		if ( ! empty( $_POST ) && check_admin_referer( 'sw_admin_option' ) ) {

			if (isset($_POST['submit']) && ! empty($_FILES["dataFeed"])) {
				$this->errorHandler->handleError($_FILES["dataFeed"]);
				if (! $this->errorHandler->valid) {
					echo "<h3 class='error center'>Failed! </br>". $this->errorHandler->message . "</br></h3>";
				} else {
					$csvImporter = new CSVImporter($_FILES["dataFeed"]["tmp_name"]);
					$csvImporter->importToTable();
					$count = count(file($_FILES["dataFeed"]["tmp_name"]));
					echo "<h3 class='info center'>Success! </br>$count Row processed</br></h3>";
				}
			}
		}

		if ( ! empty( $_POST ) && check_admin_referer( 'sw_admin_option' ) ) {

			if (! empty($_POST['filterOptions'])) {
				$optionHandler = new OptionHandler($_POST);
				$optionHandler->updateOptions();
			}
		}

		?>

		<section class="wrap">
			<h2>Import your shopwindow data feed to display in widget</h2>
			<h3 class="info">Maximum file size must be smaller than: <?php echo $max_file_size ?>B </h3>
			<p>[Update 'upload_max_filesize' directive in php.ini for larger import]</p>

			<form enctype="multipart/form-data" name="csvUpload" method="post" action="">
				<?php echo wp_nonce_field( 'sw_admin_option'); ?>
				<h3>
            <span>
                <input type="file" name="dataFeed" id="dataFeed">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Import Data Feed">
            </span>
				</h3>
			</form>
		</section>

		<?php
		if($this->processor->hasFeedInDb()) {
			?>
			<div class="productCount">
				<div class="count"> You have <span class="counter"><?= $this->processor->getFeedCount() ?></span> products in your data store to choose from</h1> </div>
			</div>
			<section>
				<div class="options">
					<div class="form">
						<form name="swFilters" id="swFilters"  method="post">
							<?php echo wp_nonce_field( 'sw_admin_option'); ?>
							<table class="aw-filter" cellspacing="0" cellpadding="0">
								<tr>
									<th colspan="2" class="title">
										<h2>Filter products*</h2>
									</th>
								</tr>
								<tr>
									<td colspan="2">
										(DEFAULT) Randomly display product in random order.
									</td>
								</tr>
								<tr>
									<th colspan="2" class="filterType">By Delivery Type</th></tr>
								<tr>
									<td><input
											<?php if(get_option('sw_deliveryMethod') == 'free'){ echo 'checked="checked"'; } ?>
											type="checkbox" name="deliveryMethod" value="free" id="deliveryMethod">Free Delivery</td>
									<td>
										(<?= $this->processor->getFreeDeliveryProducts() ?>)
									</td>
								</tr>

								<?php
								if(count($this->processor->getProductCountByCategory()) > 1) {
									echo '<tr><th colspan="2" class="filterType">By Category</th></tr>';

									foreach($this->processor->getProductCountByCategory() as $category) {
										?>
										<tr>
											<td>
												<input
													<?php if(is_array(get_option('sw_categories')) && in_array($category['categoryName'], get_option('sw_categories') ))
													{ echo 'checked="checked"'; } ?>
													type="checkbox" name="categories[]"
													value="<?=$category['categoryName']?>"><?=$category['categoryName']?>
											</td>
											<td>
												<?=$category['count']?>
											</td>
										</tr>
										<?php
									}
								}
								?>
								<tr><th colspan="2" class="filterType">By price</th></tr>
								<tr>
									<td><input <?php if(get_option('sw_maxPriceRadio') == '10'){ echo 'checked="checked"';} ?>
											class="maxPriceRadio" type="radio" name="maxPriceRadio" value="10">Less than £10</td>
									<td>
										(<?= $this->processor->getProductCountForPrice(10) ?>)
									</td>
								</tr>
								<tr>
									<td><input <?php if(get_option('sw_maxPriceRadio') == '50'){ echo 'checked="checked"';} ?>
											class="maxPriceRadio" type="radio" name="maxPriceRadio" value="50">Less than £50</td>
									<td>
										(<?= $this->processor->getProductCountForPrice(50) ?>)
									</td>
								</tr>
								<tr>
									<td><input <?php if(get_option('sw_maxPriceRadio') == '100'){ echo 'checked="checked"';} ?>
											class="maxPriceRadio" type="radio" name="maxPriceRadio" value="100">Less than £100</td>
									<td>
										(<?= $this->processor->getProductCountForPrice(100) ?>)
									</td>
								</tr>
								<tr><th colspan="2" class="filterType">By price range</th></tr>
								<tr>
									<td><input <?php if(get_option('sw_maxPriceRadio') == 'range'){ echo 'checked="checked"';} ?>
											type="radio" name="maxPriceRadio" value="range" id="maxPriceRange">
										<input value="<?= get_option('sw_minPrice') ?>" class="range" size="3" maxlength="3" type="number" name="minPrice" placeholder="min" readonly></td>
									<td><input value="<?= get_option('sw_maxPrice') ?>" class="range" size="3" maxlength="3" type="number" name="maxPrice" placeholder="max" readonly></td>
								</tr>
								<tr><td colspan="2"><i>*Product without valid image will not be displayed</i></td> </tr>
								<tr>
									<td colspan="2" class="buttons">
										<input type="submit" name="filterOptions" id="filterOptions" class="button button-primary" value="Save changes">
										<input type="button" name="resetFilters" id="resetFilters" class="button" value="Reset filters">
									</td>
								</tr>
							</table>
						</form>
					</div>
				</div>
				<div class="display">
					<input class="button reportButton" type="button" value="Display Analytics" id="reportButton"/>
				</div>

				<section id="analytics" class="analytics" style="display: none;">

					<div class="analyticsPopular">
						<?php
						$db = new DataFeedDBConnection();
						$analytics = $db->getPopularAnalytics();
						?>
						<table class="aw-filter analytics"  cellspacing="0" cellpadding="0">
							<tr><th colspan="2"><h1> Popular Products </h1></th></tr>
							<tr><th>Product</th><th>Click</th></tr>
							<?php
							foreach($analytics as $row) {
								?>
								<tr>
									<td class="image"><a href="<?=$row['merchantDeepLink']?>" target="_blank"><img src="<?=$row['awImageUrl']?>"/> </a></td>
									<td><?=$row['count']?></td></tr>
								<?php
							}
							?>
						</table>
					</div>
					<div class="analyticsDaily">
						<?php
						$db = new DataFeedDBConnection();
						$analytics = $db->getClickAnalytics();
						?>
						<table class="aw-filter analytics" cellspacing="0" cellpadding="0">
							<tr><th colspan="3"><h1> User daily click </h1></th></tr>
							<tr><th class="text-center">Click time</th><th>IP</th><th>Product</th></tr>
							<?php
							foreach($analytics as $row) {
								?>
								<tr>
									<td><?=$row['clickDateTime']?></td>
									<td><?=$row['clickIp']?></td>
									<td class="image"> <a href="<?=$row['merchantDeepLink']?>" target="_blank"><img src="<?=$row['awImageUrl']?>"/></a></td></tr>
								<?php
							}
							?>
						</table>
					</div>
				</section>
			</section>
			<?php
		}
	}

	public function render_guide()
	{?>

		<div class="wrap">
			<h3 class="info"> Please refer to
				<a href="http://wiki.affiliatewindow.com/index.php/Downloading_A_Feed" target="_blank">
					http://wiki.affiliatewindow.com/index.php/Downloading_A_Feed
				</a>
			</h3>
			<h1>Shortcodes</h1>
			<ol>
				<li>[AWIN_DATA_FEED] - Default shortcode</li>
			</ol>
			<h2>Shortcode Options</h2>
			<ol>
				<li>title='any title in quote'</li>
				<li>no_of_product=any number</li>
				<li>keywords='comma separated value in quote'</li>
			</ol>
			<h2>Shortcode Examples</h2>
			<ol>
				<li>[AWIN_DATA_FEED title='hello world' no_of_product=2]</li>
				<li>[AWIN_DATA_FEED no_of_product=3 title='Iron Man vs Captain America' keywords='Iron Man, Captain America' ]</li>
			</ol>
		</div>

	<?php
	}
}
