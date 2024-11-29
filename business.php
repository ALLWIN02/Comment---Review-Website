<?php
session_start();
if (!isset($_GET['id'])) {
    die('Business ID not specified.');
}

$business_id = intval($_GET['id']);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'review_website_db');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch business details
$stmt = $conn->prepare('SELECT name, description, image_url, brand_name FROM businesses WHERE id = ?');
$stmt->bind_param('i', $business_id);
$stmt->execute();
$stmt->bind_result($name, $description, $image_url, $brand_name);
$stmt->fetch();
$stmt->close();


// Fetch review statistics
$rating_counts = [];
$total_reviews = 0;
$avg_rating = 0;

for ($i = 1; $i <= 5; $i++) {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM reviews WHERE business_id = ? AND rating = ?');
    $stmt->bind_param('ii', $business_id, $i);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $rating_counts[$i] = $count;
    $total_reviews += $count;
    $avg_rating += $count * $i; // to calculate average rating
    $stmt->close();
}

$avg_rating = $total_reviews ? round($avg_rating / $total_reviews, 1) : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($name); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="styles.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: bold;
            color: white !important;
        }

        .business-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 80px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .business-image img,h1,h3,p {
            max-width: 600px;
            border-radius: 10px;
            text-align:center;
        }

        .brand-name {
            font-weight: 400;
            color: #6c757d; /* Muted grey color */
            margin-top: -10px; /* Adjust space between the name and brand */
            text-align: center;
        }


        .business-description {
            margin-top: 20px;
            width: 600px;
            
        }

        .rating-breakdown {
            margin-left: 40px;
            max-width: 400px;
            border: 1px solid #007bff;
            padding: 50px;
            width: 700px;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .rating-bar span {
            width: 100px;
            font-weight: bold;
        }

        .bar{
            background-color: #ffc107;
            height: 12px;
            border-radius: 5px;
            flex-grow: 1;
            margin: 0 10px;
        }
        .rating-breakdown {
            margin-top: 20px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 400px;
        }
        i{
            color: #ffc107;
        }
        button i{
            color:white;
        }
        .rating-bar {
            margin-bottom: 15px;
        }

        .star-rating .filled {
             color: #ffc107;
        }

        .total-rating h3 {
         color: #007bff;
         font-weight: bold;
        }

        .progress-bar {
         background-color: #ffc107;
        }


        .total-rating {
            font-size: 20px;
            font-weight: 600;
            color: #007bff;
        }

        .review-container {
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .form-group textarea, .form-group input {
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .star-rating {
            font-size: 1.8rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s;
        }

        .star-rating .filled {
            color: #ffc107;
        }

        .card {
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .card img {
            border-radius: 10px;
            width: 400px;
            height: 200px;
        }
        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
        }

        .review-content {
            flex: 1;
            margin-right: 20px;
        }

        .review-image {
            max-width: 400px;
            max-height: 400px;
            border-radius: 10px;
        }

        .review-image img {
            max-width: 300px;
            height: auto;
            border-radius: 10px;
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
        @media (max-width: 768px) {
            .business-container {
                flex-direction: column;
            }

            .rating-breakdown {
                margin-left: 0;
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top custom-navbar">
      <a class="navbar-brand" href="index.html">
        <img src="img/logo.png" alt="Comment Logo" style="height: 40px; width: auto; margin-right: 10px;">Comment
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarMenu"
        aria-controls="navbarMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarMenu">
        <div class="mx-auto order-0">
          <form class="form-inline my-2 my-lg-0" action="search_results.php" method="GET">
            <input
              class="form-control mr-sm-2"
              type="search"
              name="query"
              placeholder="Search"
              aria-label="Search"
              required
            />
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
              Search
            </button>
          </form>
        </div>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="user_register.php">Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="user_login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="admin_login.php">Admin</a>
          </li>
          <li class="nav-item"></li>
            <a class="nav-link" href="profile.php">User</a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="container">
        <div class="business-container">
            <div class="business-image">
                <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($name); ?>" class="img-fluid mb-2">
                <h1 class="text-muted brand-name"><?php echo htmlspecialchars($brand_name); ?>  <?php echo htmlspecialchars($name); ?></h1>
                <?php 
                $shortDescription = substr($description, 0, 100) . '...'; 
                $fullDescription = htmlspecialchars($description);
                ?>
                <p class="business-description">
                    <span class="short-content"><?php echo htmlspecialchars($shortDescription); ?></span>
                    <span class="content"><?php echo htmlspecialchars($fullDescription); ?></span>
                </p><span class="show-more-btn">Show More</span>
            </div>


            <div class="rating-breakdown">
                <div class="total-rating text-center mb-4">
                    <h3>Average Rating: <?php echo $avg_rating; ?> / 5</h3>
                    <div class="star-rating">
                       <?php for ($i = 1; $i <= 5; $i++): ?>
                           <span class="review-star <?php echo ($i <= $avg_rating) ? 'filled' : ''; ?>">&#9733;</span>
                      <?php endfor; ?>
                    </div>
                 <p class="text-muted">(Based on <?php echo $total_reviews; ?> reviews)</p>
                </div>
    
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <div class="rating-bar d-flex align-items-center">
                        <span class="text-muted"><?php echo $i; ?> <i class="fas fa-star"></i></span>
                        <div class="progress flex-grow-1 mx-3" style="height: 12px;">
                           <div class="progress-bar bg-warning" role="progressbar" 
                              style="width: <?php echo ($total_reviews ? ($rating_counts[$i] / $total_reviews) * 100 : 0); ?>%;" 
                              aria-valuenow="<?php echo $rating_counts[$i]; ?>" aria-valuemin="0" aria-valuemax="100">
                          </div>
                      </div>
                      <span class="text-muted"><?php echo $rating_counts[$i]; ?> reviews</span>
                    </div>
                <?php endfor; ?>
            </div>

        </div>

        <div class="review-container">
            <h2>Submit Your Review</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
            <form action="submit_review.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="business_id" value="<?php echo $business_id; ?>">
                <div class="star-rating mb-3">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span data-value="<?php echo $i; ?>" class="star">&#9733;</span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" id="rating" name="rating" value="">

                <div class="form-group">
                    <textarea class="form-control" id="review" name="review" rows="4" placeholder="Share your experience..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="photo">Add photos</label>
                    <input type="file" class="form-control-file" id="photo" name="photo">
                </div>

                <button type="submit" class="btn btn-primary" id="submitReview" disabled>Post Review</button>
            </form>
            <?php else: ?>
            <p>Please <a href="user_login.php">log in</a> to submit a review.</p>
            <?php endif; ?>
        </div>

        <div class="reviews mt-5">
            <h2>Reviews</h2>
            <?php
            $conn = new mysqli('localhost', 'root', '', 'review_website_db');
            if ($conn->connect_error) {
                die('Connection failed: ' . $conn->connect_error);
            }

            $stmt = $conn->prepare('SELECT users.username, reviews.review, reviews.rating, reviews.created_at, reviews.photo_url, reviews.id, reviews.likes, reviews.dislikes 
                        FROM reviews 
                        JOIN users ON reviews.user_id = users.id 
                        WHERE reviews.business_id = ? 
                        ORDER BY reviews.created_at DESC');

            $stmt->bind_param('i', $business_id);
            $stmt->execute();
            $stmt->bind_result($username, $review, $rating, $created_at, $photo_url, $review_id, $likes, $dislikes);

            while ($stmt->fetch()): ?>
                <div class="card p-3 bg-white">
                
                    <div class="card-body">
                        <div class="review-content">
                            <div class="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="review-star <?php echo ($i <= $rating) ? 'filled' : ''; ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                            <h5 class="card-title"><?php echo htmlspecialchars($username); ?></h5>
                            <?php 
                                $shortreview = substr($review, 0, 40) . '...'; 
                                $fullreview = htmlspecialchars($review);
                            ?>
                            
                            <p class="card-text">
                                <span class="short-content"><?php echo htmlspecialchars($shortreview); ?></span>
                                <span class="content"><?php echo htmlspecialchars($fullreview); ?></span>
                            </p>
                            <span class="show-more-btn">Show More</span>
                            <p class="card-text"><small class="text-muted"><?php echo $created_at; ?></small></p>

                            <div class="like-dislike-buttons">
                                <button class="btn btn-success like-btn" data-review-id="<?php echo $review_id; ?>">
                                    <i class="fas fa-thumbs-up"></i> <span class="like-count"><?php echo $likes; ?></span>
                                </button>
                                <button class="btn btn-danger dislike-btn" data-review-id="<?php echo $review_id; ?>">
                                    <i class="fas fa-thumbs-down"></i> <span class="dislike-count"><?php echo $dislikes; ?></span>
                                </button>
                            </div>
                            
                        </div>

                        <?php if ($photo_url): ?>
                            <div class="review-image">
                                <a href="<?php echo htmlspecialchars($photo_url); ?>" target="_blank">
                                    <img src="<?php echo htmlspecialchars($photo_url); ?>" alt="Review photo">
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>


            <?php endwhile; 
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </main>

    <script>
        document.querySelectorAll('.like-btn, .dislike-btn').forEach(button => {
            button.addEventListener('click', function() {
                let reviewId = this.getAttribute('data-review-id');
                let action = this.classList.contains('like-btn') ? 'like' : 'dislike';

                // AJAX request
                fetch('update_review_rating.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `review_id=${reviewId}&action=${action}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // Update like and dislike counts in the DOM
                        this.closest('.like-dislike-buttons').querySelector('.like-count').textContent = data.likes;
                        this.closest('.like-dislike-buttons').querySelector('.dislike-count').textContent = data.dislikes;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

        document.querySelectorAll('.star-rating .star').forEach(star => {
            star.addEventListener('click', function() {
                let rating = this.getAttribute('data-value');
                document.getElementById('rating').value = rating;

                document.querySelectorAll('.star-rating .star').forEach(s => {
                    s.classList.remove('filled');
                });

                for (let i = 0; i < rating; i++) {
                    document.querySelectorAll('.star-rating .star')[i].classList.add('filled');
                }

                document.getElementById('submitReview').disabled = false;
            });
        });
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