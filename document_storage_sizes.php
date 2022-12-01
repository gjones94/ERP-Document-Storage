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
    $db->close();
?>

<div class='ml-3 p-0 d-flex'>
    <div style="height: auto;">
        <table style='border-style: solid; border-color: black; border-width: 2px;' class="table-bordered table-responsive table-dark table-striped">
            <thead style="color: white; background-color: black;">
                <tr class="text-center" scope="row">
                    <th class="p-5 h3" colspan="2">
                        Loan Storage Details
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class='p-3'>
                        <?php echo "<p>Total Size of All Documents:</p>"?>
                    </td>
                    <td class='p-3'>
                        <?php echo "<p>" . $size . " Bytes or ". round($MB, 2) . " Megabytes </p>"; ?> 
                    </td>
                </tr>
                <tr>
                    <td class='p-3'>
                        <?php echo "<p>Number of files: </p>"; ?>
                    </td>
                    <td class='p-3' >
                        <?php echo "<p>" . $number_of_files ."</p>"; ?>
                    </td>
                </tr>
                <tr>
                    <td class='p-3'>
                        <?php echo "<p>Average Size of File:</p>";?>
                    </td>
                    <td class='p-3'>
                        <?php echo "<p>" . round($average_size, 2) . " Bytes or " . round($average_size / (1024), 2) . " Kilobytes </p>"; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>