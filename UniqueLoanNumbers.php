<?php
    include_once "/var/www/html/doc_storage/models/Database.php";

    $db = new Database();
    $db->connect();
    $query = "SELECT DISTINCT `account` FROM `file` WHERE `status` = 'active'";
    $results = $db->query($query);
    $data = $results->fetch_all(MYSQLI_ASSOC);
    echo "Total Unique Loan Numbers: " . count($data) . "\n";
    echo "<div style='overflow-y: auto'>";
    foreach($data as $record)
    {
        echo "<div> " . $record['account'] . "</div>";
    }
    echo "</div>";

?>