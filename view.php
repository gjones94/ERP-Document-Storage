<?php
    include_once "models/Database.php";
    include_once "support/file_functions.php";

    if(isset($_POST['fileId'])){
        $id = $_POST['fileId'];
        $link = get_temp_link_to_file($id);
        echo '<p>File link will expire in 5 minutes </p>';
        echo '<hr>';
        echo '<p>File: <a href="'.$link.'">'."Link".'</a></p>';
    }
?>
