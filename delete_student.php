<?php
require 'session_check.php'; ?>

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
    <header> <h3>This project by Shanika E and Stephanie Hillier</h3>
    <br/>
        <h1>Student List</h1>

     
    </header>
    <main>             
    <section>  
 <h3>Welcome <?php echo $_SESSION['username'] ?></h3>
<ul class="menu-list">
    <li>
  <a href="search.php">Search</a>  
</li>
<li>
  <a href="students.php">Students</a>  
</li>

<li><a href="logout.php">Logout</a></li></ul>


<?php

if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch the record 
    $fetch_sql = "SELECT id, firstname, lastname FROM students WHERE id = ?";
    $stmt = $conn->prepare($fetch_sql);
    $stmt->bind_param("s", $delete_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if ($student) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
                $delete_sql = "DELETE FROM students WHERE id = ?";
                $stmt = $conn->prepare($delete_sql);
                $stmt->bind_param("s", $delete_id);
                
                if ($stmt->execute()) {
                    $_SESSION['success_msg'] = "Record deleted successfully!: ". htmlspecialchars($student['id'])." : ". htmlspecialchars($student['firstname'])." ".htmlspecialchars($student['lastname']);
                    header("Location: students.php"); // Redirect to students.php
                    exit();
                } else {
                    echo "<p>Error deleting record: " . $conn->error . "</p>";
                }
                $stmt->close();
            } else {
               // echo "<p class='true best-practice'>Deletion canceled.</p>";
                $_SESSION['success_msg'] = "Deletion canceled!: ". htmlspecialchars($student['id'])." : ". htmlspecialchars($student['firstname'])." ".htmlspecialchars($student['lastname']);
                header("Location: students.php"); 
                exit();
            }
        } else {
            echo "<h2>Confirm Deletion</h2>";
            echo "<p class='warning'>Are you sure you want to delete this student?</p>";
            echo "<p><strong>ID:</strong> " . htmlspecialchars($student['id']) . "<br>";
            echo "<strong>Name:</strong> " .  htmlspecialchars($student['firstname']). " " . htmlspecialchars($student['lastname']) . "</p>";

            echo "<form method='POST'>";
            echo "<label class='radio-label'><input type='radio' name='confirm' value='yes'> Yes </label>";
            echo "<label class='radio-label'><input type='radio' name='confirm' value='no' checked> No </label>";
            echo "<br><br><input type='submit' value='Submit'>";
            echo "</form>";
        }
    } else {
        echo "<p class='true'>Record not found.</p>";
    }
} else {
    echo "<p class='important'>Invalid request. No ID provided.</p>";
}

$conn->close();

?>



     
</section>        
    </main>
    <footer>
        <p>Copyleft 20** <span>&copy;</span> - PHP FWD Web Scripting 2</p>
    </footer>
</div>    
</body>
</html>