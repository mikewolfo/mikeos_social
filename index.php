<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MikeOS Social Test Page</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>

    <form method="GET" action="index.php">
        <input type="text" name="query">
        <input type="submit" value="Search!">
    </form>

    <form method="POST" action="index.php">
        <input type="hidden" name="new_acct">
        <input type="submit" value="Create Account">
    </form>

    <form method="POST" action="index.php">
        <?php

            include 'db_connect.php';

            //TODO sanitize cookie variables!

            if(isset($_COOKIE['login_session'])){

                $user_stmt = $conn->prepare('SELECT u.user_id, u.handle, c.cookie_id FROM user as u, cookies as c WHERE u.user_id = c.user_id AND cookie_id = ?');
                $user_stmt->bind_param("s", $_COOKIE['login_session']);
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();
                $user_row = $user_result->fetch_assoc();

                echo 'Logged in as <b>' . var_dump($user_result) . '</b>
                      <input type="hidden" name="logout">
                      <input type="submit" value="Logout">';
            } else {
                echo '<input type="hidden" name="login">
                      <input type="submit" value="Login">';        
            }
        ?>
    </form>

    <?php


    include 'db_connect.php';


    //goto correct page if a button leads to somewhere other than index.php
    //or just handle buttons that aren't doing the search query
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST['login'])){
            header("Location: login.php");
            die;
        }

        if(isset($_POST['logout'])){

            $user_stmt = $conn->prepare('UPDATE cookies SET in_use = FALSE WHERE cookie_id = ?;');
            $user_stmt->bind_param("s", $_COOKIE['login_session']);
            $user_stmt->execute();
            setcookie("login_session", "", time() - 3600, '/');      
            header("Location: index.php");
            die;
        }

        if(isset($_POST['new_acct'])){
            header("Location: new_account.php");
            die;
        }
    }


    $query = $_GET["query"];
    $query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);
    $query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
    $query = '%' . $_GET["query"] . '%';


    if ($_SERVER["REQUEST_METHOD"] === "GET" && $query != "%%") {
        
        
        echo '<br>';

        echo 'Searched for: ' . $_GET["query"] . '<br>';

        //$user_result = $conn->query("SELECT * FROM user WHERE user_id = 3");

        $user_stmt = $conn->prepare("SELECT * FROM user WHERE handle LIKE ?");
        $user_stmt->bind_param("s", $query);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();

        $user_result->data_seek(0);
        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['user_id'];

        //$posts_result = $conn->query("SELECT * FROM post WHERE user_id = 3");

        $posts_stmt = $conn->prepare("SELECT post_id, LEFT(content, 30) as content, post_time FROM post WHERE user_id = ?");
        $posts_stmt->bind_param("i", $user_id);
        $posts_stmt->execute();
        $posts_result = $posts_stmt->get_result();


        
    
        for ($post_row_no = 0; $post_row_no < $posts_result->num_rows; $post_row_no++) {
            $posts_result->data_seek($post_row_no);
            $post_row = $posts_result->fetch_assoc();
            echo '<img width="100" height="100" src="' . $user_row['pfp'] . '"></img>';
            echo $user_row['username'] . ' (' . $user_row['handle'] . ') <br>';
            echo 'Last login: ' . $user_row['last_login_ip'] . ' at ' . $user_row['last_login_time'] . '<br>';
            echo '<a href="post.php?post_id=' . $post_row['post_id'] . '">' .$post_row['content'] . "...</a><br>" . $post_row['post_time'] . "<hr>";
        }
    }

    if($_GET["query"] === ""){
        //header("Location: index.php");
        //die;
        echo 'No search query provided!';
    }


?>

</body>
</html>

