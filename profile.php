<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "review_website_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user profile details
$sql_user = "SELECT username, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$stmt_user->close();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql_update = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('sssi', $new_username, $new_email, $new_password, $user_id);

    if ($stmt_update->execute()) {
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating profile: " . $stmt_update->error . "</div>";
    }
    $stmt_update->close();
}

// Fetch user reviews
$sql = "SELECT b.name AS business_name, r.rating, r.review As review_text, r.created_at As review_date 
        FROM reviews r 
        JOIN businesses b ON r.business_id = b.id 
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="styles.css" rel="stylesheet" />
    <style>
        .star-rating {
            color: #FFD700;
        }
        

        /* Profile Styling */
        .container {
            max-width: 900px;
            margin: auto;
            padding: 30px;
        }
        .list-group-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }
        .list-group-item:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        h4 {
            margin-bottom: 15px;
            color: #333;
            font-size: 1.5rem;
            font-weight: 600;
        }
        p {
            margin-bottom: 10px;
            font-size: 1rem;
        }
        .list-group-item p strong {
            color: #00aaff;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #00aaff;
            border-color: #00aaff;
        }
        .btn-primary:hover {
            background-color: #008ecc;
            border-color: #008ecc;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav
      class="navbar navbar-expand-md navbar-dark bg-dark fixed-top custom-navbar"
    >
      <a class="navbar-brand" href="index.html">
        <img src="img/logo.png" alt="Comment Logo" style="height: 40px; width: auto; margin-right: 10px;">comment
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

    <!-- Profile Content -->
    <div class="container mt-5 pt-3">
        <h2 class="text-center">Your Profile</h2>
        <div class="card p-4 mb-4">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">New Password (Leave blank to keep current password)</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
            </form>
        </div>

        <h3 class="mt-4">Your Reviews</h3>
        <div class="list-group">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="list-group-item">
                    <h4><?php echo htmlspecialchars($row['business_name']); ?></h4>
                    <p>
                        <strong>Rating:</strong>
                        <span class="star-rating">
                            <?php
                                $rating = $row['rating'];
                                $filled_stars = str_repeat('★', $rating);
                                $empty_stars = str_repeat('☆', 5 - $rating);
                                echo $filled_stars . $empty_stars;
                            ?>
                        </span>
                    </p>
                    <p><strong>Review:</strong> <?php echo htmlspecialchars($row['review_text']); ?></p>
                    <p><em>Posted on: <?php echo date('F j, Y', strtotime($row['review_date'])); ?></em></p>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No reviews found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
