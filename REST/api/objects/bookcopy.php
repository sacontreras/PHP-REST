<?php
class BookCopy {
    // database connection and table name
    private $conn;
    private $table_name = "BookCopy";

    // object properties
    public $BookID;
    public $SKU; //SKU
    public $ISBN;
    public $Title;
    public $Edition;
    public $Author;
    public $PublicationDate;
    public $Cost;

    // constructor with $db as database connection
    public function __construct($conn){
        $this->conn = $conn;
    }

    // read librarians
    function read($bookid = null) {
        // select all query
        $query = "
            SELECT
                BookCopyID,
                SKU,
                Book.ISBN, 
                Book.Title, 
                Book.Edition, 
                Book.Author, 
                strftime('%Y-%m-%d', datetime(Book.PublicationDate, 'unixepoch')) AS PublicationDate , 
                Book.Cost
            FROM {$this->table_name}
                JOIN Book ON Book.BookID = {$this->table_name}.BookID_Book
        ";
        if ($bookid != null)
            $query = $query."
                WHERE {$this->table_name}.BookID_Book = {$bookid}
            ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function insert() {
        $sql = "INSERT INTO {$this->table_name} (BookCopyID, SKU, BookID_Book) VALUES (NULL, :sku, :bookid);";

        // prepare query statement
        $stmt = $this->conn->prepare($sql);
    
        // bind values
        $stmt->bindParam(":sku", $this->SKU);
        $stmt->bindParam(":bookid", $this->BookID);

        return $stmt->execute();
    }
}
?>