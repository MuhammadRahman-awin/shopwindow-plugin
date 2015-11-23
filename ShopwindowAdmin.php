<?php
require_once('src/CSVImporter.php');
require_once('src/FileUploadErrorHandler.php');
require_once('src/FeedProcessor.php');
require_once('src/OptionHandler.php');
require_once('src/DataFeedDBConnection.php');

$max_size = ini_get('post_max_size');
$max_file_size = ini_get('upload_max_filesize');

if (! empty($_SERVER['CONTENT_LENGTH']) && empty($_FILES) && empty($_POST) ){
    echo "<h1 class='error center'>Failed!</h1><h3 class='error'></br>The uploaded file exceeds the limit in php.ini</br></h3>";
}

if ( ! empty( $_POST ) && check_admin_referer( 'sw_admin_option' ) ) {

    if (isset($_POST['submit']) && ! empty($_FILES["dataFeed"])) {
        $errorHandler = new FileUploadErrorHandler($_FILES["dataFeed"]);
        if (! $errorHandler->valid) {
            echo "<h3 class='error center'>Failed! </br>". $errorHandler->message . "</br></h3>";
        } else {
            $csvImporter = new CSVImporter($_FILES["dataFeed"]["tmp_name"]);
            $csvImporter->importToTable();
            $count = count(file($_FILES["dataFeed"]["tmp_name"]));
            echo "<h3 class='info center'>Success! </br>$count Product imported</br></h3>";
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
<div class="wrap" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <h2>Import your shopwindow data feed to display in widget</h2>
        </br><h3 class="info">Maximum file size must be smaller than: <?php echo $max_file_size ?>B </h3>
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
</div>
<?php
$fp = new FeedProcessor();
if($fp->hasFeedInDb()) {
   ?>
   <section>
    <div class="options">
        <div class="form">
            <form name="swFilters" id="swFilters"  method="post">
                <?php echo wp_nonce_field( 'sw_admin_option'); ?>
                <table class="filter">
                    <tr>
                        <th colspan="2" class="default">(DEFAULT) Randomly display product in random order.
                        </th>
                    </tr>
                    <tr><th colspan="2"><h2>Filter products*</h2></th></tr>
                    <tr><th colspan="2" class="filterType">By Delivery Type</th></tr>
                    <tr>
                        <td><input
                                <?php if(get_option('sw_deliveryMethod') == 'free'){ echo 'checked="checked"'; } ?>
                                type="checkbox" name="deliveryMethod" value="free" id="deliveryMethod">Free Delivery</td>
                        <td>
                            (<?= $fp->getFreeDeliveryProducts() ?>)
                        </td>
                    </tr>

                    <?php
                    if(count($fp->getProductCountByCategory()) > 1) {
                            echo '<tr><th colspan="2" class="filterType">By Category</th></tr>';

                            foreach($fp->getProductCountByCategory() as $category) {
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
                            (<?= $fp->getProductCountForPrice(10) ?>)
                        </td>
                    </tr>
                    <tr>
                        <td><input <?php if(get_option('sw_maxPriceRadio') == '50'){ echo 'checked="checked"';} ?>
                                class="maxPriceRadio" type="radio" name="maxPriceRadio" value="50">Less than £50</td>
                        <td>
                            (<?= $fp->getProductCountForPrice(50) ?>)
                        </td>
                    </tr>
                    <tr>
                        <td><input <?php if(get_option('sw_maxPriceRadio') == '100'){ echo 'checked="checked"';} ?>
                                class="maxPriceRadio" type="radio" name="maxPriceRadio" value="100">Less than £100</td>
                        <td>
                            (<?= $fp->getProductCountForPrice(100) ?>)
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
                </table>
                <section class="submitButton">
                    <input type="submit" name="filterOptions" id="filterOptions" class="button button-primary" value="Save changes">
                    <input type="button" name="resetFilters" id="resetFilters" class="button" value="Reset filters">
                </section>
            </form>
        </div>
    </div>
    <div class="productCount">
        <h1 class="count"> Product found: <?= $fp->getFeedCount() ?></h1> </h1>
    </div>
       <input class="button reportButton" type="button" value="Display Report" id="reportButton"/>
   </section>
    <section class="analytics" style="display: none;">
    <div class="analyticsIP">
        <?php
        $db = new DataFeedDBConnection();
        $analytics = $db->getIPAnalytics();
        ?>
        <table class="analytics">
            <tr><th colspan="2"><h1> User click analytics* </h1></th></tr>
            <tr><th>User IP</th><th>Click</th></tr>
            <?php
            foreach($analytics as $row) {
                ?>
                <tr><td><?=$row['clickIp']?></td><td><?=$row['click']?></td></tr>
                <?php
            }
            ?>
            <tr><td colspan="2"><i>*may not be 100% correct due to proxy or manual http header</i></td> </tr>
        </table>
    </div>
    <div class="analyticsPopular">
        <?php
        $db = new DataFeedDBConnection();
        $analytics = $db->getPopularAnalytics();
        ?>
        <table class="analytics">
            <tr><th colspan="2"><h1> Popular Products </h1></th></tr>
            <tr><th>Product</th><th>Click</th></tr>
            <?php
            foreach($analytics as $row) {
                ?>
                <tr>
                    <td><a href="<?=$row['awDeepLink']?>" target="_blank"><img src="<?=$row['merchantImageUrl']?>"/> </a></td>
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
        <table class="analytics">
            <tr><th colspan="2"><h1> User daily click </h1></th></tr>
            <tr><th>Click time</th><th>Click</th></tr>
            <?php
            foreach($analytics as $row) {
                ?>
                <tr>
                    <td><?=$row['clickDateTime']?></td>
                    <td><a href="<?=$row['awDeepLink']?>" target="_blank"><img src="<?=$row['merchantImageUrl']?>"/></a></td></tr>
                <?php
            }
            ?>
        </table>
    </div>
    </section>
<?php
}
