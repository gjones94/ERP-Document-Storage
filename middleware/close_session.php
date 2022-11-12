<?php
    include "middleware_functions.php";
    include_once "../support/file_functions.php";

    $log_file = "/home/ubuntu/logs/api_close_log.txt";
    $debug = false;
    $api_type = "close";

    if(open_session_exists()){
        $session_record = get_open_session();
    }else{
        session_logging("N/A", $api_type, "N/A", "INTERNAL_ERROR", "No open session exists", "You must first create a session", "0.0");
        die("Internal Error: No open session exists, errors logged to database\n");
    }

    $sid = $session_record['sid'];
    $uid = $session_record['uid'];
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

    if($status == "OK"){
        close_session($sid);
    }


    //Log to database
    session_logging($sid, $api_type, $data, $status, $msg, $action, $execution_time);

    //Log to system
    $response = get_response_formatted($api_type, $cinfo);
    write_to_file($log_file, $response);
?>