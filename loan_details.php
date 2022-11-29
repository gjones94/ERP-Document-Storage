<?php
    include_once "models/Database.php";
    //TODO, programatically check to see if these queries are correct, that no loan number has all documents
    //INSERT ONE WITH ALL just to confirm
    $db = new Database();
    $db->connect();

    echo "<div class='h1 text-center'>Loan Detail Report</div>";
    $total_of_each_type = "SELECT `type`, count(`type`) as 'count' FROM `file` where `status` = 'active' group by `type`"; 
    $results = $db->query($total_of_each_type);
    $data = $results->fetch_all(MYSQLI_ASSOC);

    $running_sum = 0;
    echo "<hr style='border-color: white; border-width: 4px;'>";
    echo "<div class='text-center h3'>Counts of each document type</div>";
    echo "<br>";
    foreach($data as $type)
    {
        echo "<div>";
        echo $type['type'] . ": " . $type['count'];
        $running_sum += $type['count'];
        echo "</div>";
    }

    echo "<div>";
    echo "-------------------";
    echo "<div>";
    echo "<div>";
    echo "Total: " . $running_sum;
    echo "<div>";
    echo "<hr style='border-color: white; border-width: 4px;'>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    $loans_with_all_documents = "SELECT DISTINCT `account`" .
                                "FROM `file` " .
                                "WHERE `account` in (" .
                                "SELECT `account`" .
                                "FROM `file`" . 
                                "GROUP BY `account`" . 
                                "HAVING (COUNT(DISTINCT `type`)) = (SELECT COUNT(DISTINCT `type`) FROM `file`))";
    $result = $db->query($loans_with_all_documents);
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo "<hr style='border-color: white; border-width: 4px;'>";
    echo "<div class='text-center h3'>Loans with all documents: " . count($data) . "</div>";
    echo "<br>";
    if($data == NULL)
    {
        echo "No loans have all documents";
    }else{
        foreach($data as $loan_with_all_documents)
        {
            echo "<div>";
            echo "Loan: " . $loan_with_all_documents['account'];
            echo "</div>";
        }
    }
    echo "<hr style='border-color: white; border-width: 4px;'>";
    echo "<br>";
    echo "<br>";
    echo "<br>";


    $loans_missing_documents = "SELECT DISTINCT `account`" .
                               "FROM `file` " .
                               "WHERE `account` in (" .
                               "SELECT `account`" .
                               "FROM `file`" . 
                               "GROUP BY `account`" . 
                               "HAVING (COUNT(DISTINCT `type`)) < (SELECT COUNT(DISTINCT `type`) FROM `file`))";
    $result = $db->query($loans_missing_documents);
    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo "<hr style='border-color: white; border-width: 4px;'>";
    echo "<div class='text-center h3'>Loans with missing documents: " . count($data) . "</div>";
    echo "<br>";
    foreach($data as $loan_missing_files)
    {
        $loan_number = $loan_missing_files['account'];
        $query_missing_doc_types_for_loan = "SELECT DISTINCT `type` FROM `file` WHERE `type` NOT IN (SELECT `type` FROM `file` WHERE `account` = '$loan_number')";
        $results = $db->query($query_missing_doc_types_for_loan);
        $missing_types = $results->fetch_all(MYSQLI_ASSOC);
        echo "<div>";
        echo "</div>";
        echo "Loan Number: " . $loan_number;
        echo "<div>";
        echo "Missing documents:";
        echo "</div>";
        foreach($missing_types as $type)
        {
            echo"<div class='ml-4'>" . $type['type'] . "</div>";
        }
        echo "</div>";
        echo "<hr style='border-color: white; border-width: 1px;'>";
        echo "<br>";
        echo "<br>";
    }
?>