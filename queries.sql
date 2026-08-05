-- Question 1
SELECT
    Member.name,
    Book.title
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
JOIN Book ON Loan.book_id = Book.book_id;

-- Question 2
SELECT
    Member.name,
    COUNT(*) AS books_borrowed
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
GROUP BY Member.name;

-- Question 3
SELECT
    Book.title,
    Author.author_name
FROM Book
JOIN Author ON Book.author_id = Author.author_id;

-- Question 4
SELECT
    Category.category_name,
    COUNT(*) AS total_books
FROM Book
JOIN Category ON Book.category_id = Category.category_id
GROUP BY Category.category_name;

-- Question 5
SELECT
    Member.name,
    COUNT(*) AS total_loans
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
GROUP BY Member.name
ORDER BY total_loans DESC;
``