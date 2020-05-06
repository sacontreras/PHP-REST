INSERT INTO CheckoutLedger (LibrarianID_Librarian, CheckoutDate, DueDate, LibraryCardID_Borrower, BookCopyID_BookCopy)
    VALUES 
	    (2, strftime('%s','2010-12-01'), strftime('%s','2011-02-01'), 1, 1),
        (4, strftime('%s','2010-12-01'), strftime('%s','2011-02-01'), 2, 2),
        (2, strftime('%s','2010-12-01'), strftime('%s','2011-02-01'), 1, 3),
        (3, strftime('%s','2010-12-01'), strftime('%s','2011-02-01'), 1, 13),
        (3, strftime('%s','2010-12-01'), strftime('%s','2011-02-01'), 2, 4),
        (1, strftime('%s','2010-12-01'), strftime('%s','2011-02-01'), 6, 11)
;