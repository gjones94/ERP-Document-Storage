<?php
    include ".credentials.php";    
    include "middleware_functions.php";
    include "../support/file_functions.php";

    $log_file = "/home/ubuntu/logs/api_create_log.txt";
    $debug = false;

    $data = "username=".API_UID."&password=".API_PASSWORD;
    $uid = API_UID;
    $api_type = "create";

    if(open_session_exists()){
        $session_record = get_open_session();
        $sid = $session_record['sid'];
        session_logging($sid, $api_type, $data, "INTERNAL_ERROR", "Open session already exists", "Please close existing session", "0.0");
        die("Internal Error: Open session already exists, errors logged to database\n");
    }

    $ch = get_curl_for_api($api_type, $data);

    if($ch == NULL){
        session_logging("N/A", $api_type, "N/A", "INTERNAL_ERROR", "Curl command was not generated successfully", "Check curl functions", "0.0");
        die();
    }

    $time_start = microtime(true);
    $result = curl_exec($ch);
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start) / 60;

    curl_close($ch);
    $cinfo = json_decode($result, true);

    print_response($cinfo);
    echo "\n\n";
    echo $cinfo[0] . "\n";
    echo $cinfo[1] . "\n";
    echo $cinfo[2] . "\n";

    $status = explode(": ", $cinfo[0])[1];
    $msg = explode(": ", $cinfo[1])[1];

    if($status == 'OK' && $msg == "Session Created"){
        $action = $cinfo[2];
        echo "Successfully created session\n";
        echo "Session ID: " . $action . "\n"; //TODO: remove once we confirm this records to database from professor system
        add_session($uid, $action); //action = sid in this instance
    }else{
        $action = explode(": ", $cinfo[2])[1]; //get rid of the action label
    }


    //Log to database
    session_logging($action, $api_type, $data, $status, $msg, $action, $execution_time);

    //Log to system
    $response = get_response_formatted($api_type, $cinfo);
    write_to_file($log_file, $response);
?>
