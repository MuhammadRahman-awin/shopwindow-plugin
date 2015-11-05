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
            currency,
            price
            )
        VALUES
            (
            '" .esc_sql($row['category_name']). "','" .
            $row['aw_deep_link']. "&plugin=shopwindow-feed','" .
            $row['merchant_image_url']. "','" .
            esc_sql($row['description']). "','" .
            esc_sql($row['product_name']). "','" .
            $row['delivery_cost']. "','" .
            $row['currency']. "','" .
            $row['search_price']. "'
            )";

        $wpdb->query($query);
    }

    /**
     * @param int $limit
     * @return array
     */
    public function getLimitedRows($limit)
    {
        global $wpdb;

        $sql = "SELECT * FROM  $this->dbTable
                WHERE description !=''
                ORDER BY RAND() LIMIT " . $limit;
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
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
                  awDeepLink varchar(500) DEFAULT NULL,
                  merchantImageUrl varchar(500) DEFAULT NULL,
                  description text CHARACTER SET utf8mb4,
                  productName varchar(255) DEFAULT NULL,
                  deliveryCost varchar(255) DEFAULT NULL,
                  currency varchar(11) DEFAULT NULL,
                  price varchar(15) DEFAULT NULL,
                  PRIMARY KEY (id),
                  UNIQUE KEY id_UNIQUE (id)
                ) $charset_collate;";

        $wpdb->get_var("SHOW TABLES LIKE '". $this->tableName . "'");
        if($wpdb->num_rows != 1) {
            dbDelta( $sql );
        }
    }
}
