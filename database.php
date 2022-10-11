
<?php
    class Database{

        private $connection;

        function __construct()
        {
            echo 'HELLO';
        }

        function connect(){
            $this->connection = new mysqli('localhost', 'webuser', 'W3bUs3r123*', 'doc_storage');
            if(mysqli_connect_errno()){
                die("Error connecting to database");
                return FALSE;
            }
            return TRUE;
        }

        function query($query){
            return $this->connection->query($query) or die("Error in query $query: " . $this->connection->error);
        }

    }
?>