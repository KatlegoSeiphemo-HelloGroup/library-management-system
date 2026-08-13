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
JOIN Author a
    ON b.author_id = a.author_id
JOIN Category c
    ON b.category_id = c.category_id
ORDER BY c.category_name, b.title
";

$result = mysqli_query($conn, $sql);

$currentCategory = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Catalogue</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            margin:0;
            padding:30px;
        }

        h1{
            text-align:center;
            color:#2563eb;
            margin-bottom:40px;
        }

        .category{
            background:#2563eb;
            color:white;
            padding:15px;
            border-radius:10px;
            margin-top:40px;
            margin-bottom:20px;
            font-size:24px;
            font-weight:bold;
        }

        .books-container{
            display:flex;
            flex-wrap:wrap;
            gap:20px;
        }

        .book-card{
            width:280px;
            height:220px;
            background:white;
            border-radius:12px;
            box-shadow:0 4px 10px rgba(0,0,0,0.15);
            position:relative;
            overflow:hidden;
            transition:0.3s;
            cursor:pointer;
        }

        .book-card:hover{
            transform:translateY(-5px);
        }

        .book-front{
            padding:20px;
        }

        .book-title{
            font-size:22px;
            font-weight:bold;
            color:#333;
            margin-bottom:15px;
        }

        .book-author{
            color:#666;
            margin-bottom:15px;
        }

        .badge{
            display:inline-block;
            padding:6px 12px;
            border-radius:20px;
            color:white;
            font-weight:bold;
        }

        .child{
            background:green;
        }

        .teen{
            background:orange;
        }

        .adult{
            background:red;
        }

        .book-info{
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:#2563eb;
            color:white;
            padding:15px;
            box-sizing:border-box;
            opacity:0;
            transition:0.3s;
            overflow-y:auto;
        }

        .book-card:hover .book-info{
            opacity:1;
        }

        .book-info h3{
            margin-top:0;
        }

        .book-info p{
            line-height:1.5;
            font-size:14px;
        }

    </style>

</head>
<body>

<h1>📚 Library Catalogue</h1>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>

<?php if ($currentCategory != $row['category_name']) : ?>

<?php
if ($currentCategory != "") {
    echo "</div>";
}

$currentCategory = $row['category_name'];
?>

<div class="category">
    <?= $currentCategory ?>
</div>

<div class="books-container">

    <?php endif; ?>

    <div class="book-card">

        <div class="book-front">

            <div class="book-title">
                <?= htmlspecialchars($row['title']); ?>
            </div>

            <div class="book-author">
                ✍️ <?= htmlspecialchars($row['author_name']); ?>
            </div>

            <span class="badge <?= strtolower($row['age_rating']); ?>">
                <?= htmlspecialchars($row['age_rating']); ?>
            </span>

        </div>

        <div class="book-info">

            <h3><?= htmlspecialchars($row['title']); ?></h3>

            <p>
                <strong>Author:</strong>
                <?= htmlspecialchars($row['author_name']); ?>
            </p>

            <p>
                <strong>Category:</strong>
                <?= htmlspecialchars($row['category_name']); ?>
            </p>

            <p>
                <strong>Published:</strong>
                <?= htmlspecialchars($row['publication_year']); ?>
            </p>

            <p>
                <strong>Age Rating:</strong>
                <?= htmlspecialchars($row['age_rating']); ?>
            </p>

            <p>
                <?= htmlspecialchars($row['description']); ?>
            </p>

        </div>

    </div>

    <?php endwhile; ?>

</div>

</body>
</html>