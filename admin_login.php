<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "review_website_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the username matches admin's ID (admin has ID 14)
    $query = "SELECT * FROM users WHERE name = '$username' AND id = 14 LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        
        // Verify password (assuming it's stored hashed)
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: admin_dashboard.html'); // Redirect to admin dashboard
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet" />
    <style>
        body {
            background color:white;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        .login-container {
            display: flex;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
        }

        .login-form {
            padding: 40px;
            width: 100%;
        }

        .login-form h1 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #007bff;
        }

        .form-group label {
            font-weight: bold;
            transition: all 0.2s ease;
            pointer-events: none;
            position: absolute;
            left: 15px;
            top: 10px;
            color: #999;
        }

        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            top: -20px;
            font-size: 12px;
            color: #4169e1;
        }

        .form-group input {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            padding-left: 15px;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #4169e1;
            border-color: #4169e1;
            width: 100%;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #365aa0;
            border-color: #365aa0;
        }

        .login-form p {
            text-align: center;
            color: red;
            font-size: 14px;
        }

        
    </style>
</head>
<body>
    
    <div class="login-container">
        <div class="login-form">
            <h1>Admin Login</h1>
            <?php if (!empty($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-group" >
                    <input type="text" class="form-control" id="username" name="username" placeholder=" " required />
                    <label for="admin_username">Username</label>
                </div>
                <div class="form-group" >
                    <input type="password" class="form-control" id="password" name="password" placeholder=" " required />
                    <label for="admin_password">Password</label>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html>

