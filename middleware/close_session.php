<?php
    include_once "middleware_functions.php";
    include_once "../support/file_functions.php";
    include_once "../support/general_functions.php";

    function close_session($uid = NULL, $sid = NULL)
    {
        $log_file = "/home/ubuntu/logs/api_close_log.txt";
        $api_type = "close";

        if($sid == NULL || $uid == NULL)
        {
            if(open_session_exists()){
                $session_record = get_open_session();
            }else{
                session_logging("N/A", $api_type, "N/A", "INTERNAL_ERROR", "No open session exists", "You must first create a session", "0.0");
                die("Internal Error: No open session exists, errors logged to database\n");
            }
            $sid = $session_record['sid'];
            $uid = $session_record['uid'];
        }

        $data = "sid=$sid&uid=$uid";

        $ch = get_curl_for_api($api_type, $data);

        if($ch == null){
            session_logging("N/A", $api_type, "N/A", "INTERNAL_ERROR", "Curl command was not generated successfully", "Check curl functions", "0.0");
            die();
        }
        
        $time_start = microtime(true);
        $result = curl_exec($ch);
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start) / 60;

        curl_close($ch);
        $cinfo = json_decode($result);

        $status = explode(": ", $cinfo[0])[1];
        $msg = explode(": ", $cinfo[1])[1];
        $action = explode(": ", $cinfo[2])[1];

        print_response($cinfo);

        if($status == "OK"){
            log_session_closed($sid);
        }

        //Log to database
        session_logging($sid, $api_type, $data, $status, $msg, $action, $execution_time);

        //Log to system
        $response = get_response_formatted($api_type, $cinfo, get_date());
        write_to_file($log_file, $response);
    }

?>