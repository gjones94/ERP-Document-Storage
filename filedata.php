<?php
include_once "database.php";

class FileData {

    private $fileOwner;
    private $fileName;
    private $fileSize;
    private $fileStatus;
    private $filePath;
    private $fileType;
    private $fileDocType;

    private $fileUploadDate;
    private $fileTempName;

    function __construct($fileObject, $fileOwner, $fileDocType)
    {
        $this->fileName = $fileObject['name'];
        $this->fileTempName = $fileObject['tmp_name'];
        $this->fileOwner = $fileOwner;
        $this->fileSize = $fileObject['size'];
        $this->fileStatus = 'active';
        $this->filePath = '/var/www/html/uploads/';
        $this->fileType = $fileObject['type'];
        $this->fileDocType = $fileDocType;
    }

    public function upload()
    { /* Read contents of file into temp variable */
        $this->fileUploadDate = date("Y-m-d H:i:s"); //last thing we record
        $this->writeFileRecordToDatabase();
        $this->writeFileToSystem();
    }

    private function writeFileRecordToDatabase()
    {
       $db = new Database();
       $db->connect();
       $query = "Insert into `file` (`file_owner`,`file_name`,`file_size`,`file_upload_date`,`file_status`,`file_path`, `file_type`, `file_doc_type`) values ('$this->fileOwner','$this->fileName','$this->fileSize','$this->fileUploadDate', '$this->fileStatus', '$this->filePath', '$this->fileType', '$this->fileDocType')";

       $db->query($query);
    }

    private function writeFileToSystem()
    {
        $content = $this->readFileData();
        $fp = fopen($this->filePath.$this->fileName, "wb") or die("Could not open the $this->filePath$this->fileName for writing..");
        fwrite($fp, $content);
        fclose($fp);
    }

    private function readFileData()
    {
        $fp = fopen($this->fileTempName, "r");
        $content = fread($fp, filesize($this->fileTempName));
        fclose($fp);
        return $content;
    }


}
?>