<?php
include 'config.php';

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? strtolower(trim(preg_replace('/\s+/', '_', $_POST['username']))) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message = "Wrong username!";
        $message_type = "error";
    } else {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $message = "Login successful!";
            $message_type = "success";
            // session_start(); $_SESSION['username'] = $username;
        } else {
            $message = "Wrong password!";
            $message_type = "error";
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.jpeg" type="image/jpeg">
    <title>Login</title>
    <style>
        body {
            margin: 0; padding: 0; height: 100vh; display: flex; justify-content: center; align-items: center;
            background: url('backgound.jpg') no-repeat center center fixed; background-size: cover;
            font-family: Arial, sans-serif;
        }
        .box {
            width: 300px; padding: 40px; background: rgba(25, 25, 25, 0.7); text-align: center; border-radius: 10px;
        }
        .box h1 {
            color: white; text-transform: uppercase; font-weight: 500;
        }
        .box input[type="text"], .box input[type="password"] {
            width: 100%; padding: 14px; margin: 10px 0; border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 24px; outline: none; color: white; background: rgba(255, 255, 255, 0.2);
            transition: border-color 0.25s;
        }
        .box input[type="text"]:focus, .box input[type="password"]:focus {
            border-color: #f4bf04;
        }
        .box input[type="submit"] {
            width: 100%; padding: 14px; border: 2px solid #f4bf04; border-radius: 24px; color: white; background: none; cursor: pointer;
            transition: background 0.25s;
        }
        .box input[type="submit"]:hover {
            background: #f4bf04;
        }
        .dialog-box {
            display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 300px; padding: 20px;
            background: rgba(0, 0, 0, 0.8); border: 2px solid #3498db; border-radius: 10px; text-align: center; z-index: 1000;
        }
        .dialog-content { color: white; }
        .dialog-content button { margin-top: 20px; padding: 10px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .dialog-content button:hover { background: #2ecc71; }
        @media (max-width: 768px) {
            .box { width: 80%; }
        }
    </style>
</head>
<body>
    <form class="box" action="login.php" method="post">
        <h1>Login</h1>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
        <p>Don't have an account? <a href="signup.php" style="color: #f4bf04;">Sign Up</a></p>
    </form>

    <div id="dialog" class="dialog-box">
        <div class="dialog-content">
            <p id="dialog-message"></p>
            <button id="dialog-ok-btn">OK</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function showDialog(message, isSuccess) {
                $('#dialog-message').text(message);
                $('#dialog').fadeIn();
                $('#dialog-ok-btn').off('click').on('click', function() {
                    $('#dialog').fadeOut();
                    if (isSuccess) window.location.href = 'mainpage.html';
                });
            }

            <?php if (!empty($message)): ?>
                showDialog("<?php echo $message; ?>", "<?php echo $message_type; ?>" === 'success');
            <?php endif; ?>
        });
    </script>
</body>
</html>

