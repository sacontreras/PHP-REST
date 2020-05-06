<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once $_SERVER['DOCUMENT_ROOT'].'/DF/REST/database/db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/DF/REST/api/objects/borrower.php';
  
// instantiate database and product object
$dbmgr = new LibraryDBManager();
$db = $dbmgr->getConnection();
  
// initialize object
$borrower = new Borrower($db);
  
// query borrowers
$result = $borrower->read();

// check if more than 0 record found
if ($result->fetchArray(SQLITE3_ASSOC)){
    $result->reset();

    // borrowers array
    $borrowers_arr = array();
    $borrowers_arr["columns"] = array(
        array("title"=>"LibraryCardID", "data"=>"LibraryCardID"), 
        array("title"=>"Name", "data"=>"Name"), 
        array("title"=>"Address", "data"=>"Address"), 
        array("title"=>"PostalCode", "data"=>"PostalCode"), 
        array("title"=>"PhoneNumber", "data"=>"PhoneNumber"), 
        array("title"=>"MembershipDate", "data"=>"MembershipDate")
    );
    $borrowers_arr["records"] = array();
  
    // retrieve our table contents
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        extract($row);
  
        $borrower_item = array(
            "LibraryCardID" => $LibraryCardID,
            "Name" => $Name,
            "Address" => $Address,
            "PostalCode" => $PostalCode,
            "PhoneNumber" => $PhoneNumber,
            "MembershipDate" => $MembershipDate
        );
  
        array_push($borrowers_arr["records"], $borrower_item);
    }
  
    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    echo json_encode($borrowers_arr);
} else {
  
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("message" => "No borrowers found.")
    );
}

$db->close();
?>