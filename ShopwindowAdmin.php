<?php
if (isset($_POST['submit'])) {

    if (isset($_FILES) &&  $_FILES["timetable"]["type"] === "text/csv") {
var_dump($_FILES);
        $temp = $_FILES["timetable"]["tmp_name"];

        $row = 0;

        if (($handle = fopen($temp, "r")) !== FALSE) {

            $file = file($temp);

            /** skip column headings */
            $header = fgetcsv($handle);
        }

        fclose($handle);
    } else {
        echo "<h1 class='red center'>Invalid csv file ? </h1>";
    }
}
?>
<div class="wrap" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <h1><a href="https://darwin.affiliatewindow.com" target="_new">Process Shopwindow feed</a></h1></br>
    <?php $size = ini_get('post_max_size'); ?>
    </br><h1>Maximum file size must be smaller than: <?php echo $size ?> </h1>

    <form enctype="multipart/form-data" name="csvUpload" method="post" action="">

        <h3>Upload your data feed:
            <input type="file" name="timetable" id="timetable">
        </h3>

        <?php submit_button('Process Data Feed'); ?>
    </form>
</div>
<?php
