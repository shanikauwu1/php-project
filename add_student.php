<?php
require 'session_check.php'; 
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    $check_query = "SELECT * FROM students WHERE id = '$student_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error_message'] = "Record not added. This student number is already registered. Please enter a different student number.";
    } else {
       
        if (!preg_match('/^A\d{8}$/', $student_id)) {
            $_SESSION['error_message'] = "Invalid student number format. It must start with 'A' followed by 8 digits (e.g., A01447000).";
        } else {
            
            $query = "INSERT INTO students (id, firstname, lastname) VALUES ('$student_id', '$first_name', '$last_name')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['success_message'] = "Record Updated: $student_id - $first_name $last_name";

            } else {
                $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
            }
        }
    }

   
    header("Location: students.php");
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>New Student - PHP Mysql FWD Web Scripting 2</title>  
    <link rel="stylesheet" href="css/styles.css">    
</head>
<body>
<div id="wrapper">
    <header> 
        <h3>Project PHP</h3>
        <h1>New Student</h1>
    </header>

    <main>               
        <section>  
            <h3>Welcome <?php echo $_SESSION['username'] ?></h3>
            <ul>
                <li><a href="search.php">Search</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>

            <h1>Add Student Record</h1>

            <form id="add-student-form" action="add_student.php" method="POST">
                <fieldset>
                    <legend>Add Student Record</legend>
                    <label for="student_id">Student Number:</label>
                    <input type="text" id="student_id" name="student_id" required pattern="^A\d{8}$" title="Student number must start with 'A' followed by 8 digits (e.g., A01447000).">

                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>

                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>

                    <button type="submit">Submit</button>
                </fieldset>
            </form>
        </section>        
    </main>

    <footer>
        <p>Copyleft 20** <span>&copy;</span> - PHP FWD Web Scripting 2</p>
    </footer>
</div>    
</body>
</html>
