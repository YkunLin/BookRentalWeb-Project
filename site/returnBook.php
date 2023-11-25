<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('connectdatabase.php');

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if return_book_id is set in POST
    if (isset($_POST['return_book_id'])) {
        $returnBookId = $_POST['return_book_id'];
        
        // Check if the book is rented by the current user
        $userId = $_SESSION['id'];
        $checkRentedBook = $pdo->prepare("SELECT * FROM rentals WHERE book_id = :returnBookId AND user_id = :userId");
        $checkRentedBook->bindParam(':returnBookId', $returnBookId);
        $checkRentedBook->bindParam(':userId', $userId);
        $checkRentedBook->execute();

        if ($checkRentedBook->rowCount() > 0) {
            // Book is rented by the user, proceed with return
            $returnBook = $pdo->prepare("DELETE FROM rentals WHERE book_id = :returnBookId AND user_id = :userId");
            $returnBook->bindParam(':returnBookId', $returnBookId);
            $returnBook->bindParam(':userId', $userId);

            if ($returnBook->execute()) {
                // Update available_quantity in the books table
                $updateQuantity = $pdo->prepare("UPDATE books SET available_quantity = available_quantity + 1 WHERE book_id = :returnBookId");
                $updateQuantity->bindParam(':returnBookId', $returnBookId);
                $updateQuantity->execute();

                echo "<h2 style='color: green;'>Book returned successfully.</h2>";
            } else {
                echo "Error returning the book.";
                print_r($returnBook->errorInfo());
            }
        } else {
            echo "You didn't rent this book or it's not available for return.";
        }
    } else {
        echo "Error: return_book_id not set.";
    }
} else {
    echo "Invalid request method.";
}

echo "<br>";
echo"<a href='home.php'><button>Go back</button></a>";
?>