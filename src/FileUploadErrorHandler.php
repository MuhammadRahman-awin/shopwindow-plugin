<?php

class FileUploadErrorHandler
{
    private $message;

    /**
     * @param string $code
     * @return string
     */
    public function getFileErrorCodeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $this->message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $this->message = "File upload stopped by extension";
                break;
        }
        return $this->message;
    }

    /**
     * @param array $file
     * @return string
     */
    public function getFileErrorMessage(array $file)
    {
        if($file["type"] != "text/csv") {
            return "Invalid csv file ?";
        }

        return null;
    }
}
