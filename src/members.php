<?php

require 'db.php';

$result = mysqli_query(
        $conn,
        "SELECT * FROM Member"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Members</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h2>Library Members</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Membership Code</th>
        <th>Name</th>
        <th>Email</th>
        <th>Date of Birth</th>
        <th>Member Type</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $row['member_id']; ?></td>
            <td><?= $row['membership_code']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['date_of_birth']; ?></td>
            <td><?= $row['member_type']; ?></td>
        </tr>
    <?php endwhile; ?>

</table>

</body>
</html>