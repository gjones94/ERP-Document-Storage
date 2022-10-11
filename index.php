<?php
    include "partial/header.php";
    include_once "support/fileSupport.php";
    include_once "support/userSupport.php";
?>

<div class="h1">
    Welcome to the Home Page!
</div>

<div class="row p-0 m-0">
    <div>
        <table class="table-bordered table-dark">
            <thead class="thead-dark">
                <tr class="text-center" scope="row">
                    <th class="h3" colspan="4">
                        Files Available
                    </th>
                </tr>
            </thead>

            <thead class="thead-dark">
                <tr class="text-center" scope="row">
                    <th scope="col">
                        Name
                    </th>

                    <th scope="col">
                        Owner
                    </th>

                    <th scope="col">
                        Date
                    </th>

                    <th scope="col">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $files = getAllFiles();
                    for($i=0; $i<count($files); $i++){
                        //TEMP
                        $current_user = 1;
                        $file = $files[$i];
                        $file_id = $file['auto_id'];
                        $name = $file['file_name'];
                        $owner = $file['file_owner'];
                        $size = $file['file_size'];
                        $date = $file['file_upload_date'];
                        echo "<tr scope='row'>";
                            echo "<td class='d-flex flex-column align-items-center py-3'>";
                                echo '<p class="m-0 py-2 px-2"> <a href="uploads/'.$name.'">'.$name.'</a> </p>';
                            echo "</td>";
                            echo "<td class='p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$owner.'</p>';
                            echo "</td>";
                            echo "<td class='p-3'>";
                                echo '<p class="m-0 py-2 px-2">'.$date.'</p>';
                            echo "</td>";
                            echo "<td class='p-3'>";
                                echo "<button class='me-2 btn btn-primary' style='cursor: pointer' onclick='deleteFile(".$file['auto_id'].")'>DELETE</button>";
                                echo '<form method="POST" action="support/userSupport.php">';
                                    echo "<input type='hidden' name='userId' value='".$current_user."'>";
                                    echo "<input type='hidden' name='fileId' value='".$file_id."'>";
                                    echo "<button class='ms-2 btn btn-primary' style='cursor: pointer' type='submit' name='view' value='view'>VIEW</button>";
                                echo '</form>';
                            echo "</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
