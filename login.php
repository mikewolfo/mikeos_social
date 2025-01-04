<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <form method="POST" action="login.php">
        <h6>User handle: </h6>
        <input type="text" name="handle" required>
        <br>
        <h6>Password: </h6>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
    
</body>
</html>


<?php

    include 'db_connect.php';

    //echo 'INSERT INTO cookies VALUES (?, ?, TRUE, "' . $ip . '", "' . $ip . '", "' . date('Y-m-d H:i:s', strtotime('now')) .'", "' . date('Y-m-d H:i:s', strtotime('now')) . '", "' . date('Y-m-d H:i:s', strtotime('+14 days')) . '" );';

    if($_SERVER["REQUEST_METHOD"] === "POST"){

        //input sanitization
        $handle = $_POST['handle'];
        $handle = filter_input(INPUT_POST, 'handle', FILTER_SANITIZE_STRING);
        $handle = htmlspecialchars($handle, ENT_QUOTES, 'UTF-8');

        $password = $_POST['password'];
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

    
        //lookup user
        $user_stmt = $conn->prepare("SELECT user_id, handle, password FROM user WHERE handle = :handle");
        //$user_stmt->bind_param("s", $handle);
        $user_stmt->bindValue(':handle', $handle, PDO::PARAM_STR);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user_row = $user_result->fetch_assoc();

        // if($user_row === NULL){
        //     echo 'Invalid Credentials';
        //     die;
        // }

        // //if username found, we then need to verify the password
        $password_db = $user_row['password'];
        // if(!password_verify($password, $password_db)){
        //     echo 'Invalid Credentials';  
        //     die;
        // }

        if($user_row === NULL || !password_verify($password, $password_db)){
            echo 'Invalid Credentials';  
            die;
        }


        //now we can create a cookie for this login
        $cookie_id = strval(random_bytes(510));
        setcookie("login_session", $cookie_id, time() + (14 * 60 * 60 * 24), "/");

        //record the new cookie in the DB

        //TODO - something is fucked up with this query here 
        //$user_stmt = $conn->prepare('INSERT INTO cookies VALUES (?, ?, TRUE, "' . $ip . '", "' . $ip . '", "' . date('Y-m-d H:i:s', strtotime('now')) .'", "' . date('Y-m-d H:i:s', strtotime('now')) . '", "' . date('Y-m-d H:i:s', strtotime('+14 days')) . '" );');
        $user_stmt = $conn->prepare('INSERT INTO cookies VALUES (:cookie_value, :user_id, TRUE, "' . $ip . '", "' . $ip . '", "' . date('Y-m-d H:i:s', strtotime('now')) .'", "' . date('Y-m-d H:i:s', strtotime('now')) . '", "' . date('Y-m-d H:i:s', strtotime('+14 days')) . '" );');
        //$user_stmt->bind_param("ss", $cookie_value, $user_row['user_id']);
        $user_stmt->bindValue(':cookie_value', $cookie_value, PDO::PARAM_STR);
        $user_stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $user_stmt->execute();


        //if we haven't died, the login is correct
        echo 'Login successful';

    }

?>