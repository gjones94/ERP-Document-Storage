
<?php
    include 'definitions.php';

    class Database{
        private $connection;

        function connect(){

            $this->connection = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if(mysqli_connect_errno()){
                die("Error connecting to database");
                return FALSE;
            }
            return TRUE;
        }

        function query($query){
            $result = $this->connection->query($query) or die("Error in query" . $this->connection->error);
            return $result;
        }

    }
?>
