<?php
class CheckoutLedger {
    // database connection and table name
    private $conn;
    private $table_name = "CheckoutLedger";

    // object properties
    public $LibraryCardID;
    public $Borrower;
    public $Borrower_Phone;
    public $Title;
    public $Edition;
    public $Author;
    public $ISBN;
    public $BookCopyID;
    public $SKU;
    public $CheckoutDate;
    public $DueDate;
    public $LibrarianID;
    public $Librarian;
    public $Librarian_Phone;

    // constructor with $db as database connection
    public function __construct($conn){
        $this->conn = $conn;
    }

    // read librarians
    function read() {
        // select all query
        $query = "
            SELECT
                Borrower.Name AS Borrower,
                Borrower.PhoneNumber AS Borrower_Phone,
                Book.Title,
                Book.Edition,
                Book.Author,
                Book.ISBN,
                BookCopy.SKU,
                strftime('%Y-%m-%d', datetime({$this->table_name}.CheckoutDate, 'unixepoch')) AS CheckoutDate,
                strftime('%Y-%m-%d', datetime({$this->table_name}.DueDate, 'unixepoch')) AS DueDate,
                Librarian.Name AS Librarian,
                Librarian.PhoneNumber AS Librarian_Phone
        
            FROM {$this->table_name}
                JOIN Librarian ON Librarian.LibrarianID = {$this->table_name}.LibrarianID_Librarian
                    JOIN Borrower ON Borrower.LibraryCardID = {$this->table_name}.LibraryCardID_Borrower
                        JOIN BookCopy ON BookCopy.BookCopyID = {$this->table_name}.BookCopyID_BookCopy
                            JOIN Book ON Book.BookID = BookCopy.BookID_Book
    
            ORDER BY {$this->table_name}.CheckoutDate, Borrower.Name ASC
        ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }

    function insert() {
        $sql = "
            INSERT INTO {$this->table_name} (LibrarianID_Librarian, CheckoutDate, DueDate, LibraryCardID_Borrower, BookCopyID_BookCopy) 
                VALUES 
                    (:librarianid, strftime('%s',:checkoutdate), strftime('%s',:duedate), :librarycardid, :bookcopyid)
        ";

        // prepare query statement
        $stmt = $this->conn->prepare($sql);
    
        // bind values
        $stmt->bindParam(":librarianid", $this->LibrarianID);
        $stmt->bindParam(":checkoutdate", $this->CheckoutDate);
        $stmt->bindParam(":duedate", $this->DueDate);
        $stmt->bindParam(":librarycardid", $this->LibraryCardID);
        $stmt->bindParam(":bookcopyid", $this->BookCopyID);

        return $stmt->execute();
    }
}
?>