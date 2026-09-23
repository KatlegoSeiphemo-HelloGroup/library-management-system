<?php

require 'db.php';

$sql = "
    SELECT
        l.loan_id,
        m.membership_code,
        m.name,
        m.member_type,
        b.title,
        b.age_rating,
        l.loan_date,
        l.due_date,
        l.return_date,
        l.fine_amount,
        CASE
            WHEN l.return_date IS NOT NULL THEN 'Returned'
            WHEN l.due_date < CURDATE() THEN 'Overdue'
            ELSE 'Borrowed'
        END AS loan_status
    FROM Loan l
    JOIN Member m
        ON l.member_id = m.member_id
    JOIN Book b
        ON l.book_id = b.book_id
    ORDER BY l.loan_id
";

$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Loans</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .returned { color: green; font-weight: bold; }
        .borrowed { color: orange; font-weight: bold; }
        .overdue { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>Library Loans</h2>

<table>
    <tr>
        <th>Loan ID</th>
        <th>Membership Code</th>
        <th>Member Name</th>
        <th>Member Type</th>
        <th>Book Title</th>
        <th>Book Rating</th>
        <th>Loan Date</th>
        <th>Due Date</th>
        <th>Return Date</th>
        <th>Fine Amount (R)</th>
        <th>Status</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>

        <?php
        $statusClass = '';
        if ($row['loan_status'] === 'Returned') {
            $statusClass = 'returned';
        } elseif ($row['loan_status'] === 'Borrowed') {
            $statusClass = 'borrowed';
        } elseif ($row['loan_status'] === 'Overdue') {
            $statusClass = 'overdue';
        }
        ?>

        <tr>
            <td><?= $row['loan_id']; ?></td>
            <td><?= htmlspecialchars($row['membership_code']); ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['member_type']); ?></td>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= htmlspecialchars($row['age_rating']); ?></td>
            <td><?= $row['loan_date']; ?></td>
            <td><?= $row['due_date']; ?></td>
            <td><?= $row['return_date'] ?? 'Not Returned'; ?></td>
            <td>R<?= number_format($row['fine_amount'], 2); ?></td>
            <td class="<?= $statusClass; ?>">
                <?= $row['loan_status']; ?>
            </td>
        </tr>

    <?php endwhile; ?>

</table>

</body>
</html>
