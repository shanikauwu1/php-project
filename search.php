
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
        <h1>Search Students</h1>
    </header>
    <main>             
    <section>
    <h3>Welcome <?php echo $_SESSION['username'] ?></h3>
    <ul class="menu-list">
        <li><a href="students.php">Students</a>  </li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
<?php

$search_result = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_number = trim($_POST['student_number']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    
    if (empty($student_number) && empty($firstname) && empty($lastname)) {
        $search_result = "Please enter at least one search criteria.";
    } else {
        $conditions = [];
        $params = [];
        $types = "";
        
        if (!empty($student_number)) {
            $conditions[] = "id = ?";
            $params[] = $student_number;
            $types .= "s";
        }
        if (!empty($firstname)) {
            $conditions[] = "LOWER(firstname) = LOWER(?)";
            $params[] = $firstname;
            $types .= "s";
        }
        if (!empty($lastname)) {
            $conditions[] = "LOWER(lastname) = LOWER(?)";
            $params[] = $lastname;
            $types .= "s";
        }
        
        $baseQuery = "SELECT id, firstname, lastname FROM students";
        if (!empty($conditions)) {
            $query = $baseQuery . " WHERE " . implode(" AND ", $conditions);
        } else {
            $query = $baseQuery;
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<h2>Records Found</h2><table border='1'><tr><th>Student Number</th><th>First Name</th><th>Last Name</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['id']) . "</td><td>" . htmlspecialchars($row['firstname']) . "</td><td>" . htmlspecialchars($row['lastname']) . "</td></tr>";
            }
            echo "</table>";
        } else {
            $search_result = "Record not found.";
        }
        $stmt->close();
    }
}
?>
<!-- Search Form -->
<form id="search-form" method="post" action="" >
    <label>Student Number: <input type="text" name="student_number"></label>
    <label>First Name: <input type="text" name="firstname"></label>
    <label>Last Name: <input type="text" name="lastname"></label>
    <button type="submit">Search</button>
</form>
<?php if (!empty($search_result)) echo "<p class='important'>$search_result</p>"; ?>



     
</section>        
    </main>
    <footer>
        <p>Copyleft 20** <span>&copy;</span> - PHP FWD Web Scripting 2</p>
    </footer>
</div>    
</body>
</html>