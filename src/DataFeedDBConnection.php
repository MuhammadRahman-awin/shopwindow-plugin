<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once('OptionHandler.php');

class DataFeedDBConnection
{
    /** @var string */
    private $dbTable = "";

    /** @var string */
    private $analyticsTable = "";

    /** @var string */
    private $tableName = "";

    public function __construct()
    {
        global $wpdb;

        $this->tableName = $wpdb->prefix . "datafeed";
        $this->dbTable = DB_NAME . "." . $this->tableName;
        $this->analyticsTable = DB_NAME . ".". $wpdb->prefix. "datafeed_analytics";

        $this->createTableIfNotExist();
    }

    public function truncateTable()
    {
        delete_sw_options();

        global $wpdb;
        $wpdb->query("TRUNCATE TABLE ". $this->dbTable);
    }

    /**
     * @param array $row
     */
    public function insertRow($row)
    {
        if (empty($row['description'])) {
            return ;
        }

        global $wpdb;

        $query = "
        INSERT INTO " .$this->dbTable. "
            (
            categoryName,
            awDeepLink,
            merchantDeepLink,
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
            $row['merchant_deep_link']. "','" .
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

        $extraWhere = $this->getWhere();

        $sql = "SELECT * FROM  $this->dbTable" .
                " WHERE description !=''";
        $sql .= $extraWhere;
        $sql .= " ORDER BY RAND() LIMIT " . $limit;
//        error_log(print_r($sql, 1));
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    /**
     * @return mixed
     */
    public function countFeedInDb()
    {
        global $wpdb;
        $extraWhere = $this->getWhere();
        $sql = "SELECT COUNT(*) FROM ".  $this->dbTable . " WHERE price > 0 ". $extraWhere;
        $result = $wpdb->get_var($sql);

        return $result;
    }

    /**
     * @return mixed
     */
    public function getProductCountByFreeDeliveryCost()
    {
        global $wpdb;

        $sql = "
        SELECT SUM( amount)
        FROM
          (SELECT deliveryCost, COUNT(*) AS amount
           FROM $this->dbTable
           GROUP BY deliveryCost
           HAVING deliveryCost<1) getCount;
        ";
        $result = $wpdb->get_var($sql);

        return $result;
    }

    public function getProductCountByCategory()
    {
        global $wpdb;

        $sql = "SELECT
                  categoryName,
                COUNT(*) as count
                FROM  $this->dbTable
                GROUP BY categoryName
                HAVING count > 20
                ORDER BY count DESC ;";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    /**
     * @param integer $price
     *
     * @return mixed
     */
    public function getProductCountByPrice($price)
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) as amount FROM $this->dbTable where price < $price;";
        $result = $wpdb->get_var($sql);

        return $result;
    }

    /**
     * @param array $row
     */
    public function saveAnalytics(array $row)
    {
        global $wpdb;

        $insertOrUpdate = "
        INSERT INTO " .$this->analyticsTable. "
            (clickIp, clickDateTime, feed)
        VALUES
            (
            '" .$row['clickIp']. "','" .
             $row['clickDateTime']. "','" .
             $row['feedId']. "'
            )
        ON DUPLICATE KEY UPDATE
            clickIp = '"            . $row['clickIp'] ."',
            clickDateTime = '"      . $row['clickDateTime'] ."',
            feed = '"               . $row['feedId'] . "';";

        $wpdb->query($insertOrUpdate);
    }

    public function getIPAnalytics()
    {
        global $wpdb;

        $sql = "SELECT clickIp, COUNT(*) AS click FROM ". $this->analyticsTable. " GROUP BY clickIp ORDER BY click DESC LIMIT 20;";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    public function getClickAnalytics()
    {
        global $wpdb;

        $sql = "
            SELECT clickDateTime, df.merchantImageUrl, df.awDeepLink
            FROM ". $this->analyticsTable . " da
            JOIN ". $this->tableName ." df
            ON df.id = da.feed
            WHERE DATE(clickDateTime) = CURDATE() ORDER BY clickDateTime DESC LIMIT 20;
        ";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    public function getPopularAnalytics()
    {
        global $wpdb;

        $sql = "
            SELECT feed AS product, COUNT(*) AS count, df.merchantImageUrl, df.awDeepLink
            FROM ". $this->analyticsTable . " da
            JOIN ". $this->tableName ." df
            ON df.id = da.feed
            GROUP BY product ORDER BY count DESC LIMIT 20;
        ";
        $result = $wpdb->get_results($sql, ARRAY_A);

        return $result;
    }

    /**
     * @return string
     */
    private function getWhere()
    {
        $where = "";
        if (get_option('sw_deliveryMethod') == 'free') {
            $where .= "AND (deliveryCost=0 OR deliveryCost='') ";
        }

        $categories = get_option('sw_categories');
        if ($categories) {
            $where .= "AND categoryName in (\"". implode('","', $categories) . "\") ";
        }

        $maxPriceRadio = get_option('sw_maxPriceRadio');
        if ($maxPriceRadio == 'range') {
            $min = get_option('sw_minPrice');
            $max = get_option('sw_maxPrice');
            $where .= "AND price between $min AND $max ";
        } else {
            $max = (int)$maxPriceRadio;
            if ($max > 0) {
                $where .= "AND price < $max";
            }
        }

        return $where;
    }

    /**
     * create necessary db tables
     */
    private function createTableIfNotExist()
    {
        $this->createFeedTable();
        $this->createAnalyticsTable();
    }

    private function createFeedTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . $this->dbTable. "(
                  id int(11) NOT NULL AUTO_INCREMENT,
                  categoryName varchar(45) DEFAULT NULL,
                  awDeepLink varchar(500) DEFAULT NULL,
                  merchantDeepLink varchar(500) DEFAULT NULL,
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

    private function createAnalyticsTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "
          CREATE TABLE ". $this->analyticsTable ." (
          id int(11) NOT NULL AUTO_INCREMENT,
          clickIp varchar(45) NOT NULL,
          clickDateTime datetime DEFAULT NULL,
          feed int(11) DEFAULT NULL,
          PRIMARY KEY (id)
        ) $charset_collate;";

        $wpdb->get_var("SHOW TABLES LIKE 'wp_datafeed_analytics'");
        if($wpdb->num_rows != 1) {
            dbDelta( $sql );
        }
    }
}
