<?php
session_start();


// Connect to the database
$conn = new mysqli("sql103.infinityfree.com", "if0_37406853", "bE0pyDa57O2mONc", "if0_37406853_techstore");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the secret key from the database
$secret_key_query = $conn->query("SELECT secret_key FROM secret_keys WHERE id = 1");
if ($secret_key_query->num_rows > 0) {
    $row = $secret_key_query->fetch_assoc();
    $stored_secret_key = $row['secret_key'];
} else {
    die('Secret key not found in the database.');
}

// Check if user is already authenticated by session
if (!isset($_SESSION['authenticated'])) {
    // Check if access key is provided and matches the stored secret key
    if (isset($_POST['access_key']) && $_POST['access_key'] === $stored_secret_key) {
        $_SESSION['authenticated'] = true; // Mark user as authenticated
    } else {
        // If not authenticated, show the access key form
        echo '<form method="POST">
                <label for="access_key">Enter Access Key:</label>
                <input type="password" name="access_key" required>
                <input type="submit" value="Authenticate">
              </form>';
        exit(); // Stop further execution unless authenticated
    }
}

// Automatically hash password and insert into the database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    // Get the username and password from the form
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Hash the password before inserting
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Use a prepared statement to prevent SQL injection and insert hashed password
    $insertStmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    $insertStmt->bind_param("ss", $username, $hashedPassword);

    if ($insertStmt->execute()) {
        echo "Admin user inserted with hashed password.";
    } else {
        echo "Error: " . $conn->error;
    }

    $insertStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
 <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/6703c7e302d78d1a30ed9ac5/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</head>
<body>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Signup">
    </form>
</body>
</html>
