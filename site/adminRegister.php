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

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username=$_POST['username'];
    $password=password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (empty($username) || empty($password)) {
        echo "<h1 style='color: red;'>Please fill out all required fields.</h1>";
    } else {

    //check if username already exist
    $stat = $pdo->prepare("SELECT * FROM registration WHERE username = :username");
    $stat->bindParam(':username', $username);
    $stat->execute();

    if ($stat->rowCount()>0) {
        echo "<h2 style='color: red;'>Username already taken. Please choose another one.</h2>";
    }
    else{//if not exist, then insert registration info to the project table.
        $stat=$pdo->prepare("INSERT INTO registration(username,password) VALUES(:username,:password)");

        $stat->bindParam(':username',$username);
        $stat->bindParam(':password',$password);
    
        $stat->execute();
        echo "<h2 style='color: green;'>User created successful. you can go back to <a href='admin.php'>admin</a> page.</h2>";
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin create new user</title>
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
    <h1>Create new user</h1>
    <form action="adminRegister.php" method="POST">
        <label for="user">Type username</label>
        <input type="text" id="user" name="username">
        <br>
        <label for="pass">Type password</label>
        <input type="password" id="pass" name="password">
        <br>

        <input type="submit" value ="Create" class="button">
</form>
        <form action='logout.php' method='POST' class="logout-form">

        <input type='submit' value='Logout to the login page'>
        </form>
<br> 
<p>Return to <a href="admin.php">Admin page</a>.</p>
<br>
<footer>
    &copy; <?php echo $year; ?> Yankun Lin
</footer>
</body>
</html>