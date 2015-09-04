<?php

class CSVImporter extends SplFileObject
{
    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        parent::__construct($fileName);
    }
}
