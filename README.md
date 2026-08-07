# Library Management System

## Description

A simple database system for managing books, authors, categories, members, and book loans in a library.

## ER Diagram

The ER diagram for the system is included in:

`database.png`

## Tables

### Author
- author_id (PK)
- author_name

### Category
- category_id (PK)
- category_name

### Book
- book_id (PK)
- title
- author_id (FK)
- category_id (FK)

### Member
- member_id (PK)
- name
- email

### Loan
- loan_id (PK)
- member_id (FK)
- book_id (FK)
- loan_date
- return_date

## Relationships

### One-to-Many Relationships

- One Author can write many Books.
- One Category can contain many Books.
- One Member can have many Loans.
- One Book can appear in many Loan records.

### Many-to-Many Relationship

Members and Books have a many-to-many relationship implemented through the Loan table.

- A Member can borrow many Books.
- A Book can be borrowed by many Members over time.
- The Loan table acts as a junction table that links Members and Books.

## Files Included

### create_tables.sql
Contains all SQL statements used to create the database tables and relationships.

### insert_data.sql
Contains sample data used to populate the database tables.

### queries.sql
Contains five business questions and their SQL queries, demonstrating:
- Joins
- Multi-table joins
- Aggregate functions
- GROUP BY
- HAVING clauses and/or subqueries

## Example Questions Answered

1. Which books have been borrowed and by which members?
2. Which author wrote each book?
3. How many books has each member borrowed?
4. How many books are available in each category?
5. Which members have borrowed more than one book?

## SQL Concepts Demonstrated

- Primary Keys
- Foreign Keys
- One-to-Many Relationships
- Many-to-Many Relationships
- INNER JOIN
- Aggregate Functions
- GROUP BY
- HAVING
- Subqueries

## Author

Katlego Elizabeth Seiphemo

## Repository

Library Management System - SQL and Database Assignment
