<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once ('connectdatabase.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Update book info
if (isset($_POST['update_button'])) {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $available_quantity = $_POST['available_quantity'];

    if (!empty($title) && !empty($author) && isset($available_quantity)) {

        $update_book = $pdo->prepare("UPDATE books SET title = :title, author = :author, available_quantity = :available_quantity WHERE book_id = :book_id");

        $update_book->bindParam(':title', $title);
        $update_book->bindParam(':author', $author);
        $update_book->bindParam(':available_quantity', $available_quantity);
        $update_book->bindParam(':book_id', $book_id);

        if ($update_book->execute()) {
            echo "<h2 style='color: green;'>Book updated successfully.</h2>";
            echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
            exit();
        } else {
            echo "<h2 style='color: red;'>Error updating book.</h2>";
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
    <title>Update Book Info</title>
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
    <h1>Update Book Info</h1>

    <!-- Display current book info -->
    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];

        $update_data = $pdo->prepare("SELECT * FROM books WHERE book_id = :book_id");
        $update_data->bindParam(':book_id', $update_id);
        $update_data->execute();

        if ($update_data->rowCount() > 0) {
            $fetch_data = $update_data->fetch();
    ?>
            <form action="updateBooks.php" method="POST">

                <input type="hidden" name="book_id" value="<?php echo $fetch_data['book_id'] ?>">

                <label for="title">Type new title</label>
                <input type="text" id="title" name="title" value="<?php echo $fetch_data['title'] ?>">
                <br>
                <label for="author">Type new author</label>
                <input type="text" id="author" name="author" value="<?php echo $fetch_data['author'] ?>">
                <br>
                <label for="quantity">Type new available quantity</label>
                <input type="number" id="quantity" name="available_quantity" value="<?php echo $fetch_data['available_quantity'] ?>">
                <br>

                <input type="submit" value="Update" name="update_button" class="button">
            </form>
    <?php
        }
    }
    ?>
    
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