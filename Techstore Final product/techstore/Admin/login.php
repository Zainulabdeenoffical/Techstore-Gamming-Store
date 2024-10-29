<?php
session_start();

// Connect to the database
$conn = new mysqli("sql103.infinityfree.com", "if0_37406853", "bE0pyDa57O2mONc", "if0_37406853_techstore");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize login attempts and lockout time if not set
if (!isset($_SESSION['attempt_count'])) {
    $_SESSION['attempt_count'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = 0;
}

// Get the current time
$current_time = time();

// Check if the user is locked out
if ($current_time < $_SESSION['lockout_time']) {
    $remaining_time = ($_SESSION['lockout_time'] - $current_time);
    $minutes_remaining = ceil($remaining_time / 60);
    die("Too many failed attempts. Please try again in " . $minutes_remaining . " minute(s).");
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    // Get the username and password from the form
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Fetch the stored hashed password from the database
    $selectStmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
    $selectStmt->bind_param("s", $username);
    $selectStmt->execute();
    $result = $selectStmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verify the submitted password against the stored hashed password
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, authenticate the user
            $_SESSION['authenticated'] = true;
            $_SESSION['attempt_count'] = 0; // Reset attempt count on successful login
            header("Location: adminmainpage.html"); // Redirect to Admin main page
            exit();
        } else {
            // Password is incorrect
            $_SESSION['attempt_count']++;
            if ($_SESSION['attempt_count'] >= 2) {
                $_SESSION['lockout_time'] = $current_time + 60; // Lockout for 1 minute
                die("Too many failed attempts. Please try again in 1 minute.");
            } else {
                echo "Invalid username or password.";
            }
        }
    } else {
        // Username not found
        $_SESSION['attempt_count']++;
        if ($_SESSION['attempt_count'] >= 2) {
            $_SESSION['lockout_time'] = $current_time + 60; // Lockout for 1 minute
            die("Too many failed attempts. Please try again in 1 minute.");
        } else {
            echo "Invalid username or password.";
        }
    }

    $selectStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <style>
        body {
            background-image: url('backgound.jpg'); /* Path to your background image */
            background-size: cover; /* Cover the entire screen */
            background-position: center; /* Center the background image */
            color: white; /* Change text color to white for visibility */
            font-family: Arial, sans-serif; /* Font style */
            display: flex; /* Use flexbox for centering */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full viewport height */
            margin: 0; /* Remove default margin */
        }
        form {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background for the form */
            padding: 20px;
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Shadow effect */
            width: 300px; /* Fixed width for the form */
        }
        h2 {
            text-align: center; /* Center the heading */
            margin-bottom: 15px; /* Space below heading */
        }
        label {
            display: block; /* Block display for labels */
            margin: 5px 0; /* Margin around labels */
        }
        input[type="text"], input[type="password"] {
            width: 100%; /* Full width */
            padding: 10px; /* Padding inside input fields */
            margin: 10px 0; /* Margin between input fields */
            border: none; /* Remove default border */
            border-radius: 5px; /* Rounded corners */
        }
        input[type="submit"] {
            background-color: #4CAF50; /* Green background for submit button */
            color: white; /* White text */
            border: none; /* Remove default border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            padding: 10px; /* Padding inside submit button */
            transition: background-color 0.3s; /* Smooth background change on hover */
            font-weight: bold; /* Bold text */
        }
        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        .error-message {
            color: red; /* Red color for error messages */
            text-align: center; /* Center error messages */
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Login</h2>
        <div class="error-message">
            <?php
            // Display error message if any
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>
        </div>
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Login">
    </form>
</body>
</html>




