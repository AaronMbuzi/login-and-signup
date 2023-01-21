<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://classless.de/classless.css">
    <title>Document</title>
</head>
<body>



<?php
session_start();

// Connect to MySQL database
$host = "localhost:3308";
$user = "root";
$password = "";
$dbname = "tenant";

$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['login_submit'])) {
  // Get form data
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  // Validate form data
  if (empty($username) || empty($password)) {
    // Display error message
    $error_message = "All fields are required";
  } else {
    // Check if user exists in database
    $sql = "SELECT * FROM signup WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      // Start session for user
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];

      // Redirect to dashboard
      header("Location: gettingData.php");
      exit();
    } else {
      // Display error message
      $error_message = "Incorrect username or password";
    }
  }
}
?>

<!-- Login form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="username">Username:</label><br>
    <input type="text" name="username" id="username"><br>
    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password"><br><br>
    <input type="submit" value="Login" name="login_submit">

</form>
<?php
if (isset($error_message)) {
  echo $error_message;
}
?>


</body>
</html>
