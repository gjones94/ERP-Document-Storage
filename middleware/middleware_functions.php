<?php
    include_once "../models/Database.php"; 
    include_once ".credentials.php";
    include_once "../support/general_functions.php";

    function get_curl_for_api($api_type, $data)
    {
        $ch = "";
        switch($api_type)
        {
            case "create":
                $ch = curl_init('https://cs4743.professorvaladez.com/api/create_session'); 
                break;
            case "close":
                $ch = curl_init('https://cs4743.professorvaladez.com/api/close_session'); 
                break;
            case "query":
                $ch = curl_init('https://cs4743.professorvaladez.com/api/query_files');
                break;
            case "request":
                $ch = curl_init("https://cs4743.professorvaladez.com/api/request_file");
                break;
            default:
                return null;
        }

        set_curl_options($ch, $data);
        return $ch;
    } 

    function set_curl_options($ch, $data)
    {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'content-type: application/x-www-form-urlencoded',
        'content-length: ' . strlen($data))
        );
    }

    function print_response($cinfo)
    {
        echo "\n";
        echo $cinfo[0];
        echo "\n";
        echo $cinfo[1];
        echo "\n";
        echo $cinfo[2];
        echo "\n"; 
    }

    function open_session_exists()
    {
        /*
            Returns whether a session has already been opened
        */
        $db = new Database();
        $db->connect();

        $query = "SELECT * FROM `session` WHERE `state` = 'open'";
        $results = $db->query($query);
        $data = $results->fetch_array(MYSQLI_ASSOC);

        $db->close();

        return ($data != null);
    }

    function get_open_session()
    {
        /*
            Returns the session record that is currently open
        */
        $db = new Database();
        $db->connect();

        $query = "SELECT * FROM `session` WHERE `state` = 'open'";
        $results = $db->query($query);
        $session_record = $results->fetch_array(MYSQLI_ASSOC);

        $db->close();
        return $session_record;
    }

    function session_logging($sid, $api_type, $data, $status, $msg, $action, $execution_time = "0.0")
    {
        /* Helper to log session actions to database */
        $db = new Database();
        $db->connect();
        $date = get_date();
        $query = $query = "INSERT INTO `session_logging` (`sid`, `api_type`, `data_sent`, `status`, `msg`, `action`, `date`, `exec_time`) VALUES ('$sid', '$api_type', '$data', '$status', '$msg', '$action', '$date', '$execution_time')";
        $success = $db->query($query);
        $db->close();
        return $success;
    }

    function get_response_formatted($api_type, $response, $date)
    {
        $line = "=============================";
        $sub_line = "-----------------------------";
        $header = $line . "\n" . "API_TYPE: " . $api_type . "\nDate: $date" . "\n" . $sub_line . "\n" . "Response" . "\n" . $sub_line . "\n";
        $body = "";
        foreach($response as $element)
        {
            $body = $body . $element . "\n";
        }
        $footer = $line ."\n\n\n\n";
        return $header . $body . $footer;
    }

    function log_session_created($uid, $sid)
    {
        /* Adds session to database and records the time it was created*/
        $db = new Database();
        $db->connect();
        $date = get_date();
        $query = $query = "INSERT INTO `session` (`uid`, `sid`, `state`, `create_date`) VALUES ('$uid', '$sid', 'open', '$date')";
        $success = $db->query($query);
        $db->close();
        return $success;
    }

    function log_session_closed($sid)
    {
        $db = new Database();
        $db->connect();
        $date = get_date();
        $query = "UPDATE `session` SET `state` = 'closed', `close_date` = '$date' WHERE `sid` = '$sid'";
        $success = $db->query($query);
        $db->close();
        return $success;
    }

?>
