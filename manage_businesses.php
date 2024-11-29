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

// Fetch all businesses from the database
$query = "SELECT * FROM businesses ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if(isset($_POST['delete'])) {
    $id = $_POST['business_id'];
    $deleteQuery = "DELETE FROM businesses WHERE id='$id'";
    mysqli_query($conn, $deleteQuery);
    header('Location: manage_businesses.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Businesses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin_styles.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar and Navigation -->
    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">Admin Panel</div>
            <div class="list-group list-group-flush">
                <a href="add_business.html" class="list-group-item">Add Business</a>
                <a href="manage_businesses.php" class="list-group-item active">Manage Businesses</a>
                <a href="manage_reviews.php" class="list-group-item">Manage Reviews</a>
                <a href="user_management.php" class="list-group-item">User Management</a>
            </div>
        </div>

        <!-- Page Content -->
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
                <h1 class="mt-4">Manage Businesses</h1>

                <!-- Businesses Table -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Brand Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['brand_name']; ?></td>
                            <td><?php echo substr($row['description'], 0, 50); ?>...</td>
                            <td><?php echo $row['category']; ?></td>
                            <td><img src="<?php echo $row['image_url']; ?>" alt="Business Image" width="100"></td>
                            <td>
                                <a href="edit_business.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                <form method="POST" action="" style="display:inline-block;">
                                    <input type="hidden" name="business_id" value="<?php echo $row['id']; ?>">
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
