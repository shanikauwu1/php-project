<?php
require 'session_check.php';
$error_msg = "";
$success_msg = "";
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use isset() to check if form variables exist
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';

    // Validate required fields
    if (empty($id)) {
        $errors[] = "Student ID is required.";
    }
    if (empty($firstname)) {
        $errors[] = "First Name is required.";
    }
    if (empty($lastname)) {
        $errors[] = "Last Name is required.";
    }

    // Validate ID format using preg_match() === 1
    if (!empty($id) && preg_match('/^A00[0-9]{6}$/', $id) !== 1) {
        $errors[] = "Invalid ID format. The ID should be in the format A00XXXXXX.";
    }

    // Check if the Student ID already exists (Ensure uniqueness)
    if (!empty($id) && empty($errors)) {
        $check_sql = "SELECT COUNT(*) FROM students WHERE id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $errors[] = "This Student ID is already taken. Please choose another one.";
        }
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $insert_sql = "INSERT INTO students (id, firstname, lastname) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sss", $id, $firstname, $lastname);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Record added successfully!";
            header("Location: students.php"); // Redirect to students.php
            exit();
            
        } else {
            $errors[] = "Error adding student: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Student - PHP MySQL</title>  
    <link rel="stylesheet" href="css/styles.css">    
</head>
<body>
<div id="wrapper">
    <header>
    <h3>This project by Shanika E and Stephanie Hillier</h3>
        <h1>Add Student</h1>
    </header>
    <main>
        <section>
            <h3>Welcome <?php echo $_SESSION['username']; ?></h3>
            <ul class="menu-list">
                <li><a href="search.php">Search</a></li>
                <li><a href="students.php">Student List</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>

            <!-- Show error messages -->
            <?php if (!empty($errors)): ?>
                <ul class="important">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <!-- Show success message -->
            <?php if (!empty($success_msg)): ?>
                <p style="color: green;"><?php echo htmlspecialchars($success_msg); ?></p>
            <?php endif; ?>

            <form method="POST">
                <label for="id">Student ID:</label>
                <input type="text" name="id" value="<?php echo htmlspecialchars($id ?? ''); ?>">
                <br><br>

                <label for="firstname">First Name:</label>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname ?? ''); ?>" >
                <br><br>

                <label for="lastname">Last Name:</label>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname ?? ''); ?>" >
                <br><br>

                <input type="submit" value="Add Student">
            </form>
        </section>
    </main>
    <footer>
        <p>Copyleft 20** <span>&copy;</span> - PHP FWD Web Scripting 2</p>
    </footer>
</div>
</body>
</html>
