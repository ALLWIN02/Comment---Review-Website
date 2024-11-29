<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['error' => 'You must be logged in to like or dislike reviews.']));
}

$user_id = $_SESSION['user_id'];
$review_id = intval($_POST['review_id']);
$action = $_POST['action'];  // 'like' or 'dislike'

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'review_website_db');
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if the user has already liked or disliked the review
$stmt = $conn->prepare('SELECT id, action FROM review_ratings WHERE user_id = ? AND review_id = ?');
$stmt->bind_param('ii', $user_id, $review_id);
$stmt->execute();
$stmt->bind_result($rating_id, $current_action);
$stmt->fetch();
$stmt->close();

if ($rating_id) {
    // If the user is changing their action, update the review_ratings table and adjust likes/dislikes counts accordingly
    if ($current_action !== $action) {
        $stmt = $conn->prepare('UPDATE review_ratings SET action = ? WHERE id = ?');
        $stmt->bind_param('si', $action, $rating_id);
        $stmt->execute();
        $stmt->close();

        if ($action == 'like') {
            $conn->query("UPDATE reviews SET likes = likes + 1, dislikes = dislikes - 1 WHERE id = $review_id");
        } else {
            $conn->query("UPDATE reviews SET likes = likes - 1, dislikes = dislikes + 1 WHERE id = $review_id");
        }
    }
} else {
    // Add a new rating entry if the user has not yet liked/disliked the review
    $stmt = $conn->prepare('INSERT INTO review_ratings (user_id, review_id, action) VALUES (?, ?, ?)');
    $stmt->bind_param('iis', $user_id, $review_id, $action);
    $stmt->execute();
    $stmt->close();

    if ($action == 'like') {
        $conn->query("UPDATE reviews SET likes = likes + 1 WHERE id = $review_id");
    } else {
        $conn->query("UPDATE reviews SET dislikes = dislikes + 1 WHERE id = $review_id");
    }
}

// Fetch updated likes and dislikes count
$stmt = $conn->prepare('SELECT likes, dislikes FROM reviews WHERE id = ?');
$stmt->bind_param('i', $review_id);
$stmt->execute();
$stmt->bind_result($likes, $dislikes);
$stmt->fetch();
$stmt->close();

$conn->close();

// Return updated counts
echo json_encode(['likes' => $likes, 'dislikes' => $dislikes]);
?>
