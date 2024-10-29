<?php
$host = 'sql103.infinityfree.com'; 
$db = 'if0_37406853_techstore'; 
$user = 'if0_37406853'; 
$pass = 'bE0pyDa57O2mONc'; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$categories = $conn->query("SELECT DISTINCT category FROM games")->fetchAll(PDO::FETCH_ASSOC);
$games = $conn->query("SELECT * FROM games")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.jpeg" type="image/jpeg">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Games</title>
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
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
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

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .categories {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar {
            margin-bottom: 10px;
        }

        .search-bar input {
            padding: 10px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #categoryList {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        #categoryList li {
            margin: 5px;
            cursor: pointer;
            padding: 10px 15px;
            background-color:  #007bff; /* Changed to green */
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        #categoryList li:hover {
            background-color:  #007bff; /* Darker green on hover */
        }

        .game-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
            width: 100%;
        }

        .game-card {
            background-color: #fff;
            border-radius: 5px;
            margin: 10px;
            padding: 10px;
            width: 180px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            padding: 8px 12px;
            background-color: #007bff; /* Original blue */
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

       .game-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            width: 75%; /* Adjust width for better layout */
            margin-left: 20px; /* Margin adjustment */
        }
        .game-card {
            background: white;
            padding: 15px;
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            width: calc(20% - 20px);
            overflow: hidden;
        }
        .game-card img {
            max-width: 100%;
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .game-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }
        .stars {
            color: gold;
            font-size: 1.2em;
        }
        .download-link {
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s;
            width: 100%;
        }
        .download-link:hover {
            background: #218838;
            transform: scale(1.05);
        }
        .game-detail {
            display: none;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            width: 80%;
            max-width: 600px;
            overflow-y: auto; /* Allow scrolling for long details */
            max-height: 80%; /* Limit height for better UX */
        }
        .close-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
            color: #dc3545;
        }

        /* Styling for iframe to make it responsive */
        #youtube-video {
            width: 100%;
            height: 315px;
        }
          @media (max-width: 768px) {
            .game-card {
                flex: 1 1 calc(45% - 20px); /* Adjust for medium screens */
                max-width: none; /* Remove max width to fit more */
            }
        }

        @media (max-width: 480px) {
            .game-card {
                flex: 1 1 calc(90% - 20px); /* Adjust for small screens */
            }

            .search-bar input {
                width: 100%; /* Full width on small screens */
            }
        }
    </style>
</head>
<body>
<div id="preloader">
        <div id="loader"></div>
      </div>
    <div id="header"></div>

    <div class="container">
        <div class="categories">
        <br><br><br><br><br>
            <h2>Categories</h2>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search games..." onkeyup="searchGames()">
            </div>
            <ul id="categoryList">
                <li onclick="filterGames('all')">All Games</li>
                <?php foreach ($categories as $category): ?>
                    <li onclick="filterGames('<?php echo htmlspecialchars($category['category']); ?>')">
                        <?php echo htmlspecialchars($category['category']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="game-cards" id="game-cards"></div>
    </div>

    <div class="pagination" id="pagination"></div>

    <div id="game-detail" class="game-detail">
        <span class="close-icon" onclick="closeDetails()">✖</span>
        <h2 id="detail-name"></h2>
        <img id="detail-image" src="" alt="" style="max-width:100%; height:auto;"/>
        <h2>Rating: <span id="detail-rating"></span></h2>
        <p>Description: <span id="detail-description"></span></p>
        <p>Video:</p>
        <iframe id="youtube-video" src="" frameborder="0" allowfullscreen></iframe>
        <button id="detail-download-link" class="download-link" onclick="window.location.href='';">Download Now</button>
    </div>

    <script>
        const games = <?php echo json_encode($games); ?>;
        let filteredGames = games; // Default to all games
        const gamesPerPage = 10;
        let currentPage = 1;

        function renderGames() {
            const gameCardsContainer = document.getElementById('game-cards');
            gameCardsContainer.innerHTML = '';

            const startIndex = (currentPage - 1) * gamesPerPage;
            const endIndex = startIndex + gamesPerPage;

            const paginatedGames = filteredGames.slice(startIndex, endIndex);

            paginatedGames.forEach(game => {
                const gameCard = document.createElement('div');
                gameCard.classList.add('game-card');
                gameCard.dataset.category = game.category;
                gameCard.innerHTML = `
                    <img src="${game.image}" alt="${game.name}" style="max-width: 100%; border-radius: 5px;">
                    <h3>${game.name}</h3>
                    <p class="stars">${'★'.repeat(game.rating) + '☆'.repeat(5 - game.rating)}</p>
                    <button class="download-link" onclick="event.stopPropagation();window.location.href='${game.download_link}'">Download Now</button>
                `;
                gameCard.onclick = () => showDetails(game);
                gameCardsContainer.appendChild(gameCard);
            });

            renderPagination();
        }

        function renderPagination() {
            const totalPages = Math.ceil(filteredGames.length / gamesPerPage);
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';

            for (let page = 1; page <= totalPages; page++) {
                const pageLink = document.createElement('a');
                pageLink.href = '#';
                pageLink.innerText = page;
                if (page === currentPage) {
                    pageLink.style.fontWeight = 'bold';
                }
                pageLink.onclick = (e) => {
                    e.preventDefault();
                    currentPage = page;
                    renderGames();
                };
                paginationContainer.appendChild(pageLink);
            }
        }

        function filterGames(category) {
            if (category === 'all') {
                filteredGames = games;
            } else {
                filteredGames = games.filter(game => game.category === category);
            }
            currentPage = 1; // Reset to first page
            renderGames();
        }

        function showDetails(game) {
            document.getElementById('detail-name').innerText = game.name;
            document.getElementById('detail-image').src = game.image;
            document.getElementById('detail-rating').innerText = game.rating;
            document.getElementById('detail-description').innerText = game.description;
            document.getElementById('youtube-video').src = `https://www.youtube.com/embed/${getYoutubeId(game.video)}`;
            document.getElementById('detail-download-link').onclick = () => window.location.href = game.download_link;
            document.getElementById('game-detail').style.display = 'block';
        }

        function closeDetails() {
            document.getElementById('game-detail').style.display = 'none';
        }

        function searchGames() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            filteredGames = games.filter(game => game.name.toLowerCase().includes(searchTerm));
            currentPage = 1; // Reset to first page
            renderGames();
        }

        function getYoutubeId(url) {
            const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
            const match = url.match(regex);
            return match ? match[1] : '';
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

        // Initial rendering of games
        renderGames();
    </script>
       <script src="header.js"></script>
</body>
</html>


























