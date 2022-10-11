
<?php
    include 'definitions.php';

    class Database{
        private $connection;

        function connect(){

            $this->connection = new mysqli(HOST, USER, PASSWORD, DATABASE);
            //$this->connection = new mysqli('localhost', 'webuser', 'W3bUs3r123*', 'doc_storage');
            if(mysqli_connect_errno()){
                die("Error connecting to database");
                return FALSE;
            }
            return TRUE;
        }

        function query($query){
            //$result = $this->connection->query($query)->fetch_array(MYSQLI_ASSOC);
            $result = $this->connection->query($query);
            return $result;
        }

        function getConnection(){
            return $this->connection;
        }

    }
?>
