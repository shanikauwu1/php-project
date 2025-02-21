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
    <header> <h3>Assighment 10</h3>
    <br/>
        <h1>Student List</h1>

     
    </header>
    <main>             
    <section>  
 <h3>Welcome <?php echo $_SESSION['username'] ?></h3>
<ul><li>
  <a href="search.php">Search</a>  
</li>
<li><a href="logout.php">Logout</a></li></ul>


        <?php
$sql = "SELECT id, firstname, lastname FROM students ORDER BY lastname ASC";
$result = $conn->query($sql);
$total_records = $result->num_rows;

if ($total_records > 0) {  ?>
<h2>Student Records (<?php echo $total_records; ?> found)</h2>
<table>
    <tr><th>Student Number</th><th>First Name</th><th>Last Name</th></tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['firstname']); ?></td>
            <td><?php echo htmlspecialchars($row['lastname']); ?></td>
        </tr>
    <?php } ?>
</table>
<?php }
else{
    echo "<p class='warning'>No student records found.</p>";
}
?>



     
</section>        
    </main>
    <footer>
        <p>Copyleft 20** <span>&copy;</span> - PHP FWD Web Scripting 2</p>
    </footer>
</div>    
</body>
</html>