<?php
    include_once "partial/header.php";
    include_once "models/WebFile.php";
    include_once "support/general_functions.php";
    include_once "support/file_functions.php";
?>

<div class="h1">
    Welcome to the Home Page
</div>
<hr class='py-2'>

<?php
    if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "searchTypeError"){
        echo '<div class="alert alert-danger alert-dismissable">';
            echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
            echo 'Search Error';
        echo '</div>';
    }
?>

<form action="index.php" method="GET">
    <div class="form-group col-4 d-flex">
        <label class='mr-2 h3'> Search: </label>
        <input type="text" class="form-control" name="searchString">
        <select class='ml-2' name="searchType">
            <option value="name"> Name</option>
            <option value="uploadBy">Upload User</option>
            <option value="uploadDate"> Upload Date</option>
            <option value="all"> All</option>
        </select>
        <button class='btn btn-primary ml-2' type="submit" name="search" value="searched">Search</button>
    </div>
    <hr>
</form>

<div class="row p-0 m-0">
    <div style="height: 500px; overflow-y: auto;">
        <table class="table-bordered table-responsive table-dark">
            <thead class="thead-dark">
                <tr class="text-center" scope="row">
                    <th class="h3" colspan="5">
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

                    <th scope="col" colspan="2">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($_REQUEST['search']) && $_REQUEST['search'] == "searched"){
                        $string = $_REQUEST['searchString'];
                        $searchType = $_REQUEST['searchType'];
                        switch($searchType){
                            case "name":
                                $files = get_searched_files('name', $string);
                                break;
                            case "uploadBy":
                                $files = get_searched_files('account', $string);
                                break;
                            case "uploadDate":
                                $files = get_searched_files('upload_date', $string);
                                break;
                            case "all":
                                $files = get_all_files(); 
                                break;
                            default:
                                redirect("index.php?msg=searchTypeError");
                                break;
                        }
                    }else{
                        $files = get_all_files();
                    }
                    if(count($files) == 0){
                        echo "<thead class='thead-dark'>";
                            echo "<tr class='text-center px-4'scope='row'>";
                                echo "<th class='h3 px-4' style=' background-color: rgb(30,30,30); min-width: 40vw' colspan='5'>";
                                    echo "No files available for viewing";
                                echo "</th>";
                            echo "</tr>";
                        echo "</thead";
                    }else{
                        for($i=0; $i<count($files); $i++){
                            //TEMP
                            $current_user = 1;
                            $file = $files[$i];
                            $file_id = $file['auto_id'];
                            $name = $file['name'];
                            $owner = $file['account'];
                            $date = $file['upload_date'];
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
                                    echo '<form method="POST" action="view.php">';
                                        echo "<input type='hidden' name='fileId' value='".$file_id."'>";
                                        echo "<button class='ms-2 btn btn-primary' style='cursor: pointer' type='submit' name='view' value='view'>VIEW</button>";
                                    echo '</form>';
                                echo "</td>";
                                echo "<td class='p-3'>";
                                    echo "<button class='me-2 btn btn-primary' style='cursor: pointer' onclick='deleteFile(".$file['auto_id'].")'>DELETE</button>";
                                echo "</td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
