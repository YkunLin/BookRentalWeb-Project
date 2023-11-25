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

// Delete rental
if (isset($_GET['delete'])) {
    $delete_rental_id = $_GET['delete'];

    $delete_rental = $pdo->prepare("DELETE FROM rentals WHERE rental_id = :rental_id");
    $delete_rental->bindParam(':rental_id', $delete_rental_id);

    if ($delete_rental->execute()) {
        header("Location: admin.php");
    } else {
        header("Location: admin.php");
    }
} else {
    echo "Rental ID not provided.";
}
?>