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


//update user info
if(isset($_POST['update_button'])){
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {

    $stat = $pdo->prepare("UPDATE registration SET username = :username, password = :password WHERE id = :id");

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);//hash the password
    $stat->bindParam(':username',$username);
    $stat->bindParam(':password',$hashed_password);
    $stat->bindParam(':id',$user_id);

    if ($stat->execute()) {
        echo "<h2 style='color: green;'>User updated successfully.</h2>";
        echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
        exit();
    } else {
        echo "<h2 style='color: red;'>Error updating user.</h2>";
        echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
        exit();
    }
} else {
    echo "<h2 style='color: red;'>Please fill out all required fields.</h2>";
    echo "<p>Return to <a href='admin.php'>admin page</a>.</p>";
    exit();
}

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update UserInfo</title>
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
    <h1>Update User Info</h1>
    
<!-- display current user info -->
    <?php
    if(isset($_GET['update'])){
        $update_id= $_GET['update'];
    
        $update_data = $pdo->prepare("SELECT * FROM registration WHERE id = :id");
        $update_data->bindParam(':id', $update_id);
        $update_data->execute();
        if ($update_data->rowCount()>0){
        $fetch_data = $update_data->fetch();
        ?>
        <form action="update.php" method="POST">

        <input type="hidden" name="user_id" value="<?php echo $fetch_data['id']?>">

        <label for="user">Type new username</label>
        <input type="text" id="user" name="username" value="<?php echo $fetch_data['username']?>">
        <br>
        <label for="pass">Type new password</label>
        <input type="password" id="pass" name="password">
        <br>

        <input type="submit" value ="Update" name="update_button" class="button">
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