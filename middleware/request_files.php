<?php
    include_once "middleware_functions.php";
    include_once ".credentials.php";

    function request_files($files, $sid = NULL)
    {
        $api_type = "request";
        $uid = API_UID;

        if($sid == NULL)
        {
            $session_record = get_open_session();
            if($session_record == NULL){
                die("NO OPEN SESSION");
            }else{
                $sid = $session_record['sid'];
            }
        }
        /* Take in an array of files */

        foreach($files as $key=>$value)
        {
            
            $tmp = explode("/", $value);
            $file = $tmp[4];
            echo "File: " . $file . "\n";
            $data = "sid=$sid&uid=$uid&fid=$file";
            $ch = get_curl_for_api($api_type, $data);

            $time_start = microtime(true);
            $content = curl_exec($ch);
            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start) / 60;
            curl_close($ch);

            $fp = fopen("/home/ubuntu/files/$file", "wb");
            fwrite($fp, $content);
            fclose($fp);
            echo $file . " written to file system\n";
        }
    } 

?>