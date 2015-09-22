<?php

class CSVImporter extends SplFileObject
{
    /**
     * @param array $file
     */
    public function __construct($file)
    {
        parent::__construct($file["tmp_name"]);
        $this->setFlags(SplFileObject::READ_CSV);
    }

    public function importToTable()
    {
        var_dump($this->fgetcsv());
    }
}
