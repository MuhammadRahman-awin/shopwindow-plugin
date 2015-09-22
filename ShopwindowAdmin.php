<?php
require_once('src/CSVImporter.php');
require_once('src/FileUploadErrorHandler.php');

$max_size = ini_get('post_max_size');

if (! empty($_SERVER['CONTENT_LENGTH']) && empty($_FILES) && empty($_POST) ){
    echo "<h1 class='error'>Failed!</h1><h3 class='error'></br>The uploaded file exceeds the post_max_size directive in php.ini (<". $max_size . ")</br></h3>";
}

if (isset($_POST['submit']) && ! empty($_FILES["dataFeed"])) {
    $errorHandler = new FileUploadErrorHandler($_FILES["dataFeed"]);
    if (! $errorHandler->valid) {
        echo "<h1 class='error'>Failed!</h1><h3 class='error'></br>". $errorHandler->message . "</br></h3>";
    } else {
        $csvImporter = new CSVImporter($_FILES["dataFeed"]);
        $csvImporter->importToTable();
    }
}
?>
<div class="wrap" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <h1><a href="https://darwin.affiliatewindow.com" target="_new">Process Shopwindow feed</a></h1></br>
    </br><h1>Maximum file size must be smaller than: <?php echo $max_size ?> </h1>

    <form enctype="multipart/form-data" name="csvUpload" method="post" action="">

        <h3>Upload your shopwindow data feed:
            <input type="file" name="dataFeed" id="dataFeed">
        </h3>
        <?php submit_button('Process Data Feed'); ?>
    </form>
</div>
<?php
