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

// Update rental
if (isset($_POST['update_button'])) {
    $rental_id = $_POST['rental_id'];
    $book_id = $_POST['book_id'];
    $rental_date = $_POST['rental_date'];
    $return_date = $_POST['return_date'];

    if (!empty($book_id) && !empty($rental_date) && !empty($return_date)) {

    $update_rental = $pdo->prepare("UPDATE rentals SET book_id = :book_id, rental_date = :rental_date, return_date = :return_date WHERE rental_id = :rental_id");
    $update_rental->bindParam(':book_id', $book_id);
    $update_rental->bindParam(':rental_date', $rental_date);
    $update_rental->bindParam(':return_date', $return_date);
    $update_rental->bindParam(':rental_id', $rental_id);

    if ($update_rental->execute()) {
        echo "<h2 style='color: green;'>Rental info inserted successfully.</h2>";
        echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
        exit();
    } else {
        echo "<h2 style='color: red;'>Error insert rental.</h2>";
        echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
        exit();
    }
} else {
    echo "<h2 style='color: red;'>Please fill out all required fields.</h2>";
        echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
        exit();
}
}


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
    <title>Update Rentals Info</title>
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
    <h1>Update Rentals Info</h1>

    <!-- Display current rental info -->
    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];

        $update_data = $pdo->prepare("SELECT * FROM rentals WHERE rental_id = :rental_id");
        $update_data->bindParam(':rental_id', $update_id);
        $update_data->execute();

        if ($update_data->rowCount() > 0) {
            $fetch_data = $update_data->fetch();
    ?>
            <form action="updateRentals.php" method="POST">

            <input type="hidden" name="rental_id" value="<?php echo $fetch_data['rental_id'] ?>">

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

                <label for="rental_date">Choose new rental date</label>
                <input type="date" id="rental_date" name="rental_date" value="<?php echo $fetch_data['rental_date'] ?>">
                <br>
                <label for="return_date">Choose new return date</label>
                <input type="date" id="return_date" name="return_date" value="<?php echo $fetch_data['return_date'] ?>">
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