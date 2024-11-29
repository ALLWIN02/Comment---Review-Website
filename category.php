<!-- category.php -->
<?php
// Get the category from the URL
$category = isset($_GET['category']) ? $_GET['category'] : 'All';

// Sanitize the category to prevent SQL injection if using directly in SQL queries
$category = htmlspecialchars($category);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $category; ?> - Review Website</title>
  <link
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link href="styles.css" rel="stylesheet" />
</head>
<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top custom-navbar">
    <a class="navbar-brand" href="index.html">
      <img src="img/logo.png" alt="Comment Logo" style="height: 40px; width: auto; margin-right: 10px;">Comment
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
        <form class="form-inline my-2 my-lg-0" action="search.php" method="get">
          <input
            class="form-control mr-sm-2"
            type="search"
            placeholder="Search"
            aria-label="Search"
            name="query"
          />
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
            Search
          </button>
        </form>
      </div>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="register.html">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.html">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="profile.php">User</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Content -->
  <main role="main" class="container-fluid mt-5 pt-3">
    <div class="jumbotron">
      <h1><?php echo $category; ?></h1>
      <div id="businessList" class="list-group">
        <!-- Business listings will be loaded here dynamically -->
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script>
    $(document).ready(function () {
      fetchBusinesses();

      function fetchBusinesses() {
        $.ajax({
          url: 'fetch_businesses.php',
          method: 'GET',
          data: { category: '<?php echo $category; ?>' },
          success: function (data) {
            $('#businessList').html(data);
          },
          error: function (xhr, status, error) {
            console.error('AJAX Error: ' + status + error);
          },
        });
      }
    });
  </script>
</body>
</html>
