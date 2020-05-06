<?php
class Book {
    // database connection and table name
    private $conn;
    private $table_name = "Book";

    // object properties
    public $ISBN; //ISBN
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
    function read() {
        // select all query
        $query = "
            SELECT
                BookID, 
                ISBN, 
                Title, 
                Edition, 
                Author, 
                strftime('%Y-%m-%d', datetime(PublicationDate, 'unixepoch')) AS PublicationDate , 
                Cost
            FROM {$this->table_name};
        ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function insert() {
        $sql = "INSERT INTO {$this->table_name} (BookID, ISBN, Title, Edition, Author, PublicationDate, Cost) VALUES (NULL, :isbn, :title, :edition, :author, strftime('%s', :publicationdate), :cost);";

        // prepare query statement
        $stmt = $this->conn->prepare($sql);
    
        // bind values
        $stmt->bindParam(":isbn", $this->ISBN);
        $stmt->bindParam(":title", $this->Title);
        $stmt->bindParam(":edition", $this->Edition);
        $stmt->bindParam(":author", $this->Author);
        $publicationdate = date('Y-m-d', strtotime($this->PublicationDate));
        $stmt->bindParam(":publicationdate", $publicationdate);
        $stmt->bindParam(":cost", $this->Cost);

        return $stmt->execute();
    }
}
?>