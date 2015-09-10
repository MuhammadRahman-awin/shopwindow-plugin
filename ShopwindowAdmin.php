<?php
require_once('src/CSVImporter.php');

$max_size = ini_get('post_max_size');

if ( !empty($_SERVER['CONTENT_LENGTH']) && empty($_FILES) && empty($_POST) )
    echo 'The uploaded zip was too large. You must upload a file smaller than ' . $max_size;


if (isset($_POST['submit']) && isset($_FILES)) {
    $csvImporter = new CSVImporter($_FILES);
    if (! $csvImporter->isValid()) {
        foreach($csvImporter->errors as $error) {
            echo $error;
        }
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
            <input type="file" name="dataFeed" id="timetable">
        </h3>
        <?php submit_button('Process Data Feed'); ?>
    </form>
</div>
<?php
