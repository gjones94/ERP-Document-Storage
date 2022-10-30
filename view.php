<?php
    include_once "support/Database.php";
    include_once "support/file_functions.php";

    if(isset($_POST['fileId'])){
        $id = $_POST['fileId'];
        get_temp_link_to_file($id);
    }
?>