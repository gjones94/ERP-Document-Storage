<?php
include_once "Database.php";
include_once "definitions.php";

/*
    Purpose:
    Provides a wrapper class for file information that contains internal methods to
    upload itself (with file content and metadata) to the database
*/

class UploadFile{

    private $account;
    private $file_name;
    private $size;
    private $upload_date;
    private $create_date;
    private $status;
    private $path;
    private $type;
    private $doc_type;
    private $ext;
    private $content;
    private $temp_name;

    function __construct($file)
    {
        $this->file_name = $file['name'];
        $this->temp_name = $file['tmp_name'];
        $this->account = "71640293"; //Parse this from filename
        $this->size = $file['size'];
        $this->status = 'active';
        $this->create_date = date("Y-m-d H:i:s"); //parse this from file name
        $this->path = UPLOADS;
        $this->type = "Legal"; //parse this from filetype
        $this->doc_type = $file['type'];
        $this->ext = $this->get_extension_from_filename($file['name']);
        //get content of file
        $this->content = $this->get_content();
    }

    //TODO Choose either this or the db one eventually
    /*
    public function upload_to_filesystem()
    { 
        //Read contents of file into temp variable
        $this->write_file_content_to_system();
        $this->write_file_info_to_db();
    }
    */

    //Functions for uploading file to system with path
    /*
    private function write_file_info_to_db()
    {
       $db = new Database();
       $db->connect();
       $this->upload_date = date("Y-m-d H:i:s");

       $sql = "INSERT INTO `file` (`account`,`name`,`size`,`upload_date`, `create_date`, `status`,`path`, `content`, `type`, `doc_type`, `ext`) VALUES ('$this->owner','$this->name','$this->size','$this->upload_date', '$this->status', '$this->path', NULL, '$this->type', '$this->doc_type', '$this->ext')";
       $db->query($sql);
    }
    */

    /*
    private function write_file_content_to_system()
    {
        $fp = fopen($this->path.$this->name, "wb") or die("Could not open the $this->path$this->name for writing..");
        fwrite($fp, $this->content);
        fclose($fp);
    }
    */

    //Function for uploading file and content to db without path
    public function upload_to_db(){
        $db = new Database();
        $db->connect();
        $this->upload_date = date("Y-m-d H:i:s");

        $formatted_content = addslashes($this->content);
        $sql = "INSERT INTO `file` (`account`,`name`,`size`,`upload_date`, `create_date`, `status`,`path`,`content`,`type`,`doc_type`, `ext`) VALUES ('$this->account','$this->file_name','$this->size','$this->upload_date', '$this->create_date', '$this->status', NULL, '$formatted_content','$this->type', '$this->doc_type', '$this->ext')";
        return $db->query($sql);
    }

    //Helper function for obtaining content of upload
    private function get_content()
    {
        $fp = fopen($this->temp_name, "r");
        $content = fread($fp, filesize($this->temp_name));
        fclose($fp);
        return $content;
    }

    private function get_extension_from_filename($filename)
    {
        $pattern = '/^[\w-]+(\.[a-zA-Z]{1,10}$)/';
        if(preg_match($pattern, $filename, $matches))
        {
            return $matches[1];
        }
        else
        {
            die("Incorrect filename given");
        }
    }
}



?>
