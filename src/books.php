<?php

require 'db.php';

$sql = "
SELECT
    Book.title,
    Author.author_name,
    Category.category_name
FROM Book
JOIN Author
    ON Book.author_id = Author.author_id
JOIN Category
    ON Book.category_id = Category.category_id
";

$result = mysqli_query($conn, $sql);

?>

<h2>Books</h2>

<table border="1">
    <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $row['title']; ?></td>
            <td><?= $row['author_name']; ?></td>
            <td><?= $row['category_name']; ?></td>
        </tr>
    <?php endwhile; ?>

</table>