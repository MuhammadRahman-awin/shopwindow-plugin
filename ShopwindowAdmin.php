<?php
require_once('src/CSVImporter.php');

$max_size = ini_get('upload_max_filesize');

var_dump($_SERVER['CONTENT_LENGTH']);
var_dump($_FILES);
var_dump($_POST);
if (! empty($_SERVER['CONTENT_LENGTH']) && empty($_FILES) && empty($_POST) ){
    echo "<h1 class='error'>Failed!</h1><h3 class='error'></br><li>The uploaded zip was too large. You must upload a file smaller than". $max_size . "</li></br></h3>";
}


if (isset($_POST['submit']) && ! empty($_FILES["dataFeed"])) {
    var_dump($_FILES["dataFeed"]);
    $csvImporter = new CSVImporter($_FILES);

    if (! $csvImporter->isValid()) {
        echo "<h1 class='error'>Failed!</h1><h3 class='error'></br><li>". $csvImporter->error. "</li></br></h3>";
    } else {
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
