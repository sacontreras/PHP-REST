<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/PHP-REST/REST/api/objects/checkoutledger.php';
  
// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$checkoutledger = new CheckoutLedger($db);
  
// query checkout ledger entries
$result = $checkoutledger->read();

// check if more than 0 record found
if ($result->fetchArray(SQLITE3_ASSOC)){
    $result->reset();

    // checkout ledger array
    $checkoutledger_arr = array();
    $checkoutledger_arr["columns"] = array(
        array("title"=>"Borrower", "data"=>"Borrower"), 
        array("title"=>"Borrower_Phone", "data"=>"Borrower_Phone"), 
        array("title"=>"Title", "data"=>"Title"), 
        array("title"=>"Edition", "data"=>"Edition"), 
        array("title"=>"Author", "data"=>"Author"), 
        array("title"=>"ISBN", "data"=>"ISBN"),
        array("title"=>"SKU", "data"=>"SKU"), 
        array("title"=>"CheckoutDate", "data"=>"CheckoutDate"), 
        array("title"=>"DueDate", "data"=>"DueDate"),
        array("title"=>"Librarian", "data"=>"Librarian"),
        array("title"=>"Librarian_Phone", "data"=>"Librarian_Phone")
    );
    $checkoutledger_arr["records"] = array();
  
    // retrieve our table contents
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        extract($row);
  
        $checkoutledger_item = array(
            "Borrower" => $Borrower,
            "Borrower_Phone" => $Borrower_Phone,
            "Title" => html_entity_decode($Title), //there may be an apostrophe, for example
            "Edition" => html_entity_decode($Edition),
            "Author" => $Author,
            "ISBN" => $ISBN,
            "SKU" => $SKU,
            "CheckoutDate" => $CheckoutDate,
            "DueDate" => $DueDate,
            "Librarian" => $Librarian,
            "Librarian_Phone" => $Librarian_Phone,
        );
  
        array_push($checkoutledger_arr["records"], $checkoutledger_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($checkoutledger_arr);
} else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No checkout ledger entries found.")
    );
}

$db->close();
?>