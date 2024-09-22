<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div id="postContent">

        <?php 
            include 'db_connect.php';

            $post_id = $_GET['post_id'];
            $post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_STRING);
            $post_id = htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8');
            $post_id = (int)$post_id;

            if ($_SERVER["REQUEST_METHOD"] === "GET" && $post_id != 0) {
                $post_stmt = $conn->prepare("SELECT * FROM post WHERE post_id=?"); 
                $post_stmt->bind_param("i", $post_id);
                $post_stmt->execute();
                $post_result = $post_stmt->get_result();
                $post_result->data_seek(0);
                $post_row = $post_result->fetch_assoc(); 

                $user_stmt = $conn->prepare("SELECT * FROM user WHERE user_id=?"); 
                $user_stmt->bind_param("i", $post_row['user_id']);
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();
                $user_result->data_seek(0);
                $user_row = $user_result->fetch_assoc(); 

                echo '<img width="100" height="100" src="' . $user_row['pfp'] . '"></img>';
                echo '<h1><b>'.$user_row['username'].'</b> posted: </h1>';
                echo '<p>'.$post_row['content'].'</p>';
                echo '<hr>';
                echo $post_row['post_time'];

            }



        ?>

    </div>
    
</body>
</html>


