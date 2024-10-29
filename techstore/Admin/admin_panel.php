<?php
// Database connection
$conn = new mysqli('sql103.infinityfree.com', 'if0_37406853', 'bE0pyDa57O2mONc', 'if0_37406853_techstore');


if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Initialize message variable
$message = "";

// Handle game operations (add, edit, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adding a new game
    if (isset($_POST['add_game'])) {
        $name = $_POST['name'];
        $image = $_POST['image'];
        $description = $_POST['description'];
        $rating = min(5, floatval($_POST['rating'])); // Ensure rating doesn't exceed 5
        $video = $_POST['video'];
        $download_link = $_POST['download_link'];
        $category = $_POST['category'];

        $sql = "INSERT INTO games (name, image, description, rating, video, download_link, category)
                VALUES ('$name', '$image', '$description', '$rating', '$video', '$download_link', '$category')";
        
        if ($conn->query($sql)) {
            $message = "Game added successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
    // Editing a game
    elseif (isset($_POST['edit_game'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $image = $_POST['image'];
        $description = $_POST['description'];
        $rating = min(5, floatval($_POST['rating'])); // Ensure rating doesn't exceed 5
        $video = $_POST['video'];
        $download_link = $_POST['download_link'];
        $category = $_POST['category'];

        $sql = "UPDATE games SET 
                name='$name', image='$image', description='$description', rating='$rating', 
                video='$video', download_link='$download_link', category='$category' 
                WHERE id='$id'";
        
        if ($conn->query($sql)) {
            $message = "Game updated successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Deleting a game
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM games WHERE id='$id'";
    
    if ($conn->query($sql)) {
        $message = "Game deleted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch games with pagination
$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$result = $conn->query("SELECT * FROM games LIMIT $limit OFFSET $offset");
$total_result = $conn->query("SELECT COUNT(*) as count FROM games")->fetch_assoc();
$total_games = $total_result['count'];
$total_pages = ceil($total_games / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f9f9f9; }
        .container { width: 90%; margin: auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: #343a40; color: white; padding: 10px; }
        .btn { padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; margin-right: 5px; border-radius: 4px; }
        .btn:hover { background-color: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background-color: #343a40; color: white; }
        td img { width: 100px; height: auto; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); }
        .modal-content { background-color: white; margin: 5% auto; padding: 20px; border-radius: 10px; width: 50%; max-height: 80%; overflow-y: auto; }
        .close { color: #aaa; float: right; font-size: 28px; cursor: pointer; }
        .pagination a { padding: 8px 16px; margin: 0 4px; border: 1px solid #ddd; color: black; text-decoration: none; }
        .pagination a.active { background-color: #28a745; color: white; }
        input, textarea, select { width: 100%; padding: 8px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container">
    <header class="header">
        <h1>Admin Panel - Manage Games</h1>
        <div>
            <button class="btn" onclick="window.location.href='adminmainpage.html';">Back</button>
            <button class="btn" onclick="window.location.href='login.html';">Logout</button>
        </div>
    </header>

    <?php if ($message) echo "<div style='color: green;'>$message</div>"; ?>

    <button class="btn" onclick="showAddForm()">Add New Game</button>

    <table>
        <thead>
            <tr><th>ID</th><th>Name</th><th>Image</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Game Image"></td>
                    <td>
                        <button type="button" class="btn" onclick="showEditForm(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo ($i === $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>

    <!-- Add Game Modal -->
    <div id="add-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('add-modal')">&times;</span>
            <h2>Add New Game</h2>
            <form method="POST">
                <input type="text" name="name" placeholder="Game Name" required>
                <input type="text" name="image" placeholder="Image URL" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <input type="number" step="0.1" name="rating" placeholder="Rating (0-5)" max="5" required>
                <input type="text" name="video" placeholder="Video URL" required>
                <input type="text" name="download_link" placeholder="Download Link" required>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="action">Action</option>
                    <option value="sports">Sports</option>
                    <option value="fighting">Fighting</option>
                    <option value="open world">Open World</option>
                    <option value="simulation">Simulation</option>
                    <option value="shooting">Shooting</option>
                </select>
                <button type="submit" name="add_game" class="btn">Add Game</button>
            </form>
        </div>
    </div>

    <!-- Edit Game Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('edit-modal')">&times;</span>
            <h2>Edit Game</h2>
            <form method="POST">
                <input type="hidden" id="edit-id" name="id">
                <input type="text" id="edit-name" name="name" placeholder="Game Name" required>
                <input type="text" id="edit-image" name="image" placeholder="Image URL" required>
                <textarea id="edit-description" name="description" placeholder="Description" required></textarea>
                <input type="number" step="0.1" id="edit-rating" name="rating" placeholder="Rating (0-5)" max="5" required>
                <input type="text" id="edit-video" name="video" placeholder="Video URL" required>
                <input type="text" id="edit-download_link" name="download_link" placeholder="Download Link" required>
                <select id="edit-category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="action">Action</option>
                    <option value="sports">Sports</option>
                    <option value="fighting">Fighting</option>
                    <option value="open world">Open World</option>
                    <option value="simulation">Simulation</option>
                </select>
                <button type="submit" name="edit_game" class="btn">Update Game</button>
            </form>
        </div>
    </div>

</div>

<script>
    function showAddForm() {
        document.getElementById('add-modal').style.display = 'block';
    }
    
    function showEditForm(game) {
        document.getElementById('edit-id').value = game.id;
        document.getElementById('edit-name').value = game.name;
        document.getElementById('edit-image').value = game.image;
        document.getElementById('edit-description').value = game.description;
        document.getElementById('edit-rating').value = game.rating;
        document.getElementById('edit-video').value = game.video;
        document.getElementById('edit-download_link').value = game.download_link;
        document.getElementById('edit-category').value = game.category;
        document.getElementById('edit-modal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        var modals = document.getElementsByClassName('modal');
        for (var i = 0; i < modals.length; i++) {
            if (event.target === modals[i]) {
                closeModal(modals[i].id);
            }
        }
    }
</script>

</body>
</html>

<?php $conn->close(); ?>




