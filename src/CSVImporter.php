<?php

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
        var_dump($this->fgetcsv());
    }
}
