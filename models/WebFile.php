<?php
include_once "Database.php";
include_once "support/definitions.php";
include_once "support/file_functions.php";
include_once "support/general_functions.php";

/*
    Purpose:
    Provides a wrapper class for file information that contains internal methods to
    upload itself (with file content and metadata) to the database
*/

class WebFile{

    private $account;
    private $file_name;
    private $size;
    private $upload_date;
    private $create_date;
    private $status;
    private $type;
    private $doc_type;
    private $ext;
    private $content;
    private $temp_name;

    function __construct($file)
    {
        $this->file_name = $file['name'];
        $this->temp_name = $file['tmp_name'];
        $this->status = 'active';
        $this->size = $file['size'];
        $this->doc_type = $file['type'];

        $this->ext = explode(".", $file['name'])[1];
        $this->content = $this->get_content();

        $this->create_date = date("Y-m-d H:i:s"); //parse this from file name
        $this->type = "Legal"; //parse this from filetype
        $this->account = "71640293"; //Parse this from filename
    }

    function get_data_from_filename()
    {
        $this->ext = explode(".", $this->file_name)[1]; 

        $data_array = explode("-", $this->file_name);
        $this->account = $data_array[0];
        $this->type = $data_array[1];

        $unformatted_date = explode(".", $data_array[2])[0]; //get date text without the extension attached
        $this->create_date = $this->parse_date($unformatted_date);
    }

    function parse_date($unformatted_date)
    {
        $date_data = explode("_", $unformatted_date);
        $date = $date_data[0];

        $time = $date_data[1] . ":" . $date_data[2] . ":" . $date_data[3];

        $pattern = '/^(\d{4})(\d{2})(\d{2})$/';

        if(preg_match($pattern, $date, $matches))
        {
            $date = $matches[1] . "-" . $matches[2] . "-" . $matches[3];
        }
        return $date . " " . $time;
    }

    //Function for uploading file and content to db without path
    public function upload_to_db(){
        $db = new Database();
        $db->connect();
        $this->upload_date = get_date();

        $formatted_content = addslashes($this->content);
        $sql = "INSERT INTO `file` (`account`, `name`, `size`, `upload_date`, `create_date`, `status`, `content`, `type`, `doc_type`, `ext`) VALUES ('$this->account', '$this->file_name', '$this->size', '$this->upload_date', '$this->create_date', '$this->status', '$formatted_content','$this->type', '$this->doc_type', '$this->ext')";
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
}



?>
