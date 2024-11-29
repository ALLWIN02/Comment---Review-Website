<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "review_website_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $pincode = $_POST['pincode'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $terms = isset($_POST['terms']) ? 1 : 0;

    if ($password != $confirm_password) {
        $error_message = "Passwords do not match";
    } elseif (!$terms) {
        $error_message = "You must accept the terms and conditions";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, username, email, phone, country, pincode, password) 
                VALUES ('$name', '$username', '$email', '$phone', '$country', '$pincode', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            header("Location: user_login.php?success=Registration successful");
            exit();
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet" />
    <style>
        body {
            background color:white;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 110vh;
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        .registration-container {
            display: flex;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 900px;
        }

        .registration-image {
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

        .registration-image h1 {
            text-align: center;
            color: white;
        }

        .registration-image p {
            text-align: center;
            color: white;
        }

        .registration-form {
            padding: 40px;
            width: 500px;
        }

        .registration-form h1 {
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

        .registration-form a {
            float: right;
            color: #4169e1;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        [data-tooltip] {
            position: relative;
            cursor: pointer;
        }

        [data-tooltip]:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            background-color: rgba(0, 0, 0, 0.75);
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            bottom: 110%;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }

        [data-tooltip]:hover::before {
            content: "";
            position: absolute;
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.75);
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>
    
    <div class="registration-container">
        <div class="registration-image">
            <h1>Join Us</h1>
            <p>Register to stay connected</p>
        </div>
        <div class="registration-form">
            <h1>Register</h1>
            <?php if (!empty($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-group" >
                    <input type="text" class="form-control" id="name" name="name" placeholder=" " required />
                    <label for="name">Full Name</label>
                </div>
                <div class="form-group" data-tooltip="A username is required to create your account">
                    <input type="text" class="form-control" id="username" name="username" placeholder=" " required />
                    <label for="username">Username</label>
                </div>
                <div class="form-group" data-tooltip="Your email is kept private and is not shared">
                    <input type="email" class="form-control" id="email" name="email" placeholder=" " required />
                    <label for="email">Email</label>
                </div>
                <div class="form-group" data-tooltip="Your phone number will be kept confidential.">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder=" " required />
                    <label for="phone">Phone</label>
                </div>
                <div class="form-group" >
                    <input type="text" class="form-control" id="country" name="country" placeholder=" " required />
                    <label for="country">Country</label>
                </div>
                <div class="form-group" >
                    <input type="text" class="form-control" id="pincode" name="pincode" placeholder=" " required />
                    <label for="pincode">Pincode</label>
                </div>
                <div class="form-group" data-tooltip="Choose a password">
                    <input type="password" class="form-control" id="password" name="password" placeholder=" " required />
                    <label for="password">Password</label>
                </div>
                <div class="form-group" data-tooltip="Re-enter your password">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder=" " required />
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <div class="form-group">
                    <input type="checkbox"  />  I agree to the terms & conditions
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
                <p>Already have an account? <a href="user_login.php">Login now</a></p>
            </form>
        </div>
    </div>
</body>

</html>

