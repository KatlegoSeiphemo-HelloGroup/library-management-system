-- Question 1:
-- Which members borrowed which books and who wrote those books?

SELECT
    Member.name,
    Book.title,
    Author.author_name
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
JOIN Book ON Loan.book_id = Book.book_id
JOIN Author ON Book.author_id = Author.author_id;


-- Question 2:
-- How many books has each member borrowed?

SELECT
    Member.name,
    COUNT(*) AS books_borrowed
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
GROUP BY Member.name;


-- Question 3:
-- Which author wrote each book in the library?

SELECT
    Book.title,
    Author.author_name
FROM Book
JOIN Author ON Book.author_id = Author.author_id;


-- Question 4:
-- How many books are available in each category?

SELECT
    Category.category_name,
    COUNT(*) AS total_books
FROM Book
JOIN Category ON Book.category_id = Category.category_id
GROUP BY Category.category_name;


-- Question 5:
-- Which members have borrowed more than one book?

SELECT
    Member.name,
    COUNT(*) AS total_loans
FROM Loan
JOIN Member ON Loan.member_id = Member.member_id
GROUP BY Member.name
HAVING COUNT(*) > 1;
