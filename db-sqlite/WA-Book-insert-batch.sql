INSERT INTO Book (ISBN, Title, Edition, Author, PublicationDate, Cost)
    VALUES 
        ("1441438", "Alice in Wonderland", NULL, "Lewis Carroll", strftime("%s","1997-05-01"), 7.95),
        ("6006374", "A First Course in Database Systems", "3rd ed.", "Jeffrey Ullman", strftime("%s","2007-10-06"), 99.49),
        ("3523323", "Database System Concepts", NULL, "Abraham Silberschatz", strftime("%s","2010-01-27"), 119.67),
        ("1429477", "Grimm’s Fairy Tales", NULL, "Jacob Grimm", strftime("%s","2004-02-01"), 26.99),
        ("1486025", "A Tale of Two Cities", NULL, "Charles Dickens", strftime("%s","2010-12-01"), 7.95),
        ("1853602", "War and Peace", NULL, "Leo Tolstoy", strftime("%s","2007-09-01"), 7.99),
        ("1904129", "The Scarlet letter", NULL, "Nathaniel Hawthorne", strftime("%s","2009-10-01"), 7.95),
        ("1593832", "Pride and Prejudice", NULL, "Jane Austen", strftime("%s","2004-09-20"), 7.95)
;