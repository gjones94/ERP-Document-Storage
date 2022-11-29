<?php
    include "models/Database.php";

    echo "<div class='h1 text-center'>Loan Document Count And Averages Report</div>";
    $db = new Database();
    $db->connect();

    $query = "SELECT COUNT(`auto_id`) as 'total_doc_count' FROM `file` WHERE `status`='active'";
    $result = $db->query($query);
    $total_doc_count = $result->fetch_assoc()['total_doc_count'];
    echo "<div> Total Documents: " . $total_doc_count . "</div>";
    
    $query = "SELECT DISTINCT `account` from `file` WHERE `status`='active'";
    $result = $db->query($query);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $number_of_loans = count($data);

    $average_documents_per_loan = round($total_doc_count / $number_of_loans, 2);
    echo "<div> Average documents per loan: " . $average_documents_per_loan . "</div>";

    foreach($data as $record)
    {
        $loan = $record['account'];
        $query = "SELECT COUNT(`auto_id`) as 'file_count' from `file` where `status`='active' and `account` = $loan";
        $result = $db->query($query);
        $data = $result->fetch_assoc();
        $num_documents_for_loan = $data['file_count'];
        $conclusion = "";
        if($num_documents_for_loan > $average_documents_per_loan)
        {
            $conclusion = "is greater than the average";
        }
        else if($num_documents_for_loan == $average_documents_per_loan)
        {
            $conclusion = "is the same as the average";
        }
        else{

            $conclusion = "is less than the average";
        }
        echo "<div> Loan: ". $loan . " has " . $num_documents_for_loan . " documents, which " . $conclusion . "</div>";
    }
    $db->close();
?>