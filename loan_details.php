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
?>

<br>
<br>

<div class='ml-3 p-0 d-flex'>
    <div style="height: auto;">
        <table style='border-style: solid; border-color: black; border-width: 2px;' class="table-bordered table-responsive table-dark table-striped">
            <thead style="color: white; background-color: black;">
                <tr class="text-center" scope="row">
                    <th class="p-5 h3" colspan="5">
                        Counts of each document type
                    </th>
                </tr>
            </thead>
            <thead class="thead-dark">
                <tr class="p-1 text-center" scope="row">
                    <th scope="col">
                        Type
                    </th>

                    <th scope="col">
                        Count
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(count($data) == 0){
                    echo "<thead class='thead-dark'>";
                        echo "<tr class='text-center px-1'scope='row'>";
                            echo "<th class='h3 px-1' style=' background-color: rgb(30,30,30);' colspan='2'>";
                                echo "No files available for viewing";
                            echo "</th>";
                        echo "</tr>";
                    echo "</thead";
                }else{
                    foreach($data as $type)
                    {
                        echo "<tr scope='row'>";
                            echo "<td class='text-center p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$type['type'].'</p>';
                            echo "</td>";
                            echo "<td class='text-center p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$type['count'].'</p>';
                            echo "</td>";
                        echo "</tr>";
                        $running_sum += $type['count']; //TODO put this in last row of table
                    }
                    echo "<tr style='background-color: #111;' scope='row'>";
                        echo "<td class='text-center p-3'>";
                            echo '<p class="m-0 py-2 px-2">Total</p>';
                        echo "</td>";
                        echo "<td class='text-center p-3'>";
                            echo '<p class="m-0 py-2 px-2">'.$running_sum.'</p>';
                        echo "</td>";
                    echo "</tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<br>
<br>

<?php
    $loans_with_all_documents = "SELECT DISTINCT `account`" .
                                "FROM `file` " .
                                "WHERE `account` in (" .
                                "SELECT `account`" .
                                "FROM `file`" . 
                                "GROUP BY `account`" . 
                                "HAVING (COUNT(DISTINCT `type`)) = (SELECT COUNT(DISTINCT `type`) FROM `file`))";
    $result = $db->query($loans_with_all_documents);
    $data = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class='ml-3 p-0 d-flex'>
    <div style="height: auto;">
        <table style='border-style: solid; border-color: black; border-width: 2px;' class="table-bordered table-responsive table-dark table-striped">
            <thead style="color: white; background-color: black;">
                <tr class="text-center" scope="row">
                    <th class="p-5 h3" colspan="5">
                        Loans with all documents
                    </th>
                </tr>
            </thead>
            <thead class="thead-dark">
                <tr class="p-1 text-center" scope="row">
                    <th scope="col">
                        Loan Number
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
                if(count($data) == 0){
                    echo "<thead class='thead-dark'>";
                        echo "<tr class='text-center px-5'scope='row'>";
                            echo "<th class='h3 px-5' style=' background-color: rgb(30,30,30);' colspan='2'>";
                                echo "No loans have all documents";
                            echo "</th>";
                        echo "</tr>";
                    echo "</thead";
                }else{
                    foreach($data as $loan)
                    {
                        echo "<tr scope='row'>";
                            echo "<td class='text-center p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$loan['account'].'</p>';
                            echo "</td>";
                        echo "</tr>";
                    }
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<br>
<br>

<?php 
    $loans_missing_documents = "SELECT DISTINCT `account`" .
                               "FROM `file` " .
                               "WHERE `account` in (" .
                               "SELECT `account`" .
                               "FROM `file`" . 
                               "GROUP BY `account`" . 
                               "HAVING (COUNT(DISTINCT `type`)) < (SELECT COUNT(DISTINCT `type`) FROM `file`))";
    $result = $db->query($loans_missing_documents);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $number_of_loans_missing_documents = count($data);
?>

<div class='ml-3 p-0 d-flex'>
    <div style="height: auto;">
        <table style='border-style: solid; border-color: black; border-width: 2px;' class="table-bordered table-responsive table-dark table-striped">
            <thead style="color: white; background-color: black;">
                <tr class="text-center" scope="row">
                    <th class="p-5 h3" colspan="2">
                        Loans with Missing Documents
                    </th>
                </tr>
            </thead>
            <thead style="color: white; background-color: #111;">
                <tr>
                    <th class="p-1 h4 text-center" colspan="2">
                        <?php
                            echo '<p class="m-0 py-2 px-2"> Total Loans Missing Documents: '.$number_of_loans_missing_documents.' </p>';
                        ?>
                    </th>
                </tr>
            </thead>
            <thead class="thead-dark">
                <tr class="p-1 text-center" scope="row">
                    <th scope="col">
                        Loan Number
                    </th>

                    <th scope="col">
                        Missing Documents
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php
                if($number_of_loans_missing_documents == 0){
                    echo "<thead class='thead-dark'>";
                        echo "<tr class='text-center px-2'scope='row'>";
                            echo "<th class='h3 px-2 py-2' style=' background-color: rgb(30,30,30);' colspan='2'>";
                                echo "No files available for viewing";
                            echo "</th>";
                        echo "</tr>";
                    echo "</thead";
                }else{
                    foreach($data as $loan)
                    {
                        $loan_number = $loan['account'];
                        $query_missing_doc_types_for_loan_number = "SELECT DISTINCT `type` FROM `file` WHERE `type` NOT IN (SELECT `type` FROM `file` WHERE `account` = '$loan_number')";
                        $results = $db->query($query_missing_doc_types_for_loan_number);
                        $missing_documents = $results->fetch_all(MYSQLI_ASSOC);
                        echo "<tr scope='row'>";
                            echo "<td class='text-center p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$loan_number.'</p>';
                            echo "</td>";
                            echo "<td class='text-center p-3'>";
                                foreach($missing_documents as $document)
                                {
                                    echo '<p class="m-0 py-2 px-2">'.$document['type'].'</p>';
                                }
                            echo "</td>";
                        echo "</tr>";
                    }
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

    <?php
    /*
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
    */
    ?>
