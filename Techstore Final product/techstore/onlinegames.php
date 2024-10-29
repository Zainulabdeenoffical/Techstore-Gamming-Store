<?php
//$conn = new mysqli("localhost", "root", "", "techstore");
$conn = new mysqli("sql103.infinityfree.com", "if0_37406853", "bE0pyDa57O2mONc", "if0_37406853_techstore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$games_output = "";
$sql = "SELECT game_name, game_embed, game_image FROM online_games";
$result = $conn->query($sql);
if ($result === false) {
    $games_output = "Error: " . $conn->error;
} else {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $games_output .= "<div class='game-card'>
                                <div class='game-title' style='color: black;'>{$row['game_name']}</div>
                                <img src='{$row['game_image']}' alt='{$row['game_name']} Image' class='game-image'>
                                <div class='game-embed-container' style='display: none; overflow-y: auto;'>
                                    <iframe class='game-frame' src='{$row['game_embed']}' allowfullscreen></iframe>
                                </div>
                                <button class='fullscreen-btn' onclick='toggleGame(this)' style='background-color: green;'>Play Now</button>
                                <button class='close-btn' onclick='closeGame(this)' style='display: none; background-color: green;'>Stop Game</button>
                              </div>";
        }
    } else {
        $games_output = "No games found.";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="icon.jpeg" type="image/jpeg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="header.css">
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
    <title>Games Page</title>
    <style>
        #preloader {
    position: fixed;
    width: 100%;
    height: 100%;
    background-color: rgb(36, 32, 32);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

#loader {
    border: 8px solid #f3f3f3; 
    border-top: 8px solid #3498db; 
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; flex-direction: column; align-items: center; margin: 0; height: 100vh; }
        h1 { margin: 20px 0; font-size: 28px; color: #333; }
        .search-container { margin-bottom: 20px; }
        .search-input { padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; width: 280px; }
        .game-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; flex-grow: 1; align-items: flex-start; }
        .game-card { width: 280px; border-radius: 10px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); background-color: white; text-align: center; margin-bottom: 20px; position: relative; overflow: hidden; }
        .game-card:hover { transform: scale(1.05); box-shadow: 0 0 25px rgba(0, 0, 0, 0.3); }
        .game-title { padding: 10px; font-size: 22px; background-color: #fff; color: black; }
        .game-image { width: 100%; height: auto; border-bottom: 1px solid #ccc; }
        .game-embed-container { width: 100%; height: 250px; display: none; overflow: hidden; }
        iframe { width: 100%; height: 100%; border: none; }
        .fullscreen-btn, .close-btn { margin: 10px; padding: 10px; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .fullscreen-btn:hover, .close-btn:hover { opacity: 0.8; }
        @media (max-width: 600px) { .game-card { width: 90%; } }
    </style>
</head>
<body>
<div id="preloader">
        <div id="loader"></div>
      </div>
<div id="header"></div>
     <br><br><br><br><br><br>
    <h1>All Games</h1>
    <div class="search-container">
        <input type="text" class="search-input" placeholder="Search for games..." onkeyup="searchGames()">
    </div>
    <div class="game-container" id="game-container">
        <?php echo $games_output; ?>
    </div>
    <script>
        function toggleGame(button) {
            const gameCard = button.parentElement;
            const iframeContainer = gameCard.querySelector('.game-embed-container');
            const gameImage = gameCard.querySelector('.game-image');
            const closeButton = gameCard.querySelector('.close-btn');

            if (iframeContainer.style.display === 'none') {
                gameImage.style.display = 'none';
                iframeContainer.style.display = 'block';
                closeButton.style.display = 'inline-block';
                button.textContent = 'Fullscreen';
            } else {
                if (iframeContainer.requestFullscreen) iframeContainer.requestFullscreen();
                button.style.display = 'none';
            }
        }

        function closeGame(button) {
            const gameCard = button.parentElement;
            const iframeContainer = gameCard.querySelector('.game-embed-container');
            const gameImage = gameCard.querySelector('.game-image');
            const fullscreenButton = gameCard.querySelector('.fullscreen-btn');

            gameImage.style.display = 'block';
            iframeContainer.style.display = 'none';
            button.style.display = 'none';
            fullscreenButton.style.display = 'inline-block';
            fullscreenButton.textContent = 'Play Now';
            const iframe = gameCard.querySelector('.game-frame');
            iframe.src = iframe.src; // Reset the iframe to stop the game
        }

        function searchGames() {
            const input = document.querySelector('.search-input').value.toLowerCase();
            const gameCards = document.querySelectorAll('.game-card');
            gameCards.forEach(card => {
                const title = card.querySelector('.game-title').textContent.toLowerCase();
                card.style.display = title.includes(input) ? 'block' : 'none';
            });
        }
             window.addEventListener('load', function(){
  
  const preloader = document.getElementById('preloader');
  if (preloader) {
    preloader.style.display = 'none';
  }
  

  if (document.body) {
    document.body.classList.remove('loading');
  }
});


if (document.body) {
  document.body.classList.add('loading');
}
    </script>
    <script src="header.js"></script>
</body>
</html>
