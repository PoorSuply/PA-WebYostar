<?php
// Include your database connection file
include("../connection.php");

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the 'id' from the URL parameter
    $game_id = $_GET['id'];

    // Fetch game details from the games table
    $gameResult = mysqli_query($conn, "SELECT * FROM games WHERE id = $game_id");

    // Fetch reviews for the specified game from the reviews table
    $reviewsResult = mysqli_query($conn, "SELECT * FROM reviews WHERE game_id = $game_id");
} else {
    // Handle the case where 'id' is not set (you may redirect the user or show an error message)
    echo "Error: Game ID is not set.";
    exit; // or handle the error in another way
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Details</title>
    <style>
        .carousel-container {
            max-width: 300px;
            max-height: 300px;
            margin: auto;
            overflow: hidden;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            width: 100%;
        }

        #prevBtn, #nextBtn {
            cursor: pointer;
            font-size: 24px;
            color: #333;
            background-color: #fff;
            border: none;
            outline: none;
        }

        #prevBtn {
            float: left;
        }

        #nextBtn {
            float: right;
        }
    </style>
</head>

<body>
    <?php
    // Check if game details are found
    if ($gameRow = mysqli_fetch_assoc($gameResult)) {
    ?>
        <h2><?php echo $gameRow['name']; ?> Details</h2>

        <p>Category: <?php echo $gameRow['category']; ?></p>
        <img src="<?php echo $gameRow['thumbnail']; ?>" alt="Game thumbnail" style="max-width: 300px;">

        <!-- Carousel for displaying images -->
        <?php
        $imagePaths = explode(",", $gameRow['images']);
        if (!empty($imagePaths)) {
        ?>
            <div class="carousel-container">
                <button id="prevBtn">&lt;</button>
                <button id="nextBtn">&gt;</button>
                <br>
                <div class="carousel-track">
                    <?php
                    foreach ($imagePaths as $imagePath) {
                    ?>
                        <div class="carousel-item">
                            <img src="<?php echo $imagePath; ?>" alt="Game Image">
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        }
        ?>

        <h3>Reviews:</h3>

        <?php
        // Loop through reviews and display them
        while ($reviewRow = mysqli_fetch_assoc($reviewsResult)) {
        ?>
            <p>Rating: <?php echo $reviewRow['rating']; ?></p>
            <p>Review: <?php echo $reviewRow['review']; ?></p>
        <?php
        }
        ?>
    <?php
    } else {
        // Handle the case where no game details are found for the specified ID
        echo "Error: Game details not found.";
    }
    ?>

    <?php
    // Close database connection
    mysqli_close($conn);
    ?>

    <script>
        const track = document.querySelector('.carousel-track');
        const items = document.querySelectorAll('.carousel-item');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let currentIndex = 0;

        nextBtn.addEventListener('click', () => {
            if (currentIndex < items.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = items.length - 1;
            }
            updateCarousel();
        });

        function updateCarousel() {
            const newPosition = -currentIndex * 100 + '%';
            track.style.transform = 'translateX(' + newPosition + ')';
        }
    </script>
</body>

</html>