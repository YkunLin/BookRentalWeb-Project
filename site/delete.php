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

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];

    // Check for associated records in the rentals table
    $check_rentals = $pdo->prepare("SELECT rental_id FROM rentals WHERE user_id = :user_id");
    $check_rentals->bindParam(':user_id', $delete_id);
    $check_rentals->execute();
    $associated_rentals = $check_rentals->fetchAll();
    
    // Delete associated records in the rentals table
    foreach ($associated_rentals as $rental) {
        $delete_rental = $pdo->prepare("DELETE FROM rentals WHERE rental_id = :rental_id");
        $delete_rental->bindParam(':rental_id', $rental['rental_id']);
        $delete_rental->execute();
    }

    // Now, delete the user
    $delete_user = $pdo->prepare("DELETE FROM registration WHERE id = :id");
    $delete_user->bindParam(':id', $delete_id);


if ($delete_user->execute()) {
    header("Location: admin.php");
} else {
    header("Location: admin.php");
}
}

else {
echo "User ID not provided.";
}
?>