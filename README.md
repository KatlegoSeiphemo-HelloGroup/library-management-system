# Library Management System

## Description

The Library Management System is a simple PHP and MySQL application designed to manage books, authors, categories, members, and book loans within a library. The system demonstrates database design concepts, SQL queries, and PHP database integration.

## Features

- Manage books and their authors
- Organize books by categories
- Manage library members
- Track borrowed and returned books
- View loan records
- Demonstrate SQL relationships and queries

## Database Structure

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
- The Loan table acts as a junction table linking Members and Books.

## Project Structure

```text
library-management-system/
│
├── database/
│   ├── create_tables.sql
│   ├── insert_data.sql
│   └── queries.sql
│
├── src/
│   ├── db.php
│   ├── index.php
│   ├── books.php
│   ├── members.php
│   └── loans.php
│
├── .gitignore
└── README.md
