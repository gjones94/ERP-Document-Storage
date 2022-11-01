<?php
include_once "Database.php";
include_once "definitions.php";

class UploadFile{

    private $owner;
    private $name;
    private $size;
    private $upload_date;
    private $status;
    private $path;
    private $type;
    private $doc_type;
    private $ext;

    private $content;
    private $temp_name;

    function __construct($file, $owner, $doc_type)
    {
        $this->temp_name = $file['tmp_name'];
        $this->owner = $owner;
        $this->size = $file['size'];
        $this->status = 'active';
        $this->path = UPLOADS;
        $this->type = $file['type'];
        $this->doc_type = $doc_type;

        //separate file name and extension
        $this->separate_file_name_and_extension($file['name']);

        //get content of file
        $this->content = $this->get_content();
    }

    //TODO Choose either this or the db one eventually
    public function upload_to_filesystem()
    { /* Read contents of file into temp variable */
        $this->write_file_content_to_system();
        $this->write_file_info_to_db();
    }

    //Functions for uploading file to system with path
    private function write_file_info_to_db()
    {
       $db = new Database();
       $db->connect();
       $this->upload_date = date("Y-m-d H:i:s");

       $sql = "INSERT INTO `file` (`owner`,`name`,`size`,`upload_date`,`status`,`path`, `content`, `type`, `doc_type`, `ext`) VALUES ('$this->owner','$this->name','$this->size','$this->upload_date', '$this->status', '$this->path', NULL, '$this->type', '$this->doc_type', '$this->ext')";
       $db->query($sql);
    }

    private function write_file_content_to_system()
    {
        $fp = fopen($this->path.$this->name, "wb") or die("Could not open the $this->path$this->name for writing..");
        fwrite($fp, $this->content);
        fclose($fp);
    }

    //Function for uploading file and content to db without path
    public function upload_to_db(){
        $db = new Database();
        $db->connect();
        $this->upload_date = date("Y-m-d H:i:s");

        $formatted_content = addslashes($this->content);
        $sql = "INSERT INTO `file` (`owner`,`name`,`size`,`upload_date`,`status`,`path`,`content`,`type`,`doc_type`, `ext`) VALUES ('$this->owner','$this->name','$this->size','$this->upload_date', '$this->status', NULL, '$formatted_content','$this->type', '$this->doc_type', '$this->ext')";
        $db->query($sql);
    }

    //Helper function for obtaining content of upload
    private function get_content()
    {
        $fp = fopen($this->temp_name, "r");
        $content = fread($fp, filesize($this->temp_name));
        fclose($fp);
        return $content;
    }

    private function separate_file_name_and_extension($filename)
    {
        $pattern = '/^(\w+)(\.[a-zA-Z]{1,10}$)/';
        if(preg_match($pattern, $filename, $matches))
        {
            $this->name = $matches[1];
            $this->ext = $matches[2];
        }
        else
        {
            die("Incorrect filename given");
        }
    }
}



?>
