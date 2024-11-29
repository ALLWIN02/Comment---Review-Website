<?php
// Connect to the database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "review_website_db";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query
$searchQuery = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            width: 350px;
            height:auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card img {
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff;
        }
        .short-content, .full-content {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .full-content {
            display: none;
        }
        .show-more-btn {
            cursor: pointer;
            color: #007bff;
            font-size: 0.9rem;
            text-decoration: none;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <?php
    if ($searchQuery) {
        // Search query for businesses
        $sql = "SELECT * FROM businesses WHERE name LIKE '%$searchQuery%' OR description LIKE '%$searchQuery%' OR category LIKE '%$searchQuery%' OR brand_name LIKE '%$searchQuery%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h1>Search Results for '$searchQuery'</h1>";
            echo "<div class='row'>";

            while ($row = $result->fetch_assoc()) {
                $shortDescription = substr($row['description'], 0, 100) . '...';
                $fullDescription = htmlspecialchars($row['description']);

                echo "
                <div class='col-md-6 col-lg-4 mb-4'>
                    <div class='card'>
                        <img src='" . htmlspecialchars($row['image_url']) . "' class='card-img-top' alt='" . htmlspecialchars($row['name']) . "'>
                        <div class='card-body'>
                            <h5 class='card-title'>" . htmlspecialchars($row['brand_name']) . " " . htmlspecialchars($row['name']) . "</h5>
                            <p>
                                <span class='short-content'>" . htmlspecialchars($shortDescription) . "</span>
                                <span class='full-content'>" . $fullDescription . "</span>
                            </p>
                            <span class='show-more-btn'>Show More</span>
                            <a href='business.php?id=" . intval($row['id']) . "' class='btn btn-primary mt-3'>View Business</a>
                        </div>
                    </div>
                </div>";
            }

            echo "</div>";
        } else {
            echo "<h1>No results found for '$searchQuery'</h1>";
        }
    } else {
        echo "<h1>Invalid search query.</h1>";
    }
    
    $conn->close();
    ?>
</div>

<script>
    document.querySelectorAll('.show-more-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var content = this.previousElementSibling.querySelector('.full-content');
            var shortContent = this.previousElementSibling.querySelector('.short-content');

            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'inline';
                shortContent.style.display = 'none';
                this.textContent = 'Show Less';
            } else {
                content.style.display = 'none';
                shortContent.style.display = 'inline';
                this.textContent = 'Show More';
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
