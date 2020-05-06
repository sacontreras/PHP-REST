<?php
class Librarian {
    // database connection and table name
    private $conn;
    private $table_name = "Librarian";

    // object properties
    public $LibrarianID;
    public $Name;
    public $PhoneNumber;

    // constructor with $db as database connection
    public function __construct($conn){
        $this->conn = $conn;
    }

    // read librarians
    function read() {
        // select all query
        $query = "SELECT * FROM {$this->table_name};";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function insert() {
        $sql = "INSERT INTO {$this->table_name} (LibrarianID, Name, PhoneNumber) VALUES (NULL, :name, :phonenumber);";

        // prepare query statement
        $stmt = $this->conn->prepare($sql);
    
        // bind values
        $stmt->bindParam(":name", $this->Name);
        $stmt->bindParam(":phonenumber", $this->PhoneNumber);

        return $stmt->execute();
    }
}
?>