<?php
    include "models/Database.php";

    echo "<div class='h1 text-center'>Loan Document Count And Averages Report</div>";
    $db = new Database();
    $db->connect();

    $query = "SELECT COUNT(`auto_id`) as 'total_doc_count' FROM `file` WHERE `status`='active'";
    $result = $db->query($query);
    $total_doc_count = $result->fetch_assoc()['total_doc_count'];
    
    $query = "SELECT DISTINCT `account` from `file` WHERE `status`='active'";
    $result = $db->query($query);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $number_of_loans = count($data);

    $average_documents_per_loan = round($total_doc_count / $number_of_loans, 2);
?>

<div class='ml-3 p-0 d-flex'>
    <div style="height: auto;">
        <table style='border-style: solid; border-color: black; border-width: 2px;' class="table-bordered table-responsive table-dark table-striped">
            <thead style="color: white; background-color: black;">
                <tr class="text-center" scope="row">
                    <th class="p-5 h3" colspan="3">
                        Total Documents: <?php echo $total_doc_count; ?>
                    </th>
                </tr>
                <tr class="text-center" scope="row">
                    <th style='background-color: #111;' class="p-5 h3" colspan="3">
                        Average Documents Per Loan: <?php echo $average_documents_per_loan; ?>
                    </th>
                </tr>
            </thead>
            <thead class="thead-dark">
                <tr class="p-1 text-center" scope="row">
                    <th scope="col">
                        Loan #
                    </th>

                    <th scope="col">
                        Number of Documents
                    </th>

                    <th scope="col">
                        Compared to Average
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(count($data) == 0){
                    echo "<thead class='thead-dark'>";
                        echo "<tr class='text-center px-1'scope='row'>";
                            echo "<th class='h3 px-1' style=' background-color: rgb(30,30,30);' colspan='3'>";
                                echo "No files available for viewing";
                            echo "</th>";
                        echo "</tr>";
                    echo "</thead";
                }else{
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
                            $conclusion = "greater than the average";
                        }
                        else if($num_documents_for_loan == $average_documents_per_loan)
                        {
                            $conclusion = "same as the average";
                        }
                        else{

                            $conclusion = "less than the average";
                        }
                        echo "<tr scope='row'>";
                            echo "<td class='text-center p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$loan.'</p>';
                            echo "</td>";
                            echo "<td class='text-center p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$num_documents_for_loan.'</p>';
                            echo "</td>";
                            echo "<td class='text-center p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$conclusion.'</p>';
                            echo "</td>";
                        echo "</tr>";
                    }
                }
                $db->close();
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php
    /*
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
    */
?>