<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('connectdatabase.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Insert new book
if (isset($_POST['insert_button'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $available_quantity = $_POST['available_quantity'];

    if (!empty($title) && !empty($author) && isset($available_quantity)) {

        $insert_book = $pdo->prepare("INSERT INTO books (title, author, available_quantity) VALUES (:title, :author, :available_quantity)");

        $insert_book->bindParam(':title', $title);
        $insert_book->bindParam(':author', $author);
        $insert_book->bindParam(':available_quantity', $available_quantity);

        if ($insert_book->execute()) {
            echo "<h2 style='color: green;'>Book inserted successfully.</h2>";
            echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
            exit();
        } else {
            echo "<h2 style='color: red;'>Error inserting book.</h2>";
            echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
            exit();
        }
    } else {
        echo "<h2 style='color: red;'>Please fill out all required fields.</h2>";
        echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert New Book</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h1 {
            text-shadow: 2px 2px;
        }

        body {
            background-image: url("pencil.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size: cover;

            max-width: 500px;
            margin: 0 auto;
            height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>

<body>
    <h1>Insert New Book</h1>

    <!-- Form to insert a new book -->
    <form action="insertBook.php" method="POST">
        <label for="title">Title</label>
        <input type="text" id="title" name="title">
        <br>
        <label for="author">Author</label>
        <input type="text" id="author" name="author">
        <br>
        <label for="quantity">Available Quantity</label>
        <input type="number" id="quantity" name="available_quantity">
        <br>

        <input type="submit" value="Insert Book" name="insert_button" class="button">
    </form>

    <form action='logout.php' method='POST' class="logout-form">
        <input type='submit' value='Logout to the login page'>
        </form>
<br> 
<p>Return to <a href="admin.php">admin page</a>.</p>
<br>

<footer>
    &copy; <?php echo $year; ?> Yankun Lin
</footer>
</body>
</html>