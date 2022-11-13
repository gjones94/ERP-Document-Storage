<?php
    include_once "middleware_functions.php";
    include_once ".credentials.php";
    include_once "../support/general_functions.php";
    include_once "../support/file_functions.php";
    include_once "request_files.php";


    $msg = "/storage/files/lvs888/71640293-Other-20221110_17_47_40.pdf,/storage/files/lvs888/71640293-Title-20221110_17_47_40.pdf,/storage/files/lvs888/71640293-Closing-20221110_17_47_41.pdf,/storage/files/lvs888/71640293-Closing-20221110_17_47_42.pdf";
    $files = explode(",", $msg);

    request_files($files);

?>
