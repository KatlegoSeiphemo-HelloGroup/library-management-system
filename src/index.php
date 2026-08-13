<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        .header h1 {
            margin-bottom: 10px;
        }

        .cards {
            margin-top: 40px;
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            width: 250px;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h2 {
            margin-bottom: 15px;
        }

        .card a {
            text-decoration: none;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
        }

        .card a:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>📚 Library Management System</h1>
        <p>Manage Members, Books and Loans</p>
    </div>

    <div class="cards">

        <div class="card">
            <h2>👥 Members</h2>
            <p>View library members and membership details.</p>
            <br>
            <a href="members.php">View Members</a>
        </div>

        <div class="card">
            <h2>📋 Books</h2>
            <p>Browse books, categories and age ratings.</p>
            <br>
            <a href="books.php">View Books</a>
        </div>

        <div class="card">
            <h2>📋 Loans</h2>
            <p>Track loans, returns, fines and due dates.</p>
            <br>
            <a href="loans.php">View Loans</a>
        </div>

    </div>

</div>

</body>
</html>