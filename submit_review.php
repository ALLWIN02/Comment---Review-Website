<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $business_id = intval($_POST['business_id']);
    $user_id = $_SESSION['user_id'];
    $review = htmlspecialchars($_POST['review'], ENT_QUOTES, 'UTF-8');
    $rating = intval($_POST['rating']);
    
    // File upload handling
    $target_dir = "uploads1/";
    $photo_url = null; // Default value if no photo is uploaded

    if (!empty($_FILES["photo"]["name"])) {
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image or fake image
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            $upload_ok = 1;
        } else {
            echo "File is not an image.";
            $upload_ok = 0;
        }

        // Check file size (limit to 2MB)
        if ($_FILES["photo"]["size"] > 2097152) {
            echo "Sorry, your file is too large.";
            $upload_ok = 0;
        }

        // Allow certain file formats (JPEG, PNG, and GIF)
        if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            $upload_ok = 0;
        }

        // Check if $upload_ok is set to 0 by an error
        if ($upload_ok == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload the file
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo_url = $target_file; // Store the file path
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'review_website_db');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Modify the query to include the photo_url
    $stmt = $conn->prepare('INSERT INTO reviews (business_id, user_id, review, rating, photo_url) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('iisis', $business_id, $user_id, $review, $rating, $photo_url);

    if ($stmt->execute()) {
        header('Location: business.php?id=' . $business_id);
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'You must be logged in to submit a review.';
}
