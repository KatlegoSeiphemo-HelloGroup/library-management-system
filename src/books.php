<?php

require 'db.php';

$sql = "
SELECT
    b.book_id,
    b.title,
    b.age_rating,
    b.publication_year,
    b.description,
    a.author_name,
    c.category_name
FROM Book b
JOIN Author a ON b.author_id = a.author_id
JOIN Category c ON b.category_id = c.category_id
ORDER BY c.category_name, b.title
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Catalogue</title>
</head>

<body>

<h1>Library Catalogue</h1>

<table border="1">
    <tr>
        <th>Book ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
        <th>Age Rating</th>
        <th>Publication Year</th>
        <th>Description</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>
            <td><?= htmlspecialchars($row['book_id']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['author_name']) ?></td>
            <td><?= htmlspecialchars($row['category_name']) ?></td>
            <td><?= htmlspecialchars($row['age_rating']) ?></td>
            <td><?= htmlspecialchars($row['publication_year']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
        </tr>

    <?php } ?>

</table>

</body>
</html>
