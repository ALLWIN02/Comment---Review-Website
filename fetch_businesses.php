<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Businesses in Category</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .business-card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
            
        }
        .business-card:hover {
            transform: translateY(-5px);
        }
        .product-img {
            max-width: 300px;
            height: 300px;
            border-radius: 8px;
        }
        .business-details {
            display: flex;
            align-items: center;
        }
        .business-info {
            margin-left: 20px;
            width: 100%;
        }
        .business-title {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .rating-stars {
            color: #ffc107;
        }
        .short-content, .content {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .content {
            display: none;
        }
        .show-more-btn {
            cursor: pointer;
            color: #007bff;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .explore-btn {
            text-align: right;
        }
        .explore-link {
            background-color: #007bff;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .explore-link:hover {
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <?php
        $conn = new mysqli('localhost', 'root', '', 'review_website_db');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $category = isset($_GET['category']) ? $_GET['category'] : '';

        if ($category) {
            $stmt = $conn->prepare(
                'SELECT b.id, b.name, b.brand_name, b.description, b.image_url, 
                        AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count 
                 FROM businesses b 
                 LEFT JOIN reviews r ON b.id = r.business_id 
                 WHERE b.category = ? 
                 GROUP BY b.id 
                 ORDER BY b.id DESC
                 LIMIT 20'
            );
            $stmt->bind_param('s', $category);
        } else {
            $stmt = $conn->prepare(
                'SELECT b.id, b.name, b.brand_name, b.description, b.image_url, 
                        AVG(r.rating) AS avg_rating, COUNT(r.id) AS review_count 
                 FROM businesses b 
                 LEFT JOIN reviews r ON b.id = r.business_id 
                 GROUP BY b.id 
                 ORDER BY b.id DESC
                 LIMIT 20'
            );
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ratingStars = str_repeat('★', round($row['avg_rating'])) . str_repeat('☆', 5 - round($row['avg_rating']));

                echo '<div class="col-md-6 mb-4">';
                echo '<div class="business-card p-3 bg-white">';
                echo '<div class="business-details">';
                echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '" class="product-img">';
                echo '<div class="business-info">';
                echo '<h5 class="business-title mb-1">' . htmlspecialchars($row['brand_name']) . ' ' . htmlspecialchars($row['name']) . '</h5>';
                echo "<p class='mb-1 text-warning rating-stars'>" . $ratingStars . " | " . htmlspecialchars($row['review_count']) . " Reviews</p>";
                
                $shortDescription = substr($row['description'], 0, 100) . '...';
                $fullDescription = htmlspecialchars($row['description']);
                
                echo '<p class="mb-1">';
                echo '<span class="short-content">' . htmlspecialchars($shortDescription) . '</span>';
                echo '<span class="content">' . $fullDescription . '</span>';
                echo '</p>';
                echo '<span class="show-more-btn">Show More</span>';
                
                echo '</div>'; // Close business-info
                echo '</div>'; // Close business-details
                
                echo '<div class="explore-btn mt-3">';
                echo '<a href="business.php?id=' . htmlspecialchars($row['id']) . '" class="explore-link">Explore</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="col-12">';
            echo '<div class="alert alert-warning">No businesses found in this category.</div>';
            echo '</div>';
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>

<script>
    document.querySelectorAll('.show-more-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var content = this.previousElementSibling.querySelector('.content');
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

</body>
</html>
