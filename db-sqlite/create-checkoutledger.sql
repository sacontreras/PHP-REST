CREATE TABLE CheckoutLedger
(
LibrarianID_Librarian INTEGER NOT NULL REFERENCES Librarian (LibrarianID),
CheckoutDate INTEGER NOT NULL,
DueDate INTEGER NOT NULL,
LibraryCardID_Borrower INTEGER NOT NULL REFERENCES Borrower (LibraryCardID),
BookCopyID_BookCopy INTEGER NOT NULL UNIQUE REFERENCES BookCopy (BookCopyID)
);