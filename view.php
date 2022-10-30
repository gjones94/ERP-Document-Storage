<?php
    include_once "support/Database.php";
    include_once "support/file_functions.php";

    if(isset($_POST['fileId'])){
        $id = $_POST['fileId'];
        $link = get_temp_link_to_file($id);
        echo '<p>File: <a href="'.$link.'">'."Link".'</a></p>';
    }
?>