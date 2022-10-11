<?php
    include "partial/header.php";
    include_once "support/fileSupport.php"
?>

<div class="h1">
    Welcome to the Home Page!
</div>

<div class="row">
    <div class="col-3">
        Files Available:
        <?php
            $files = getAllFiles();
            for($i=0; $i<count($files); $i++){
                $file = $files[$i];
                $filename = $file['file_name'];
                echo "<div>";
                    echo '<p> <a href="uploads/'.$filename.'">'.$filename.'</a> </p>';
                echo "</div>";
            }
        ?>
    </div>
</div>
