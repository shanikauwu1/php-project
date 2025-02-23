<?php
require 'session_check.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Student - PHP Mysql FWD Web Scripting 2</title>  
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
        <h1>Update Student</h1>
    </header>
    <main>             
    <section>  
        <h3>Welcome <?php echo $_SESSION['username']; ?></h3>
        <ul class="menu-list">
            <li><a href="search.php">Search</a></li>
            <li><a href="students.php">Student List</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

        <?php
        if (isset($_GET['update_id']) && !empty($_GET['update_id'])) {
            $update_id = $_GET['update_id'];
            $error_msg = "";

            // Fetch the student record to be updated
            $fetch_sql = "SELECT id, firstname, lastname FROM students WHERE id = ?";
            $stmt = $conn->prepare($fetch_sql);
            $stmt->bind_param("s", $update_id); 
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();
            $stmt->close();

            if ($student) {
                // Check if form is submitted 
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $new_id = isset($_POST['id']) ? trim($_POST['id']) : '';
                    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
                    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';

                    // Validate if the fields are empty
                    if (empty($new_id) || empty($firstname) || empty($lastname)) {
                        $error_msg = "All fields are required!";
                    }
                    elseif (!empty($new_id) && preg_match('/^A00[0-9]{6}$/', $new_id) !== 1) {
                        $error_msg  = "Invalid ID format. The ID should be in the format A00XXXXXX.";
                    }
                
                    else {
                        // Check if the new ID already exists 
                        $check_sql = "SELECT id FROM students WHERE id = ?";
                        $stmt = $conn->prepare($check_sql);
                        $stmt->bind_param("s", $new_id);
                        $stmt->execute();
                        $stmt->store_result();
                        if ($stmt->num_rows > 0 && $new_id != $student['id']) {
                            $error_msg = "The new ID is already taken. Please choose another one.";
                        } else {
                            // Update student information in the database
                            $update_sql = "UPDATE students SET id = ?, firstname = ?, lastname = ? WHERE id = ?";
                            $stmt = $conn->prepare($update_sql);
                            $stmt->bind_param("ssss", $new_id, $firstname, $lastname, $update_id);

                            if ($stmt->execute()) {
                                $_SESSION['success_msg'] = "Record updated successfully! : ".$new_id." : ".$firstname." ".$lastname;
                                header("Location: students.php"); // Redirect to students.php
                                exit();
                            } else {
                                $error_msg = "Error updating record: " . $conn->error;
                            }
                            $stmt->close();
                        }
                    }
                }

                // Show update form
                echo "<h2>Update Student</h2>";
                if (!empty($error_msg)) {
                    echo "<p class='important'>$error_msg</p>";
                }
                echo "<form method='POST'>";
                echo "<label for='id'>Student ID:</label>";
                echo "<input type='text' name='id' value='" . htmlspecialchars($student['id']) . "' ><br><br>";
                echo "<label for='firstname'>First Name:</label>";
                echo "<input type='text' name='firstname' value='" . htmlspecialchars($student['firstname']) . "' ><br><br>";
                echo "<label for='lastname'>Last Name:</label>";
                echo "<input type='text' name='lastname' value='" . htmlspecialchars($student['lastname']) . "' ><br><br>";
                echo "<input type='submit' value='Update'>";
                echo "</form>";
            } else {
                echo "<p>Record not found.</p>";
            }
        } else {
            echo "<p>Invalid request. No ID provided.</p>";
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
