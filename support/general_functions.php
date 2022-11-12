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

    function console($data)
    {
        echo "<script> console.log('".$data."'); </script>";
    }

    function get_date()
    {
        date_default_timezone_set('America/Chicago');
        return date("Y-m-d H:i:s");
    }

    function get_date_difference($date1, $date2)
    {
        //format of date = "Y-m-d H:i:s"
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $interval = $date1->diff($date2);

        $duration = "Difference: " . $interval->y . " years, " . $interval->m . " months, " . $interval->d . " days, " . $interval->h . " hours, " . $interval->i . " minutes, " . $interval->s . " seconds";
        return $duration;
    }
?>