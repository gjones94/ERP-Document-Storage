<?php
    include_once "/var/www/html/doc_storage/models/Database.php";

    $db = new Database();
    $db->connect();
    echo "<div class='h1 text-center'>Loan Storage Report</div>";
    $query = "SELECT SUM(`size`) AS 'sum' FROM `file` WHERE `status` = 'active'";
    $result = $db->query($query);
    $data = $result->fetch_assoc();
    $size = $data['sum'];
    $MB = $size / (1024*1024);

    $query = "SELECT COUNT(`auto_id`) as 'count' FROM `file` WHERE `status` = 'active'";
    $result = $db->query($query);
    $data = $result->fetch_assoc();
    $number_of_files = $data['count'];

    $average_size = $size / $number_of_files;

    echo "<div> Total Size of All Documents: " . $size . " Bytes or ". round($MB, 2) . " Megabytes</div>";
    echo "<div> Number of files: " . $number_of_files . "</div>";
    echo "<div> Average Size of File: " . round($average_size, 2) . " Bytes or " . round($average_size / (1024), 2) . " Kilobytes</div>";

?>