<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="signupform.css">
  <title>Document</title>
</head>
<body>
    <?php
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

if (isset($_POST['signup_submit'])) {
  // Get form data
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);

  // Validate form data
  if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
    // Display error message
    $error_message = "All fields are required";
  } elseif ($password !== $password_confirm) {
    // Display error message
    $error_message = "Passwords do not match";
  } else {
    // Create user in database
    $sql = "INSERT INTO signup (username, email, password) VALUES ('$username', '$email', '$password')";
    if (mysqli_query($conn, $sql)) {
      // Create table for user
      $table_name = "$username" . mysqli_insert_id($conn);
      $sql = "CREATE TABLE $table_name (tenantname varchar(255), tenantemail varchar(255), tenantphone varchar(255), tenantnrc varchar(255), tenantlocation varchar(255), tenantpropertynumber varchar(255), rentalamount varchar(255), firstduedate varchar(255))";
      if (mysqli_query($conn, $sql)) {
        // Start session for user
        session_start();
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        $_SESSION['username'] = $username;

        // Redirect to dashboard
        header("Location: gettingData.php");
        exit();
      } else {
        // Display error message
        $error_message = "Error creating table for user: " . mysqli_error($conn);
      }
    } else {
      // Display error message
      $error_message = "Error creating user: " . mysqli_error($conn);
    }
  }
}


?>

<!-- Sign up form -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="username">Username:</label><br>
    <input type="text" name="username" id="username"><br>
    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email"><br>
    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password"><br>
    <label for="password_confirm">Confirm Password:</label><br>
    <input type="password" name="password_confirm" id="password_confirm"><br><br>
    <input type="submit" value="Sign Up" name="signup_submit">

</form>
<?php
if (isset($error_message)) {
  echo $error_message;
}
?>
</body>
</html>