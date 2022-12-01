<?php
    include_once "/var/www/html/doc_storage/models/Database.php";

    echo "<div class='h1 text-center'>Unique Loan Number Report</div>";
    $db = new Database();
    $db->connect();
    $query = "SELECT DISTINCT `account` FROM `file` WHERE `status` = 'active'";
    $results = $db->query($query);
    $data = $results->fetch_all(MYSQLI_ASSOC);
    $total_loans = count($data);
?>
    
<div class='ml-3 p-0 d-flex'>
    <div style="height: auto;">
        <table style='border-style: solid; border-color: black; border-width: 2px;' class="table-bordered table-responsive table-dark table-striped">
            <thead style="color: white; background-color: black;">
                <tr class="text-center" scope="row">
                    <th class="p-5 h3" colspan="3">
                        Total Unique Loan Numbers: <?php echo $total_loans; ?>
                    </th>
                </tr>
            </thead>
            <thead class="thead-dark">
                <tr class="p-1 text-center" scope="row">
                    <th scope="col">
                        Loan #
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
                    foreach($data as $record)
                    {
                        $loan = $record['account'];
                        echo "<tr scope='row'>";
                            echo "<td class='text-center p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$loan.'</p>';
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
    echo "Total Unique Loan Numbers: " . count($data) . "\n";
    echo "<div style='overflow-y: auto'>";
    foreach($data as $record)
    {
        echo "<div> " . $record['account'] . "</div>";
    }
    echo "</div>";
    */

?>