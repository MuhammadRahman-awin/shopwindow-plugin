<?php

require_once('FileUploadErrorHandler.php');

class CSVImporter extends SplFileObject
{
    /** @var  FileUploadErrorHandler */
    private $errorHandler;
    
    /** @var  array */
    private $file;

    private $valid = true;

    public $error;

    /**
     * @param array $file
     * @param FileUploadErrorHandler $errorHandler
     */
    public function __construct($file, FileUploadErrorHandler $errorHandler=null)
    {
        $this->file = $file;
        $this->errorHandler = empty($errorHandler) ? new FileUploadErrorHandler() : $errorHandler;
        parent::__construct($file["dataFeed"]["tmp_name"]);
        $this->setFlags(SplFileObject::READ_CSV);
    }

    public function isValid()
    {
        if ($this->file['dataFeed']['error'] === UPLOAD_ERR_OK) {
            echo 1;
            $this->error = $this->errorHandler->getFileErrorMessage($this->file["dataFeed"]);
        } else {
            echo 2;
            $this->error = $this->errorHandler->getFileErrorCodeToMessage($this->file['dataFeed']['error']);
        }

        if ($this->error) {
            $this->valid = false;
        }

        return $this->valid;
    }

    public function importToTable()
    {
        var_dump($this->fgetcsv());
    }
}
