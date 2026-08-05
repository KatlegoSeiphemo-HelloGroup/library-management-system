SELECT
    Member.name,
    Book.title
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
JOIN Book ON Loan.book_id = Book.book_id;


SELECT
    Member.name,
    COUNT(*) AS books_borrowed
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
GROUP BY Member.name;


SELECT
    Book.title,
    Author.author_name
FROM Book
JOIN Author ON Book.author_id = Author.author_id;


SELECT
    Category.category_name,
    COUNT(*) AS total_books
FROM Book
JOIN Category ON Book.category_id = Category.category_id
GROUP BY Category.category_name;


SELECT
    Member.name,
    COUNT(*) AS total_loans
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
GROUP BY Member.name
ORDER BY total_loans DESC;
``
