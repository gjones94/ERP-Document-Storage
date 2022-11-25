<?php
    include_once "middleware_functions.php";
    include_once ".credentials.php";
    include_once "../support/general_functions.php";
    include_once "../models/API_File.php";

    function request_files($files, $sid = NULL, $uid = NULL)
    {
        $log_file = "/home/ubuntu/logs/api_request_log.txt";

        $expected_file_count = count($files);
        $uploaded_file_count = 0;
        $api_type = "request";
        $uid = API_UID;

        if($sid == NULL)
        {
            $session_record = get_open_session();
            if($session_record == NULL){
                session_logging("N/A", $api_type, "N/A", "INTERNAL_ERROR", "No open session exists", "You must first create a session", "0.0");
            }else{
                $sid = $session_record['sid'];
            }
        }

        /* Take in an array of files */
        $file_list = [];
        foreach($files as $key=>$value)
        {
            $tmp = explode("/", $value);
            $file_name = $tmp[4];
            $data = "sid=$sid&uid=$uid&fid=$file_name";

            $ch = get_curl_for_api($api_type, $data);
            $file = new API_File($file_name, $ch);
            $execution_time = $file->get_execution_time();

            $file_list[] = $file;
        }

        foreach($file_list as $file)
        {
            if($file->upload())
            {
                $uploaded_file_count++;
                $msg = "File:  $file->file_name uploaded";
                $status = "\nUpload: OK";
                if($file->overwrite_occured())
                {
                    $msg = "File:  $file->file_name updated";
                    $status = "Overwrite: OK";
                }
                session_logging($sid, $api_type, $data, $status, $msg, "None", $execution_time);
                echo $status . "\n";
                echo $msg . "\n\n";
            }else
            {
                $missing_files[] = $file->file_name;
                session_logging($sid, $api_type, $data, "Upload: ERROR", "File: $file->file_name upload failed", $file->get_error(), $execution_time);
                echo $file->get_error() . "\n";
            }
        }

        $api_type = 'verify';
        if($uploaded_file_count == $expected_file_count)
        {
            $status = "Verify: OK";
            $action = "None";
            $response = get_response_formatted("Retrieved files", $files, get_date());
            $success = true;
        }else{
            $status = "Verify: ERROR";
            $missing_files_to_string = implode(",", $missing_files);
            $action = "Upload the following: " . $missing_files_to_string; 
            $response = get_response_formatted("Missing files", $missing_files, get_date());
            $success = false;
        }

        $msg = "Uploaded $uploaded_file_count out of $expected_file_count";
        session_logging("N/A", $api_type, "N/A", $status, $msg, $action, "0.00");

        //Log to system
        write_to_file($log_file, $response);

        return $success;
    }
?>