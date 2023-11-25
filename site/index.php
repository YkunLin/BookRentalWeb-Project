
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once ('connectdatabase.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    

if (isset($username)){
    $stat = $pdo->prepare("SELECT * FROM registration WHERE username = :username");//search user from the database
    $stat->bindParam(':username', $username);
    $stat->execute();

    $user = $stat->fetch();

    switch($username) {
        case 'admin':
            //admin login
            if ($password == 'admin') {//if both username and password = 'admin', go to admin page
                $_SESSION['admin'] = $username;
                header('Location: admin.php');
                exit();
                
            } else {
                echo "<h2 style='color: red;'>Invalid username or password.</h2>";
            }
            break;
            default:
            //regular login
            if ($user && password_verify($password, $user['password'])) {//check password is correct or not
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $user['id'];
                header('Location: home.php');//if username and password correct, then go to home page.
                exit();
            } else {
                echo "<h2 style='color: red;'>Invalid username or password.</h2>";
            }
            break;
        }
            }
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h1{
            text-shadow: 2px 2px;
        }
        body{
            background-image: url("pencil.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size:cover;

            max-width: 500px;
            margin:0 auto;
            height: 60vh;
            display: flex;
            flex-direction: column;
           justify-content: center;
        }
    </style>
</head>
<body>
    <h1>Login Info</h1>
    <form action="index.php" method="POST"> <!-- post info to index.php(which is this file)-->
        <label for="user">Type your username</label>
        <input type="text" id="user" name="username">
        <br>
        <label for="pass">Type your password</label>
        <input type="password" id="pass" name="password">
        <br>

        <input type="submit" value ="Login" class="button">
</form>
 
<p>New user? <a href="register.php">Register here</a>.</p>

<footer>
    &copy; <?php echo $year; ?> Yankun Lin
</footer>
</body>
</html>