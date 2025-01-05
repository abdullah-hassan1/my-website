<?php
ob_start();
session_start();
require_once 'dbconnect.php';

$email = ""; // Initialize the $email variable
$emailError = ""; // Initialize the $emailError variable
$passError = ""; // Initialize the $passError variable

if (isset($_SESSION['user']) != "") {
    header("Location: home.php");
    exit;
}

$error = false;

if (isset($_POST['btn-login'])) {    

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $pass = trim($_POST['pass']);
    $pass = strip_tags($pass);
    $pass = htmlspecialchars($pass);

    if (empty($email)) {
        $error = true;
        $emailError = "Please enter your email address.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter a valid email address.";
    }

    if (empty($pass)) {
        $error = true;
        $passError = "Please enter your password.";
    }

    if (!$error) {
        $password = hash('sha256', $pass); // password hashing using SHA256
        
        // Using mysqli_query() and mysqli_fetch_array()
        $res = mysqli_query($conn, "SELECT userId, userName, userPass FROM users WHERE userEmail='$email'");
        $row = mysqli_fetch_array($res);
        $count = mysqli_num_rows($res); 

        if ($count == 1 && $row['userPass'] == $password) {
            $_SESSION['user'] = $row['userId'];
            header("Location: home.php");
        } else {
            $errMSG = "Incorrect Credentials, Try again...";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPA Calculator</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div class="container">

    <div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
        <div class="col-md-12">
        
            <div class="form-group">
                <h2 class="">Sign In</h2>
            </div>
        
            <div class="form-group">
                <hr />
            </div>
            
            <?php
            if (isset($errMSG)) {
                ?>
                <div class="form-group">
                <div class="alert alert-danger">
                <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
                </div>
                <?php
            }
            ?>
            
            <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?php echo $email; ?>" maxlength="40" />
                </div>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            
            <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input type="password" name="pass" class="form-control" placeholder="Your Password" maxlength="15" />
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
            
            <div class="form-group">
                <hr />
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary" name="btn-login">Login</button>
            </div>
            
            <div class="form-group">
                <hr />
            </div>
            
            <div class="form-group">
                <p> New member? </p>
                <a class="btn btn-block btn-info" href="register.php">Go To Sign Up</a>
            </div>
        
        </div>
   
    </form>
    </div>    

</div>

</body>
</html>
<?php ob_end_flush(); ?>
