<?php
require_once('src/CSVImporter.php');
require_once('src/FileUploadErrorHandler.php');
require_once('src/FeedProcessor.php');

$max_size = ini_get('post_max_size');
$max_file_size = ini_get('upload_max_filesize');

if (! empty($_SERVER['CONTENT_LENGTH']) && empty($_FILES) && empty($_POST) ){
    echo "<h1 class='error center'>Failed!</h1><h3 class='error'></br>The uploaded file exceeds the limit in php.ini</br></h3>";
}

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

if (! empty($_POST['filterOptions'])) {
    $deliveryMethod = ($_POST['deliveryMethod']);
    delete_option('deliveryMethod');
    add_option('deliveryMethod', $deliveryMethod);

    $categories = array();
    $categories = ($_POST['categories']);
    delete_option('categories');
    add_option('categories', $categories);

    $minPrice = ($_POST['minPrice']);
    delete_option('minPrice');
    add_option('minPrice', $minPrice);

    $maxPrice = ($_POST['maxPrice']);
    delete_option('maxPrice');
    add_option('maxPrice', $maxPrice);

    $maxPriceRadio = ($_POST['maxPriceRadio']);
    delete_option('maxPriceRadio');
    add_option('maxPriceRadio', $maxPriceRadio);
}
?>
<div class="wrap" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <h2>Import your shopwindow data feed to display in widget</h2>
        </br><h3 class="info">Maximum file size must be smaller than: <?php echo $max_file_size ?>B </h3>
    <p>[Update 'upload_max_filesize' directive in php.ini for larger import]</p>

    <form enctype="multipart/form-data" name="csvUpload" method="post" action="">

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
                <table class="filter">
                    <tr><th colspan="2"><h2>Filter products</h2></th></tr>
                    <tr>
                        <th colspan="2" class="default">(DEFAULT) Randomly display product from (<?= $fp->getFeedCount() ?>) items.
                        </th>
                    </tr>
                    <tr><th colspan="2" class="filterType">By Delivery Type</th></tr>
                    <tr>
                        <td><input
                                <?php if(get_option('deliveryMethod') == '0'){ echo 'checked="checked"'; } ?>
                                type="checkbox" name="deliveryMethod" value="0" id="deliveryMethod">Free Delivery</td>
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
                                <td><input <?php if(in_array($category['categoryName'], get_option('categories') )) { echo 'checked="checked"'; } ?>
                                type="checkbox" name="categories[]" value="<?=$category['categoryName']?>"><?=$category['categoryName']?></td>
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
                        <td><input <?php if(get_option('maxPriceRadio') == '10'){ echo 'checked="checked"';} ?>
                                class="maxPriceRadio" type="radio" name="maxPriceRadio" value="10">Less than £10</td>
                        <td>
                            (<?= $fp->getProductCountForPrice(10) ?>)
                        </td>
                    </tr>
                    <tr>
                        <td><input <?php if(get_option('maxPriceRadio') == '50'){ echo 'checked="checked"';} ?>
                                class="maxPriceRadio" type="radio" name="maxPriceRadio" value="50">Less than £50</td>
                        <td>
                            (<?= $fp->getProductCountForPrice(50) ?>)
                        </td>
                    </tr>
                    <tr>
                        <td><input <?php if(get_option('maxPriceRadio') == '100'){ echo 'checked="checked"';} ?>
                                class="maxPriceRadio" type="radio" name="maxPriceRadio" value="100">Less than £100</td>
                        <td>
                            (<?= $fp->getProductCountForPrice(100) ?>)
                        </td>
                    </tr>
                    <tr><th colspan="2" class="filterType">By price range</th></tr>
                    <tr>
                        <td><input <?php if(get_option('maxPriceRadio') == ''){ echo 'checked="checked"';} ?>
                                type="radio" name="maxPriceRadio" value="" id="maxPriceRange">
                            <input value="<?= get_option('minPrice') ?>" class="range" size="3" maxlength="3" type="text" name="minPrice" placeholder="min" readonly></td>
                        <td><input value="<?= get_option('maxPrice') ?>" class="range" size="3" maxlength="3" type="text" name="maxPrice" placeholder="max" readonly></td>
                    </tr>
                </table>
                <section class="submitButton">
                    <input type="submit" name="filterOptions" id="filterOptions" class="button button-primary" value="Save changes">
                    <input type="button" name="resetFilters" id="resetFilters" class="button" value="Reset filters">
                </section>
            </form>
        </div>
    </div>
    <div class="productCount">
        <span class="count"> Will randomly display product from <span id="productCount"> (<?= $fp->getFeedCount() ?>) </span> items. </span>
    </div>
    </section>
<?php
}
