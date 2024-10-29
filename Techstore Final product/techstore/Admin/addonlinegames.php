<?php
// Connect to the database

$conn = new mysqli("sql103.infinityfree.com", "if0_37406853", "bE0pyDa57O2mONc", "if0_37406853_techstore");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding new game
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_game'])) {
    $game_name = $_POST['game_name'] ?? '';
    $game_embed = $_POST['game_embed'] ?? '';
    $game_image = $_POST['game_image'] ?? '';

    // Insert new game into the table
    $sql = "INSERT INTO online_games (game_name, game_embed, game_image) VALUES ('$game_name', '$game_embed', '$game_image')";
    if ($conn->query($sql) === TRUE) {
        $message = "<p class='success'>New game added successfully!</p>";
    } else {
        $message = "<p class='error'>Error: " . $conn->error . "</p>";
    }
}

// Delete game functionality
if (isset($_GET['delete'])) {
    $game_id = $_GET['delete'];
    $conn->query("DELETE FROM online_games WHERE id='$game_id'");
}

// Handle form submission for editing game
if (isset($_POST['edit_game'])) {
    $game_id = $_POST['game_id'];
    $game_name = $_POST['game_name'];
    $game_embed = $_POST['game_embed'];
    $game_image = $_POST['game_image'];

    // Update game in the database
    $sql = "UPDATE online_games SET game_name='$game_name', game_embed='$game_embed', game_image='$game_image' WHERE id='$game_id'";
    if ($conn->query($sql) === TRUE) {
        $message = "<p class='success'>Game updated successfully!</p>";
    } else {
        $message = "<p class='error'>Error: " . $conn->error . "</p>";
    }
}

// Pagination logic
$limit = 5; // Number of games to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_games_result = $conn->query("SELECT COUNT(*) AS total FROM online_games");
$total_games = $total_games_result->fetch_assoc()['total'];
$total_pages = ceil($total_games / $limit);

$games = $conn->query("SELECT * FROM online_games LIMIT $limit OFFSET $offset");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Games</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 0;
            min-height: 100vh;
        }
        header {
            background-color: #4CAF50;
            width: 100%;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            position: relative; /* Added for positioning buttons */
        }
        header button {
            background-color: #fff; /* Button color */
            color: #4CAF50; /* Text color */
            border: none;
            padding: 10px 15px;
            margin: 0 5px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            position: absolute; /* Position buttons */
            top: 15px;
        }
        header .back-button {
            left: 15px; /* Back button position */
        }
        header .logout-button {
            right: 15px; /* Logout button position */
        }
        .container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }
        h2, h3 {
            color: #4CAF50;
            margin-bottom: 15px;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .games-list {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .game-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .game-item:last-child {
            border-bottom: none;
        }
        .game-item img {
            width: 50px;
            height: auto;
            margin-right: 10px;
            border-radius: 5px;
        }
        .game-actions button, .game-actions a {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-decoration: none;
            margin-left: 5px;
        }
        .game-actions button:hover, .game-actions a:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        .success {
            color: green;
            margin: 10px 0;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .pagination a {
            color: #4CAF50;
            padding: 8px 16px;
            text-decoration: none;
            margin: 0 5px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .pagination a:hover {
            background-color: #4CAF50;
            color: white;
        }
        .active {
            background-color: #4CAF50;
            color: white;
        }
    </style>
    <script>
        function openModal(game) {
            document.getElementById('game_id').value = game.id;
            document.getElementById('edit_game_name').value = game.game_name;
            document.getElementById('edit_game_embed').value = game.game_embed;
            document.getElementById('edit_game_image').value = game.game_image;
            document.getElementById('editModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) closeModal();
        }
    </script>
</head>
<body>
    <header>
        Admin Panel - Manage Games
        <button class="back-button" onclick="window.location.href='adminmainpage.html'">Back</button>
        <button class="logout-button" onclick="window.location.href='login.php'">Logout</button>
    </header>
    <div class="container">
        <?php if (isset($message)) echo $message; ?>
        <h2>Add New Game</h2>
        <form method="POST" action="">
            <input type="text" name="game_name" placeholder="Game Name" required>
            <input type="text" name="game_embed" placeholder="Game Embed URL" required>
            <input type="text" name="game_image" placeholder="Game Image URL" required>
            <input type="submit" name="add_game" value="Add Game">
        </form>
        <h2>Current Games</h2>
        <div class="games-list">
            <?php while ($game = $games->fetch_assoc()): ?>
                <div class="game-item">
                    <img src="<?php echo $game['game_image']; ?>" alt="<?php echo $game['game_name']; ?>">
                    <div>
                        <strong><?php echo $game['game_name']; ?></strong>
                        <div class="game-actions">
                            <button onclick='openModal(<?php echo json_encode($game); ?>)'>Edit</button>
                            <a href="?delete=<?php echo $game['id']; ?>" onclick="return confirm('Are you sure you want to delete this game?');">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($i === $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>

    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Edit Game</h3>
            <form method="POST" action="">
                <input type="hidden" name="game_id" id="game_id" value="">
                <input type="text" name="game_name" id="edit_game_name" placeholder="Game Name" required>
                <input type="text" name="game_embed" id="edit_game_embed" placeholder="Game Embed URL" required>
                <input type="text" name="game_image" id="edit_game_image" placeholder="Game Image URL" required>
                <input type="submit" name="edit_game" value="Update Game">
            </form>
        </div>
    </div>
</body>
</html>

