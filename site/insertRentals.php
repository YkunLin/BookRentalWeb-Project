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

// Insert new rental
if (isset($_POST['insert_button'])) {
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];
    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];

    if (!empty($user_id) && !empty($book_id) && !empty($rental_date) && !empty($return_date)) {

    $insert_rental = $pdo->prepare("INSERT INTO rentals (user_id, book_id, rental_date, return_date) VALUES (:user_id, :book_id, :rental_date, :return_date)");
    $insert_rental->bindParam(':user_id', $user_id);
    $insert_rental->bindParam(':book_id', $book_id);
    $insert_rental->bindParam(':rental_date', $rental_date);
    $insert_rental->bindParam(':return_date', $return_date);

    if ($insert_rental->execute()) {
        echo "<h2 style='color: green;'>Rental inserted successfully.</h2>";
        echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
        exit();
    } else {
        echo "<h2 style='color: red;'>Error inserting rental.</h2>";
        echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
        exit();
    }
} else {
    echo "<h2 style='color: red;'>Rental information not provided.</h2>";
}
}

// Fetch existing users
$user_query = $pdo->prepare("SELECT id, username FROM registration");
$user_query->execute();
$users = $user_query->fetchAll();

// Fetch available books
$availableBooksQuery = $pdo->prepare("SELECT * FROM books WHERE available_quantity > 0");
$availableBooksQuery->execute();
$availableBooks = $availableBooksQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Rentals Info</title>
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
    <h1>Insert Rentals Info</h1>
            <form action="insertRentals.php" method="POST">

            <label for="user">Select a User:</label>
            <select name="user_id" id="user">
            <?php foreach ($users as $user): ?>
            <option value="<?php echo $user['id']; ?>">
            <?php echo $user['username']; ?>
            </option>
            <?php endforeach; ?>
            </select>
            <br>
            <br>

            <label for="book">Select a Book:</label>
            <select name="book_id" id="book">
            <?php foreach ($availableBooks as $book): ?>
            <option value="<?php echo $book['book_id']; ?>">
            <?php echo $book['title']; ?> by <?php echo $book['author']; ?>
            </option>
            <?php endforeach; ?>
            </select>
            <br>
            <br>

                <label for="rental_date">Choose rental date</label>
                <input type="date" id="rental_date" name="rental_date">
                <br>
                <label for="return_date">Choose return date</label>
                <input type="date" id="return_date" name="return_date">
                <br>

                <input type="submit" value="Insert" name="insert_button" class="button">
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