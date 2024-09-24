<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <script src="script.js"></script>

</head>
<body>
    
    <form method="POST" action="new_account.php">
        <h6>User handle: </h6>
        <input type="text" name="handle" required>
        <br>
        <h6>Password: </h6>
        <input type="password" name="password" required>
        <br>
        <h6>Good Boy token: </h6>
        <input type="text" name="goodboytoken" required>
        <br>
        <input type="submit" value="Create Account!">
    </form>


<?php

    include 'db_connect.php';

    
    if($_SERVER["REQUEST_METHOD"] === "POST"){

        //basic input sanitization
        $handle = $_POST['handle'];
        $handle = filter_input(INPUT_POST, 'handle', FILTER_SANITIZE_STRING);
        $handle = htmlspecialchars($handle, ENT_QUOTES, 'UTF-8');

        $password = $_POST['password'];
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

        $goodboytoken = $_POST['goodboytoken'];
        $goodboytoken = filter_input(INPUT_POST, 'goodboytoken', FILTER_SANITIZE_STRING);
        $goodboytoken = htmlspecialchars($goodboytoken, ENT_QUOTES, 'UTF-8');


        //first, check the good boy token!
        $user_stmt = $conn->prepare("SELECT count(*) as count FROM token WHERE token = ? AND user_id IS NULL");
        $user_stmt->bind_param("s", $goodboytoken);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user_row = $user_result->fetch_assoc();
        if ($user_row['count'] != 1){
            die('Invalid good boy token.');
        }

        //then check if username is available
        $user_stmt = $conn->prepare("SELECT count(*) as count FROM user WHERE handle = ?");
        $user_stmt->bind_param("s", $handle);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        $user_row = $user_result->fetch_assoc();
        if ($user_row['count'] > 0){
            die('Handle taken.');
        }

        //then check that password is at least 8 characters long
        if(strlen($password) < 8){
            die('Password must be at least 8 characters.');
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user_stmt = $conn->prepare('INSERT INTO user VALUES (NULL, ?, ?, ?, "' . $ip . '" , CURRENT_TIMESTAMP(), "/res/defaultpfp.jpg", "/res/defaultbanner.jpg", "Nothing to see here yet...");');
        $user_stmt->bind_param("sss", $handle, $handle, $password_hash);
        $user_stmt->execute();

        $user_result = $conn->prepare("SELECT user_id from user where handle = ?");
        $user_result->bind_param("s", $handle);
        $user_result->execute();
        $user_result = $user_result->get_result();
        $user_result->data_seek(0);
        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['user_id'];

        $user_stmt = $conn->prepare('UPDATE token SET user_id = ? WHERE token = ?');
        $user_stmt->bind_param("ss", $user_id, $goodboytoken);
        $user_stmt->execute();




    }


?>

</body>
</html>