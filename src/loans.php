<?php

require 'db.php';

$sql = "
SELECT
    Member.name,
    Book.title,
    Loan.loan_date,
    Loan.due_date,
    Loan.return_date
FROM Loan
JOIN Member
    ON Loan.member_id = Member.member_id
JOIN Book
    ON Loan.book_id = Book.book_id
";

$result = mysqli_query($conn, $sql);

?>

<h2>Loans</h2>

<table border="1">
    <tr>
        <th>Member</th>
        <th>Book</th>
        <th>Loan Date</th>
        <th>Due Date</th>
        <th>Return Date</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $row['name']; ?></td>
            <td><?= $row['title']; ?></td>
            <td><?= $row['loan_date']; ?></td>
            <td><?= $row['due_date']; ?></td>
            <td><?= $row['return_date']; ?></td>
        </tr>
    <?php endwhile; ?>

</table>