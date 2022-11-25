<?php
    include_once "create_session.php";
    include_once "close_session.php"; 
    include_once "../support/general_functions.php";

    $byref_uid = NULL;
    $byref_sid = NULL;

    echo "========================CRON RUNNING========================";
    echo "\nDate: " . get_date();
    $session_created = create_session($byref_uid, $byref_sid);

    if($session_created)
    {
        include_once "query_files.php";
        $files = query_files($byref_uid, $byref_sid);
    }else{
        die("No session created\n");
    }


    if($files != NULL)
    {
        include_once "request_files.php";
        if(request_files($files, $byref_sid, $byref_uid) == false)
        {
            echo "Files uploading errors\n";
        }
    }

    close_session($byref_uid, $byref_sid);
    echo "\nJob Complete" ."\n";
    echo "=======================CRON COMPLETED=======================\n\n\n";
?>