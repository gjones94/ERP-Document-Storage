<?php
    include 'definitions.php';
    mysqli_report(MYSQLI_REPORT_OFF);

    class Database{
        private $connection;

        function connect(){

            $this->connection = new mysqli(HOST, DB_USER, DB_PASSWORD, DATABASE);
            if(mysqli_connect_errno()){
                die("Error connecting to database");
                return FALSE;
            }
            return TRUE;
        }

        function query($query){
            $result = $this->connection->query($query) or die("Error in query\n" . $this->connection->error . "\n");

            return $result;
        }

        function close(){
            $this->connection->close() or die("Error closing connection");
        }

    }
?>
