<?php
class LibraryDBManager {
   private $sqlite_db_fname = 'booklibrary.db';
   private $sqlite_db_path = 'PHP-REST/db-sqlite';
   private $sqlite_db_root_dir;
   private $sqlite_db_full_path;
   private $create_db_sql_fname = 'createdb-batch.sql';
   private $create_db_sql_full_path;
   private $insert_librarians_sql_fname = 'WA-Librarian-insert-batch.sql';
   private $insert_librarians_sql_full_path;
   private $insert_borrowers_sql_fname = 'WA-Borrower-insert-batch.sql';
   private $insert_borrowers_sql_full_path;
   private $insert_books_sql_fname = 'WA-Book-insert-batch.sql';
   private $insert_books_sql_full_path;
   private $insert_bookcopies_sql_fname = 'WA-BookCopy-insert-batch.sql';
   private $insert_bookcopies_sql_full_path;
   private $insert_checkoutledger_sql_fname = 'WA-CheckoutLedger-insert-batch.sql';
   private $insert_checkoutledger_sql_full_path;
   public $conn;

   function __construct() {
      $this->sqlite_db_root_dir = $_SERVER['DOCUMENT_ROOT'].'/'.$this->sqlite_db_path.'/';
      $this->sqlite_db_full_path = $this->sqlite_db_root_dir.$this->sqlite_db_fname;
      $this->create_db_sql_full_path = $this->sqlite_db_root_dir.$this->create_db_sql_fname;
      $this->insert_librarians_sql_full_path = $this->sqlite_db_root_dir.$this->insert_librarians_sql_fname;
      $this->insert_borrowers_sql_full_path = $this->sqlite_db_root_dir.$this->insert_borrowers_sql_fname;
      $this->insert_books_sql_full_path = $this->sqlite_db_root_dir.$this->insert_books_sql_fname;
      $this->insert_bookcopies_sql_full_path = $this->sqlite_db_root_dir.$this->insert_bookcopies_sql_fname;
      $this->insert_checkoutledger_sql_full_path = $this->sqlite_db_root_dir.$this->insert_checkoutledger_sql_fname;
   }
 
   // get the database connection
   public function getConnection() {
      $this->conn = new SQLite3($this->sqlite_db_full_path);
      if (!$this->conn)
         echo $this->conn->lastErrorMsg();
 
      return $this->conn;
   }

   public function resetDB() {
      $sql_file_exists = file_exists($this->create_db_sql_full_path);
      $db_file_exists = file_exists($this->sqlite_db_full_path);

      if ($sql_file_exists) {
         if ($db_file_exists)
            unlink($this->sqlite_db_full_path);

         if ($this->getConnection()) {//this will create the new db file and connect to it
            //create book table
            $sql = file_get_contents($this->sqlite_db_root_dir.'create-book.sql', false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to create book table");

            //create bookcopy table
            $sql = file_get_contents($this->sqlite_db_root_dir.'create-bookcopy.sql', false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to create bookcopy table");

            //create borrower table
            $sql = file_get_contents($this->sqlite_db_root_dir.'create-borrower.sql', false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to create borrower table");

            //create librarian table
            $sql = file_get_contents($this->sqlite_db_root_dir.'create-librarian.sql', false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to create librarian table");

            //create checkoutledger table
            $sql = file_get_contents($this->sqlite_db_root_dir.'create-checkoutledger.sql', false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to create checkoutledger table");



            //insert librarians default data
            $sql = file_get_contents($this->insert_librarians_sql_full_path, false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to insert librarians default data");

            //insert borrowers default data
            $sql = file_get_contents($this->insert_borrowers_sql_full_path, false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to insert borrowers default data");
            
            //insert books default data
            $sql = file_get_contents($this->insert_books_sql_full_path, false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to insert books default data");

            //insert book copies default data
            $sql = file_get_contents($this->insert_bookcopies_sql_full_path, false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to insert book copies default data");

            //insert checkout ledger default data
            $sql = file_get_contents($this->insert_checkoutledger_sql_full_path, false);
            $stmt = $this->conn->prepare($sql);
            if (!$stmt->execute())
               throw new Exception("failed to insert checkout ledger default data");

            return true;
         }
         throw new Exception("failed to create new database file {$this->sqlite_db_full_path}");
      }
      throw new Exception("{$this->create_db_sql_full_path} does not exist");
   }
}
?>