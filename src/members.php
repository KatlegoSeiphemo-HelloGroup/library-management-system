<?php

require 'db.php';

$result = mysqli_query(
    $conn,
    "SELECT * FROM Member"
);

?>

<h2>Members</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $row['member_id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['email']; ?></td>
        </tr>
    <?php endwhile; ?>

</table>