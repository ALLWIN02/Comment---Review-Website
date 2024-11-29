<?php
$host = 'localhost'; // or your host name
$user = 'root'; // your database user
$pass = ''; // your database password
$db = 'review_website_db'; // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Fetch the user's data based on ID
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission to update user data
if (isset($_POST['update_user'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $pincode = $_POST['pincode'];

    $query = "UPDATE users SET name = ?, username = ?, email = ?, phone = ?, country = ?, pincode = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $name, $username, $email, $phone, $country, $pincode, $user_id);
    
    if ($stmt->execute()) {
        $message = "User updated successfully.";
        header("Location: user_management.php");
    } else {
        $message = "Error updating user: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin_styles.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">Admin Panel</div>
            <div class="list-group list-group-flush">
                <a href="add_business.html" class="list-group-item">Add Business</a>
                <a href="manage_businesses.php" class="list-group-item">Manage Businesses</a>
                <a href="manage_reviews.php" class="list-group-item">Manage Reviews</a>
                <a href="user_management.php" class="list-group-item active">User Management</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light">
                <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">Go to Website</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid">
                <h1 class="mt-4 text-center">Edit User</h1>

                <?php if (isset($message)) : ?>
                    <div class="alert alert-info"><?= $message ?></div>
                <?php endif; ?>

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <form action="edit_user.php?id=<?= $user['id'] ?>" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= $user['phone'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="<?= $user['country'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="pincode" class="form-label">Pin Code</label>
                                <input type="text" class="form-control" id="pincode" name="pincode" value="<?= $user['pincode'] ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success" name="update_user">Update User</button>
                            <a href="user_management.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
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

<?php
$conn->close();
?>
