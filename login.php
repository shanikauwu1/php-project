<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - PHP Mysql FWD Web Scripting 2</title>  
    
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon/favicon-16x16.png">
        
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content="BCIT FWD Web Scripting 2: Using PHP and MySQL to develop server side solutions for web development.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <meta charset="UTF-8">    
    
    <link rel="stylesheet" href="css/styles.css">    
</head>
<body>
<div id="wrapper">
    <header> <h3>Project PHP</h3>
    <br/>
        <h1>Login</h1>
    </header>
    <main>             
    <section>

 <?php
session_start();
require 'config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if (empty($user) || empty($pass)) {
        $error = "Username and password are required.";
    } else {

        
        $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_user, $db_pass);
            $stmt->fetch();
            
            if ($pass === $db_pass) { 
                $_SESSION['username'] = $db_user;
                $_SESSION['timeout'] = time() + TIMEOUT_IN_SECONDS; 
                header("Location: students.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "The username was not in our database.";
        }
        $stmt->close();
    }
}
?>

<?php if (!empty($error)) echo "<p>$error</p>"; ?>
<form 	method="POST" 
        action="#">

    <input type="text" name="username" id="username">
    <label for="username">Username</label><br>
    
    <input type="password" name="password" id="password">
    <label for="password">Password</label><br>
    
    <input type="submit" value="Submit">
                          
</form>

        
    </section>        
    </main>
    <footer>
        <p>Copyleft 20** <span>&copy;</span> - PHP FWD Web Scripting 2</p>
    </footer>
</div>    
</body>
</html>