<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once ('connectdatabase.php');

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username=$_POST['username'];
    $password=password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (empty($username) || empty($password)) {
        echo "<h2 style='color: red;'>Please fill out all required fields.</h2>";
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
        echo "<h2 style='color: green;'>Registration successful. You can now <a href='index.php'>login</a>.</h2>";
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
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
    <h1>Register Info</h1>
    <form action="register.php" method="POST"> <!-- post info to register.php(which is this file)-->
        <label for="user">Type your username</label>
        <input type="text" id="user" name="username">
        <br>
        <label for="pass">Type your password</label>
        <input type="password" id="pass" name="password">
        <br>

        <input type="submit" value ="Register" class="button">
</form>
 
<p>Return to <a href="index.php">Login page</a>.</p>

<footer>
    &copy; <?php echo $year; ?> Yankun Lin
</footer>
</body>
</html>