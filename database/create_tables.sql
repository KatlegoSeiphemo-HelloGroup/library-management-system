USE library_db;

CREATE TABLE Author (
    author_id INT PRIMARY KEY,
    author_name VARCHAR(100)
);

CREATE TABLE Category (
    category_id INT PRIMARY KEY,
    category_name VARCHAR(100)
);

CREATE TABLE Book (
    book_id INT PRIMARY KEY,
    title VARCHAR(100),
    author_id INT,
    category_id INT,
    age_rating VARCHAR(10),
    publication_year INT,
    description TEXT,
    FOREIGN KEY (author_id) REFERENCES Author(author_id),
    FOREIGN KEY (category_id) REFERENCES Category(category_id)
);

CREATE TABLE Member (
    member_id INT PRIMARY KEY,
    membership_code VARCHAR(10),
    name VARCHAR(100),
    email VARCHAR(100),
    member_type VARCHAR(20)
);

CREATE TABLE Loan (
    loan_id INT PRIMARY KEY,
    member_id INT,
    book_id INT,
    loan_date DATE,
    due_date DATE,
    return_date DATE,
    fine_amount DECIMAL(6,2) DEFAULT 0.00,
    FOREIGN KEY (member_id) REFERENCES Member(member_id),
    FOREIGN KEY (book_id) REFERENCES Book(book_id)
);
