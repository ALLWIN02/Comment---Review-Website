<?php
session_start();
$adminId = 14; // Example admin ID
// Database connection parameters
$servername = "localhost"; // usually "localhost" if using XAMPP
$username = "root"; // Default user for XAMPP is 'root'
$password = ""; // Default password for XAMPP is an empty string
$dbname = "review_website_db"; // Replace with your actual database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch admin profile data from database
$query = "SELECT * FROM users WHERE id = '$adminId'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

// Handle profile update (for POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update query and logic here...
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Profile</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="admin_styles.css" rel="stylesheet" />
    <style>
      body {
        font-family: "Roboto", sans-serif;
        background-color: #f1f4f9;
        color: #333;
      }

      #wrapper {
        display: flex;
      }

      #sidebar-wrapper {
        min-height: 100vh;
        width: 250px;
        background-color: #1e1e2d;
        color: #ffffff;
        position: fixed;
        transition: all 0.3s ease;
      }

      #sidebar-wrapper .sidebar-heading {
        padding: 2rem;
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        background-color: #1e1e2d;
        letter-spacing: 1px;
      }

      .list-group-item {
        padding: 1.2rem 1.5rem;
        background-color: #1e1e2d;
        color: #ffffff;
        border: none;
        transition: all 0.3s ease;
      }

      .list-group-item:hover {
        background-color: #4e73df;
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
      }

      #page-content-wrapper {
        margin-left: 250px;
        padding: 2rem;
        width: 100%;
      }

      .navbar {
        background-color: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        border-radius: 10px;
      }

      .profile-card {
        background-color: #fff;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-top: 2rem;
      }

      .profile-card h3 {
        color: #4e73df;
        font-weight: bold;
        margin-bottom: 1.5rem;
      }

      .form-control {
        border-radius: 50px;
        padding: 10px 20px;
      }

      .btn-primary {
        background-color: #4e73df;
        border-radius: 50px;
        padding: 10px 25px;
      }

      .btn-primary:hover {
        background-color: #3751a0;
      }
    </style>
  </head>
  <body>
    <div class="d-flex" id="wrapper">
      <!-- Sidebar -->
      <div id="sidebar-wrapper">
        <div class="sidebar-heading">Admin Panel</div>
        <div class="list-group list-group-flush">
          <a href="add_business.html" class="list-group-item">Add Business</a>
          <a href="manage_businesses.php" class="list-group-item"
            >Manage Businesses</a
          >
          <a href="manage_reviews.php" class="list-group-item">Manage Reviews</a>
          <a href="user_management.php" class="list-group-item"
            >User Management</a
          >
        </div>
      </div>

      <!-- Page Content -->
      <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg">
          <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>
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
        </nav>

        <div class="container-fluid">
          <div class="profile-card">
            <h3>Admin Profile</h3>
            <p><strong>Name:</strong> <?php echo $admin['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $admin['email']; ?></p>
            <p><strong>Phone:</strong> <?php echo $admin['phone']; ?></p>
            <p><strong>Country:</strong> <?php echo $admin['country']; ?></p>
            <p><strong>Pincode:</strong> <?php echo $admin['pincode']; ?></p>

            <button class="btn btn-primary" onclick="showUpdateForm()">Update Profile</button>

            <!-- Profile Update Form (Initially hidden) -->
            <form id="updateForm" method="POST" action="" style="display: none; margin-top: 2rem;">
              <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $admin['name']; ?>" required />
              </div>
              <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $admin['email']; ?>" required />
              </div>
              <div class="mb-3">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $admin['phone']; ?>" required />
              </div>
              <div class="mb-3">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" value="<?php echo $admin['country']; ?>" required />
              </div>
              <div class="mb-3">
                <label for="pincode">Pincode</label>
                <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo $admin['pincode']; ?>" required />
              </div>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Toggle Sidebar Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
      });

      // Show the update form on button click
      function showUpdateForm() {
        document.getElementById("updateForm").style.display = "block";
      }
    </script>
  </body>
</html>
