<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once ('connectdatabase.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookId = $_POST['book_id'];


    // Check if the book exists and is available
    $checkBook = $pdo->prepare("SELECT * FROM books WHERE book_id = :bookId AND available_quantity > 0");
    $checkBook->bindParam(':bookId', $bookId);
    $checkBook->execute();

    // If Book is available, rental...
    if ($checkBook->rowCount() > 0) {
        $userId = $_SESSION['id'];

        // Perform the rental 
        $rentalDate = date("Y-m-d");
        $returnDate = $_POST['return_date'];

        //store rent info to the rentals table
        $rentBook = $pdo->prepare("INSERT INTO rentals (user_id, book_id, rental_date, return_date) VALUES (:userId, :bookId, :rentalDate, :returnDate)");
        $rentBook->bindParam(':userId', $userId);
        $rentBook->bindParam(':bookId', $bookId);
        $rentBook->bindParam(':rentalDate', $rentalDate);
        $rentBook->bindParam(':returnDate', $returnDate);

        if ($rentBook->execute()) {
            echo "<h2 style='color: green;'>Book rented successfully.</h2>";
            $updateQuantity = $pdo->prepare("UPDATE books SET available_quantity = available_quantity - 1 WHERE book_id = :bookId");
            $updateQuantity->bindParam(':bookId', $bookId);
            $updateQuantity->execute();
        } else {
            echo "Error renting the book.";
            print_r($rentBook->errorInfo());
        }
    } else {
        echo "Book is not available for rental.";
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
    <title>Home page</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        h1{
            text-shadow: 2px 2px;
        }
        body {
            background-image: url("pencil.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size:cover;
            text-align: center;
        }
        .logout-form {
            position: absolute;
            top: 5px;
            right: 10px;
        }
        .center-table {
            margin: 0 auto;
            padding: 0px;
            width: 50%;
            background-color:white;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-container label {
            margin-bottom: 5px;
        }
        .form-container select, .form-container input {
            margin-bottom: 5px;
            width: 50%;
            padding: 8px;
        }
    </style>
</head>
<body>
    <h1>Hello <?php echo $_SESSION['username'] ?></h1>
    <br>
    <br>

    <!-- Fetch rented books for the current user -->
    <?php
    $userId = $_SESSION['id'];
    $userRentalsQuery = $pdo->prepare("
    SELECT rentals.book_id, books.title, books.author, rentals.rental_date, rentals.return_date 
    FROM rentals 
    JOIN books 
    ON rentals.book_id = books.book_id WHERE rentals.user_id = :userId");
    $userRentalsQuery->bindParam(':userId', $userId);
    $userRentalsQuery->execute();
    $userRentals = $userRentalsQuery->fetchAll();
    ?>



    <h2>Rent a New Books:</h2>
    <form action="home.php" method="POST" class="form-container">
    <label for="book">Select a Book:</label>
    <select name="book_id" id="book">
        <?php foreach ($availableBooks as $book): ?>
            <option value="<?php echo $book['book_id']; ?>">
            <?php echo $book['title']; ?> by <?php echo $book['author']; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br>

    <label for="returnDate">Choose Return Date:</label>
    <input type="date" id="returnDate" name="return_date" required>
        
    <br>
    <input type="submit" value="Rent Book">
    </form>


    <form action='logout.php' method='POST' class="logout-form">
    <input type='submit' value='Logout to the login page'>
    </form>
    <br><br><br>

    <!-- display rented books by the current user -->
    <h2>Your Rented Books:</h2>
    <a href="home.php?refresh=1"><button>Refresh Data</button></a>
    <br><br>
    <?php if (!empty($userRentals)){ ?>
        <div class="w3-container center-table">
            <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
            <thead>
                <tr class="w3-dark-grey">
                    <th>Title</th>
                    <th>Author</th>
                    <th>Rental Date</th>
                    <th>Return Deadline</th>
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userRentals as $rental){?>
                    <tr>
                        <td><?php echo $rental['title']; ?></td>
                        <td><?php echo $rental['author']; ?></td>
                        <td><?php echo $rental['rental_date']; ?></td>
                        <td><?php echo $rental['return_date']; ?></td>
                        <td>
                            <form action="returnBook.php" method="POST">
                            <input type="hidden" name="return_book_id" value="<?php echo $rental['book_id']; ?>">
                            <input type="submit" value="Return">
                            </form>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
        </div>
    <?php }else{ ?>
        <p>You haven't rented any books yet.</p>
    <?php } ?>


</body>
</html>