SELECT
	Borrower.Name AS Borrower,
	Borrower.PhoneNumber AS Borrower_Phone,
	Book.Title,
	Book.Edition,
	Book.ISBN,
	BookCopy.SKU,
	strftime('%Y-%m-%d', datetime(CheckoutLedger.CheckoutDate, 'unixepoch')) AS CheckoutDate,
	strftime('%Y-%m-%d', datetime(CheckoutLedger.DueDate, 'unixepoch')) AS DueDate,
	Librarian.Name AS Librarian,
	Librarian.PhoneNumber AS Librarian_Phone
	
FROM CheckoutLedger
	JOIN Librarian ON Librarian.LibrarianID = CheckoutLedger.LibrarianID_Librarian
		JOIN Borrower ON Borrower.LibraryCardID = CheckoutLedger.LibraryCardID_Borrower
			JOIN BookCopy ON BookCopy.SKU = CheckoutLedger.SKU_BookCopy
				JOIN Book ON Book.ISBN = BookCopy.ISBN_Book

ORDER BY CheckoutLedger.CheckoutDate, Borrower.Name ASC
