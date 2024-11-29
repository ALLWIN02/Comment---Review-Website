<?php
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password (blank if not set)
$dbname = "review_website_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all reviews from the database
$query = "SELECT reviews.*, businesses.name AS business_name, users.username AS user_name 
          FROM reviews 
          JOIN businesses ON reviews.business_id = businesses.id 
          JOIN users ON reviews.user_id = users.id 
          ORDER BY reviews.created_at DESC";
$result = mysqli_query($conn, $query);

// Handle approval of reviews
if (isset($_POST['approve'])) {
    $id = $_POST['review_id'];
    $approveQuery = "UPDATE reviews SET status='approved' WHERE id='$id'";
    mysqli_query($conn, $approveQuery);
    header('Location: manage_reviews.php');
    exit();
}

// Handle deletion of reviews
if (isset($_POST['delete'])) {
    $id = $_POST['review_id'];
    $deleteQuery = "DELETE FROM reviews WHERE id='$id'";
    mysqli_query($conn, $deleteQuery);
    header('Location: manage_reviews.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin_styles.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">Admin Panel</div>
            <div class="list-group list-group-flush">
                <a href="add_business.html" class="list-group-item">Add Business</a>
                <a href="manage_businesses.php" class="list-group-item">Manage Businesses</a>
                <a href="manage_reviews.php" class="list-group-item active">Manage Reviews</a>
                <a href="user_management.php" class="list-group-item">User Management</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light">
                <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.html">Go to Website</a></li>
                        <li class="nav-item"><a class="nav-link" href="admin_profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid">
                <h1 class="mt-4">Manage Reviews</h1>
                <!-- Reviews Table -->
                <table class="table table-hover mt-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Business</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Photo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['user_name']; ?></td>
                                <td><?php echo $row['business_name']; ?></td>
                                <td><?php echo $row['rating']; ?>/5</td>
                                <td><?php echo substr($row['review'], 0, 50); ?>...</td>
                                <td><?php echo isset($row['status']) ? ucfirst($row['status']) : 'N/A'; ?></td> <!-- Modified line -->
                                <td><?php echo date("F j, Y", strtotime($row['created_at'])); ?></td>
                                <td>
                                    <?php if ($row['photo_url']): ?>
                                    <img src="<?php echo $row['photo_url']; ?>" alt="Review Photo" width="80" height="80">
                                    <?php else: ?>
                                    No Photo
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($row['status']) && $row['status'] == 'pending'): ?>
                                    <form method="POST" action="" style="display:inline-block;">
                                        <input type="hidden" name="review_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="approve" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <?php endif; ?>
                                    <form method="POST" action="" style="display:inline-block;">
                                        <input type="hidden" name="review_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Toggle Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
</body>
</html>
