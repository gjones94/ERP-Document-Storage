<?php

include_once "../models/Database.php";
include_once "general_functions.php";
include_once "definitions.php";

function get_all_files(){
    $sql = "SELECT `auto_id`, `name`, `account`, `upload_date` FROM `file` WHERE `status` = 'active'";
    $db = new Database();
    $db->connect();

    $result = $db->query($sql) or die("Error: Could not retrieve all files");

    $files = $result->fetch_all(MYSQLI_ASSOC);

    return $files;
}

function get_searched_files($field, $searchString){
    $sql = "SELECT `auto_id`, `name`, `account`, `upload_date` FROM `file` WHERE `status` = 'active' and `$field` like '%$searchString%'";
    $db = new Database();
    $db->connect();
    $result = $db->query($sql) or die("Error: Could not retrieve searched files");
    $files = $result->fetch_all(MYSQLI_ASSOC);

    return $files;
}

function delete_file($id){
    $sql = "UPDATE `file` SET `status` = 'inactive' WHERE `auto_id` = ".$id.";";
    $db = new Database();
    $db->connect();
    $db->query($sql);
    return TRUE; 
}

function get_temp_link_to_file($id){
    $db = new Database();
    $db->connect();
    $sql = "SELECT `content`, `ext` FROM `file` WHERE `auto_id` = $id";
    $result = $db->query($sql);
    $data = $result->fetch_array(MYSQLI_ASSOC); //should only get one row of data
    $content = $data['content'];
    $temp_file_name = get_random_string() . "." . $data['ext'];
    
    $fp = fopen(UPLOADS . $temp_file_name, "w");

    if(!$fp){
        console("Temp file could not be created at this time");
    }else{
        $bytes_written = fwrite($fp, $content);

        if(!$bytes_written){
            console("Temp file could not be written to at this time");
        }

        fclose($fp);
    }

    $link = 'uploads/'.$temp_file_name;

    return $link;
}

function get_random_string($num_bytes = 30)
{
    $string = base64_encode(openssl_random_pseudo_bytes($num_bytes));
    $string = str_replace("/", "", $string);
    $string = str_replace("\\", "", $string);
    return $string;
}

function write_to_file($path, $text)
{
    $fp = fopen($path, "a");
    $num = fwrite($fp, $text);
    $success = ($num == strlen($text));
    fclose($fp);
    return $success;
}

function file_already_exists($file_name)
{
    $db = new Database();
    $db->connect();
    $query = "SELECT `auto_id` FROM `file` WHERE `name` = '$file_name' and `status` = 'active'";
    $result = $db->query($query);
    $file = $result->fetch_all(MYSQLI_ASSOC);
    return ($file != NULL);
}

/* SCRIPTS FOR POST CALLS TO THIS FILE FROM AJAX OR FORM*/
if (isset($_POST['DELETE']) && $_POST['DELETE'] == 'yes')
{
    delete_file($_POST['id']);
}

?>
