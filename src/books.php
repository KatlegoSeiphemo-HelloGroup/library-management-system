<?php

require 'db.php';

$sql = "
    SELECT
        b.book_id,
        b.title,
        a.author_name,
        c.category_name,
        b.age_rating
    FROM Book b
    JOIN Author a
        ON b.author_id = a.author_id
    JOIN Category c
        ON b.category_id = c.category_id
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Library Books</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
        <th>Age Rating</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $row['book_id']; ?></td>
            <td><?= $row['title']; ?></td>
            <td><?= $row['author_name']; ?></td>
            <td><?= $row['category_name']; ?></td>
            <td><?= $row['age_rating']; ?></td>
        </tr>
    <?php endwhile; ?>

</table>

</body>
</html>