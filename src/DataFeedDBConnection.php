<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

class DataFeedDBConnection
{

    /** @var string */
    private $dbTable = "";

    /** @var string */
    private $tableName = "";

    public function __construct()
    {
        global $wpdb;

        $this->tableName = $wpdb->prefix . "datafeed";
        $this->dbTable = DB_NAME . "." . $this->tableName;

        $this->createTableIfNotExist();
    }

    public function truncateTable()
    {
        global $wpdb;
        $wpdb->query("TRUNCATE TABLE ". $this->dbTable);
    }

    /**
     * @param array $row
     */
    public function insertRow($row)
    {
        global $wpdb;

        $query = "
        INSERT INTO " .$this->dbTable. "
            (
            categoryName,
            awDeepLink,
            merchantImageUrl,
            description,
            productName,
            deliveryCost,
            displayPrice
            )
        VALUES
            (
            '" .esc_sql($row['category_name']). "','" .
            $row['aw_deep_link']. "','" .
            $row['merchant_image_url']. "','" .
            esc_sql($row['description']). "','" .
            esc_sql($row['product_name']). "','" .
            $row['delivery_cost']. "','" .
            $row['display_price']. "'
            )";

        $wpdb->query($query);
    }

    /**
     *
     */
    private function createTableIfNotExist()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . $this->dbTable. "(
                  id int(11) NOT NULL AUTO_INCREMENT,
                  categoryName varchar(45) DEFAULT NULL,
                  awDeepLink varchar(45) DEFAULT NULL,
                  merchantImageUrl varchar(45) DEFAULT NULL,
                  description varchar(500) DEFAULT NULL,
                  productName varchar(255) DEFAULT NULL,
                  deliveryCost varchar(255) DEFAULT NULL,
                  displayPrice varchar(15) DEFAULT NULL,
                  PRIMARY KEY (id),
                  UNIQUE KEY id_UNIQUE (id)
                ) $charset_collate;";

        $wpdb->get_var("SHOW TABLES LIKE '". $this->tableName . "'");
        if($wpdb->num_rows != 1) {
            dbDelta( $sql );
        }
    }
}