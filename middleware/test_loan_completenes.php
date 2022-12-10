<?php
    
    include_once "create_session.php";
    include_once "close_session.php";
    include_once "middleware_functions.php";
    include_once "request_file_separate.php";
    
    $api_type = "request_loans";
    $byref_uid = NULL;
    $byref_sid = NULL;


    
    $session_created = create_session($byref_uid, $byref_sid);

    /*
    $data = "sid=$byref_sid&uid=$byref_uid";

    echo "session id = " . $byref_sid . "\n";
    
    echo "creating curl handle\n"; 
    $ch = get_curl_for_api($api_type, $data);

    echo "executing api request\n"; 
    $time_start = microtime(true);
    $result = curl_exec($ch);
    $time_end = microtime(true);
    $execution_time = ($time_end - $time_start) / 60;

    echo "closing api\n"; 
    curl_close($ch);
    $cinfo = json_decode($result, true);

    $status = explode(": ", $cinfo[0])[1];
    $loans = explode(": ", $cinfo[1])[1];
    $loans = trim($loans, "[]");
    $action = explode(": ", $cinfo[2])[1];
    $loans = explode(",", $loans);

    sort($loans);
    $total_count = 0;
    */

    //$loans[] = 9147862;
    //$loans[] = 23416097;
    //$loans[] = 24907813;
    //$loans[] = 26840197;
    //$loans[] = 62941307;
    //$loans[] = 67189230;
    $loans[] = 96183024;

    foreach($loans as $loan)
    {
    //    $count_for_loan = 0;
        $api_type = "request_file_by_loan";

     //   $lid = trim($loan, "\"");
        $lid = $loan;

        $data = "sid=$byref_sid&uid=$byref_uid&lid=$lid";
        $ch = get_curl_for_api($api_type, $data);
        $result = curl_exec($ch);
        $time_end = microtime(true);
        $cinfo = json_decode($result, true);

        $status = explode(": ", $cinfo[0])[1];
        $files = trim(explode(": ", $cinfo[1])[1], "[]");
        $files = explode(",", $files);
        foreach($files as $file)
        {
            echo trim($file, "\"") . "\n";
        }
        request_files($files, $byref_sid, $byref_uid);
        die();
    }

    //echo "Total File Count: " . $total_count;


    close_session($byref_uid, $byref_sid);
?>
