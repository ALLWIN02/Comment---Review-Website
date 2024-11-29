<?php
if (!isset($_GET['business_id'])) {
    die('Business ID not specified.');
}

$business_id = intval($_GET['business_id']);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'review_website_db');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

            // Updated SQL query to include the photo_url field
    $stmt = $conn->prepare('SELECT users.username, reviews.review, reviews.rating, reviews.created_at, reviews.photo_url FROM reviews JOIN users ON reviews.user_id = users.id WHERE reviews.business_id = ? ORDER BY reviews.created_at DESC');
    $stmt->bind_param('i', $business_id);
    $stmt->execute();
    $stmt->bind_result($username, $review, $rating, $created_at, $photo_url);
    while ($stmt->fetch()): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($username); ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">Rating: <?php echo htmlspecialchars($rating); ?>/5</h6>
                <p class="card-text"><?php echo htmlspecialchars($review); ?></p>
                        
                <?php if ($photo_url): // Check if a photo URL exists ?>
                    <a href="<?php echo htmlspecialchars($photo_url); ?>" target="_blank">
                        <img src="<?php echo htmlspecialchars($photo_url); ?>" alt="Review photo" class="img-fluid mb-3" style="width: 200px; height: auto;">
                    </a>
                <?php endif; ?>
                        
                <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($created_at); ?></small></p>
            </div>
        </div>
    <?php endwhile;
    $stmt->close();
    $conn->close();
?>
