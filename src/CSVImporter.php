<?php
require_once('DataFeedDBConnection.php');

class CSVImporter extends SplFileObject
{
    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        parent::__construct($fileName);
        $this->setFlags(SplFileObject::READ_CSV);
    }

    public function importToTable()
    {
        $db = new DataFeedDBConnection();
        $db->truncateTable();
        $headers = $this->fgetcsv();

        while (!$this->eof()) {
            $row = $this->fgetcsv();
            if (count($headers) === count($row)) {
                $data = array_combine($headers, $row);
                $db->insertRow($data);
            }
        }
    }
}
