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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the business details from the database
    $query = "SELECT * FROM businesses WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $business = mysqli_fetch_assoc($result);

    if (!$business) {
        echo "Business not found.";
        exit();
    }
}

// Handle form submission for editing the business
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $brand_name = $_POST['brand_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Check if a new image file is uploaded
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $image_url = $targetFilePath;
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } else {
        $image_url = $business['image_url'];
    }

    // Update query with brand name
    $updateQuery = "UPDATE businesses SET name='$name', brand_name='$brand_name', description='$description', category='$category', image_url='$image_url' WHERE id='$id'";
    if (mysqli_query($conn, $updateQuery)) {
        header('Location: manage_businesses.php');
        exit();
    } else {
        echo "Error updating business: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Business</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin_styles.css" rel="stylesheet">
    <style>
      .form-floating label {
        color: #6c757d;
      }
      .form-floating .form-control:focus {
        border-color: royalblue;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      }
      .tooltip {
        background-color: royalblue;
        color: white;
      }
      .form-control.is-valid {
        border-color: #28a745;
      }
      .form-control.is-invalid {
        border-color: #dc3545;
      }
    </style>
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">Admin Panel</div>
            <div class="list-group list-group-flush">
                <a href="admin_dashboard.html" class="list-group-item">Dashboard</a>
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
                <h1 class="mt-4">Edit Business</h1>

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-floating mb-3">
                                <input type="text" name="name" id="name" class="form-control" value="<?php echo $business['name']; ?>" required>
                                <label for="name">Business Name</label>
                                <div class="invalid-feedback">
                                  Please enter a valid business name.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="brand_name" id="brand_name" class="form-control" value="<?php echo $business['brand_name']; ?>" required>
                                <label for="brand_name">Brand Name</label>
                                <div class="invalid-feedback">
                                  Brand name cannot be blank.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea name="description" id="description" class="form-control" style="height: 100px;" required><?php echo $business['description']; ?></textarea>
                                <label for="description">Description</label>
                                <div class="invalid-feedback">
                                  Please provide a description for the business.
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="category" id="category" class="form-control" value="<?php echo $business['category']; ?>" required>
                                <label for="category">Category</label>
                                <div class="invalid-feedback">
                                  Please enter a valid category.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Business Image<span data-bs-toggle="tooltip" title="Upload an image of the business (JPEG, PNG, GIF)"></span></label>
                                <input type="file" name="image" id="image" class="form-control">
                                <img src="<?php echo $business['image_url']; ?>" alt="Current Image" width="150" class="mt-3">
                                <div class="invalid-feedback">
                                  Only JPG, JPEG, PNG, and GIF files are allowed.
                                </div>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Update Business</button>
                            <a href="manage_businesses.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('input, textarea').on('input', function () {
                if (this.checkValidity()) {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                } else {
                    $(this).addClass('is-invalid').removeClass('is-valid');
                }
            });
        });

        // Toggle Sidebar
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
</body>
</html>
