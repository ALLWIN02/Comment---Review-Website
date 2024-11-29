<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $brand_name = htmlspecialchars($_POST['brand_name']); // New field
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);

    // Handle image upload
    $target_dir = "uploads/";
    $image = $_FILES['image']['name'];
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'review_website_db');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Insert into database including brand name
    $stmt = $conn->prepare('INSERT INTO businesses (name, brand_name, description, category, image_url) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $name, $brand_name, $description, $category, $target_file);

    if ($stmt->execute()) {
        // JavaScript for alert and redirect
        echo "<script>
            alert('Business added successfully!');
            window.location.href = 'admin_dashboard.html';
        </script>";
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
