<?php
session_start();

function connectToDatabase() {
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'review_website_db';

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    return $conn;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Connect to the database
    $conn = connectToDatabase();

    $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        header('Location: index.html');
        exit();
    } else {
        $error_message = "Invalid Username or Password!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet" />
    <style>
        body {
            background-color:white;
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
        }

        .login-image {
            background: url('img/log.jpg') no-repeat center center;
            background-size: cover;
            width: 300px;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            flex-direction: column;
        }

        .login-image h1 {
            text-align: center;
            color: white;
        }

        .login-image p {
            text-align: center;
            color: white;
        }

        .login-form {
            padding: 40px;
            width: 350px;
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

        .login-form a {
            float: right;
            color: #4169e1;
        }

        .login-form p {
            margin-top: 20px;
            text-align: center;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Tooltip styles */
        [data-tooltip] {
            position: relative;
            cursor: pointer;
        }

        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            background-color: #333;
            color: #fff;
            padding: 5px;
            font-size: 12px;
            border-radius: 5px;
            white-space: nowrap;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 1;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        [data-tooltip]::after {
            content: '';
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>
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

    <div class="login-container">
        <div class="login-image">
            <h1>Welcome Back</h1>
            <p>Login to stay connected</p>
        </div>
        <div class="login-form">
            <h1>Login</h1>
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group" data-tooltip="Please enter your username">
                    <input type="text" class="form-control" id="username" name="username" placeholder=" " required />
                    <label for="username">Username</label>
                </div>
                <div class="form-group" data-tooltip="Enter your password">
                    <input type="password" class="form-control" id="password" name="password" placeholder=" " required />
                    <label for="password">Password</label>
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-primary">Log In</button>
            </form>
            <p>Don't have an account? <a href="user_register.php">Signup</a></p>
        </div>
    </div>
</body>
</html>
