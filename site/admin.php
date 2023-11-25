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
//select user info in the registration table.
$stat = $pdo->prepare("SELECT * FROM registration");
$stat->execute();
$users = $stat->fetchAll();

// Select book info in the books table.
$stat_books = $pdo->prepare("SELECT * FROM books");
$stat_books->execute();
$books = $stat_books->fetchAll();

// Select rental info in the rentals table.
$stat_rentals = $pdo->prepare("SELECT * FROM rentals");
$stat_rentals->execute();
$rentals = $stat_rentals->fetchAll();




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        h1{
            text-shadow: 2px 2px;
        }
        body {
            text-align: center;
            background-image: url("pencil.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size:cover;
        }

        table {
            border-collapse: collapse;
            margin: 0 auto;
            padding: 0px;
            width: 70% !important;
            background-color:white;
        }

        th, td {
            border: 1px solid #000;
            text-align: left;
            padding: 8px;
        }
        .logout-form {
            position: absolute;
            top: 3px;
            right: 10px;
        }
    </style>
</head>
<body>
    <h1>Hello <?php echo $_SESSION['admin'] ?>, this is Admin Page</h1>
    <!-- display user table -->
    <div class="w3-container">
        <h2>User Table</h2>
    <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
        <thead>
            <tr class="w3-dark-grey">
                <th>id</th>
                <th>username</th>
                <th>password</th>
                <th>operation</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['password']; ?></td>
                    <td>
                        <a href="delete.php?delete=<?php echo $user['id']?>" onclick="return confirm('Are you sure you want to delete this user');">Delete</a>
                        <a href="update.php?update=<?php echo $user['id']?>">Update</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            
        </tbody>

    </table>
        </div>
        <?php
        if (empty($users)) {
        echo "<h2 style='color: red;'>No records found in the users table.</h2>";
        }
        ?>
    <P><a href='adminRegister.php'><button>Create new user</button></a></P>
    <br><br><br><br>

    <!-- Display Books Table -->
    <div class="w3-container center-table">
        <h2>Books Table</h2>
        <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
            <thead>
                <tr class="w3-dark-grey">
                    <th>book id</th>
                    <th>title</th>
                    <th>author</th>
                    <th>available quantity</th>
                    <th>operation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo $book['book_id']; ?></td>
                        <td><?php echo $book['title']; ?></td>
                        <td><?php echo $book['author']; ?></td>
                        <td><?php echo $book['available_quantity']; ?></td>
                        <td>
                        <a href="deleteBooks.php?delete=<?php echo $book['book_id']?>" onclick="return confirm('Are you sure you want to delete this book');">Delete</a>
                        <a href="updateBooks.php?update=<?php echo $book['book_id']?>">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    if (empty($books)) {
    echo "<h2 style='color: red;'>No records found in the books table.</h2>";
    }
    ?>
    <P><a href='insertBook.php'><button>Insert New Book</button></a></P>
    <br><br><br><br>

    <!-- Display Rentals Table -->
    <div class="w3-container center-table">
        <h2>Rentals Table</h2>
        <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable">
            <thead>
                <tr class="w3-dark-grey">
                    <th>rental id</th>
                    <th>user id</th>
                    <th>book id</th>
                    <th>rental date</th>
                    <th>return date</th>
                    <th>operation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rentals as $rental): ?>
                    <tr>
                        <td><?php echo $rental['rental_id']; ?></td>
                        <td><?php echo $rental['user_id']; ?></td>
                        <td><?php echo $rental['book_id']; ?></td>
                        <td><?php echo $rental['rental_date']; ?></td>
                        <td><?php echo $rental['return_date']; ?></td>
                        <td>
                        <a href="deleteRentals.php?delete=<?php echo $rental['rental_id']?>" onclick="return confirm('Are you sure you want to delete this rental data');">Delete</a>
                        <a href="updateRentals.php?update=<?php echo $rental['rental_id']?>">Update</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        if (empty($rentals)) {
        echo "<h2 style='color: red;'>No records found in the rentals table.</h2>";
        }
        ?>
    </div>

    <P><a href='insertRentals.php'><button>Insert New Rental Info</button></a></P>
    <br><br><br><br>

    <form action='logout.php' method='POST' class="logout-form">
        <input type='submit' value='Logout to the login page'>
        </form>
</body>
</html>