<?php

use FileUploadErrorHandler as ErrorHandler;

class CSVImporter extends SplFileObject
{
    /** @var  ErrorHandler */
    private $errorHandler;

    private $valid = true;

    public $errors = array();

    /**
     * @param array $file
     * @param ErrorHandler $errorHandler
     */
    public function __construct($file, FileUploadErrorHandler $errorHandler=null)
    {
        $this->errorHandler = $errorHandler ?: new FileUploadErrorHandler();
        parent::__construct($file);
    }

    public function isValid()
    {
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $this->errors[] = $this->errorHandler->getFileErrorMessage($_FILES);
        } else {
            $this->errors[] = $this->errorHandler->getFileErrorCodeToMessage($_FILES['file']['error']);
        }

        if (!empty($this->errors)) {
            $this->valid = false;
        }

        return $this->valid;
    }

    public function importToTable()
    {

    }
}
