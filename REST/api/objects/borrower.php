<?php
class Borrower {
    // database connection and table name
    private $conn;
    private $table_name = "Borrower";

    // object properties
    public $LibraryCardID;
    public $Name;
    public $Address;
    public $PostalCode;
    public $PhoneNumber;
    public $MembershipDate;

    // constructor with $db as database connection
    public function __construct($conn){
        $this->conn = $conn;
    }

    // read librarians
    function read() {
        // select all query
        $query = "
            SELECT 
                LibraryCardID, 
                Name, 
                Address, 
                PostalCode, 
                PhoneNumber, 
                strftime('%Y-%m-%d', datetime(MembershipDate, 'unixepoch')) AS MembershipDate 
            FROM {$this->table_name};
        ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function insert() {
        $sql = "INSERT INTO {$this->table_name} (LibraryCardID, Name, Address, PostalCode, PhoneNumber, MembershipDate) VALUES (NULL, :name, :address, :postalcode, :phonenumber, strftime('%s', :membershipdate));";

        // prepare query statement
        $stmt = $this->conn->prepare($sql);
    
        // bind values
        $stmt->bindParam(":name", $this->Name);
        $stmt->bindParam(":address", $this->Address);
        $stmt->bindParam(":postalcode", $this->PostalCode);
        $stmt->bindParam(":phonenumber", $this->PhoneNumber);
        date_default_timezone_set('America/Los_Angeles');
        $membershipdate = date('Y-m-d', time());
        $stmt->bindParam(":membershipdate", $membershipdate);  

        return $stmt->execute();
    }
}
?>