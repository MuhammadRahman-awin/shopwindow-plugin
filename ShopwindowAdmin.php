<?php
require_once('src/CSVImporter.php');
require_once('src/FileUploadErrorHandler.php');

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
?>
<div class="wrap" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
     xmlns="http://www.w3.org/1999/html">
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
