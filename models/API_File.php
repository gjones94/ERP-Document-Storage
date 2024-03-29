<?php

    include_once "Database.php";
    include_once "../support/file_functions.php";
    include_once "../support/general_functions.php";
    include_once "../middleware/middleware_functions.php";

    class API_File
    {
        public $file_name;
        private $size;
        private $upload_date; 
        private $status; 
        private $doc_type;
        private $ext; 
        private $content;

        //obtained from name (could be missing one of these)
        private $account = "N/A";
        private $create_date = NULL;
        private $type = "N/A"; 

        //other metadata we need to have access to for logging
        private $error;
        private $execution_time;

        //used for knowing whether a file was overwritten when uploading this file
        private $overwrite_occured = false;

        function __construct($file_name, $curl_handler)
        {
            $this->file_name = $file_name;
            $this->status = 'active';
            $this->get_data_from_filename(); //gets account, type, create_date
            $this->get_data_from_api($curl_handler);
        }

        function get_data_from_filename()
        {
            $this->ext = explode(".", $this->file_name)[1];
            $data_array = explode(".", $this->file_name)[0]; //get rid of the extension
            $data_array = explode("-", $data_array); //separate each element

            foreach($data_array as $element)
            {
                $this->set_tag($element);
            }
        }

        function set_tag ($data)
        {
            $reg_account = "/^[0-9]{8}$/";
            if(preg_match($reg_account, $data, $matches))
            {
                $this->account = $matches[0];
            }
    
            $reg_type = "/[a-zA-Z]+/";
            if(preg_match($reg_type, $data, $matches))
            {
                $this->type = $matches[0];
            }
    
            $reg_date = "/[0-9]{8}_[0-9]{2}_[0-9]{2}_[0-9]{2}/";
            if(preg_match($reg_date, $data, $matches))
            {
                $this->create_date = $this->parse_date($matches[0]);
            }
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

        private function get_data_from_api($ch)
        {
            $time_start = microtime(true);
            $this->content = curl_exec($ch);
            $time_end = microtime(true);
            $this->execution_time = ($time_end - $time_start) / 60;
            $this->doc_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $this->size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD_T);

            curl_close($ch);
        }

        public function get_execution_time()
        {
            return $this->execution_time;
        }

        public function upload()
        {
            $this->upload_date = get_date();
            $db = new Database();
            $db->connect();
            if($this->content == NULL)
            {
                $this->error = "No content for file";
                return false;
            }

            if(file_already_exists($this->file_name))
            {
                $sql = "UPDATE `file` SET `status` = 'inactive' WHERE `name` = '$this->file_name' and `status` = 'active'";
                if($db->query($sql) == false)
                {
                    $this->error = "Could not set existing file to inactive";
                    return false;
                }else
                {
                    $this->overwrite_occured = true;
                }
            }

            $formatted_content = addslashes($this->content);
            $sql = "INSERT INTO `file` (`account`, `name`, `size`, `upload_date`, `create_date`, `status`, `content`, `type`, `doc_type`, `ext`) VALUES ('$this->account', '$this->file_name', '$this->size', '$this->upload_date', '$this->create_date', '$this->status', '$formatted_content','$this->type', '$this->doc_type', '$this->ext')";

            if($this->create_date == NULL)
            {
                $sql = "INSERT INTO `file` (`account`, `name`, `size`, `upload_date`, `status`, `content`, `type`, `doc_type`, `ext`) VALUES ('$this->account', '$this->file_name', '$this->size', '$this->upload_date', '$this->status', '$formatted_content','$this->type', '$this->doc_type', '$this->ext')";
            }

            $success = $db->query($sql);
            
            if($success == false)
            {
                $this->error = "Query failed";
            }

            return $success;
        }

        public function get_error()
        {
            return $this->error;
        }

        public function overwrite_occured()
        {
            return $this->overwrite_occured;
        }

    }

?>