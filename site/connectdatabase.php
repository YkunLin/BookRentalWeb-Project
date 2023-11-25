<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$year = date('Y');

$dsn='mysql:host=localhost;dbname=project';
$username_db="root";
$password_db="root";

try{
    $pdo= new PDO($dsn,$username_db,$password_db);
}catch(PDOException $e){
    die("connection error".$e->getMessage());
}

$sql="CREATE TABLE IF NOT EXISTS registration (
    id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    PRIMARY KEY(id)
)";

$stat=$pdo->prepare($sql);
$stat->execute();

$sqlBooks = "CREATE TABLE IF NOT EXISTS books (
    book_id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    available_quantity INT NOT NULL,
    PRIMARY KEY(book_id)
)";

$statBooks = $pdo->prepare($sqlBooks);
$statBooks->execute();

$sqlRentals = "CREATE TABLE IF NOT EXISTS rentals (
    rental_id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT(5) UNSIGNED NOT NULL,
    book_id INT(5) UNSIGNED NOT NULL,
    rental_date DATE NOT NULL,
    return_date DATE,
    PRIMARY KEY(rental_id),
    FOREIGN KEY(user_id) REFERENCES registration(id),
    FOREIGN KEY(book_id) REFERENCES books(book_id) 
)";

$statRentals = $pdo->prepare($sqlRentals);
$statRentals->execute();
?>