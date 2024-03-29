<?php 
    include_once "partial/header.php";
    include_once "models/WebFile.php";
    include_once "support/general_functions.php";
?>

<?php
    if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "success"){
        echo '<div class="alert alert-success alert-dismissable">';
            echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
        echo 'Document successfully uploaded!';
        echo '</div>';
    }else if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == "error")
    {
        echo '<div class="alert alert-danger alert-dismissable">';
            echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
        echo 'Document did not upload successfully';
        echo '</div>';
    }
?>
<div class="page-inner">
    <h1 class="page-head-line"> Upload a new file</h1>

    <div class="panel-body">
        <form method="post" enctype="multipart/form-data" action="upload.php">
            <?php
                echo '<input type="hidden" name="uploadedby" value="user@test.mail">';
                echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_FILE_SIZE.'">';
            ?>
            <div class="form-group">

                <label class="control-label col-lg-4"> File Upload </label>
                <div class="fileupload fileupload-new" data-provides="fileupload">

                    <!--Preview-->
                    <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"></div>

                    <!--Buttons -> changes based on embedded file existing or not-->
                    <div class="row">

                        <!--Add a new file / change a file-->
                        <div class="col-md-2 p-0">
                            <span class="btn btn-file btn-primary" style="width: 100%">
                                <span class="fileupload-new">Select File</span> <!--Shows when file doesn't exist-->
                                <span class="fileupload-exists">Change</span> <!--Shows when file does exist-->
                                <input name="userfile" type="file">
                            </span>
                        </div>

                        <!--Remove a file-->
                        <div class="col-md-2 p-0 ms-1">
                            <a href="#" class="btn btn-danger fileupload-exists" style="width: 100%" data-dismiss="fileupload">Remove</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="p-0 col-md-2">
                        <!--
                        <button class="py-2 btn btn-large btn-block btn-success" name="submit" type="submit" value="system">
                            Upload File to File System
                        </button>
                        -->
                        <button class="py-2 btn btn-large btn-block btn-success" name="submit" type="submit" value="db">
                            Upload File
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<?php
if (isset($_POST['submit']))
{
    
    $file = new WebFile($_FILES['userfile']);
    if($_POST['submit'] == 'db'){
        if($file->upload_to_db()){
            redirect("upload.php?msg=success");
        }else{
            redirect("upload.php?msg=error");
        }
    }

}
?>
