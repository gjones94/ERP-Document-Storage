<?php
    function redirect ( $uri )
    { ?>
            <script type="text/javascript">
            <!--
            document.location.href="<?php echo $uri; ?>";
            -->
            </script>
        <?php die;
    }

    function console($data){
        echo "<script> console.log('".$data."'); </script>";
    }
?>