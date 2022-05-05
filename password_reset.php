<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(isset($_SESSION['new_password']) && $_SESSION['confirm_password'] == true){
    header('location: login.php');
    exit;
}
 
// Include config file
require_once 'config/config.php';
 
// Define variables and initialize with empty values
$new_password = $confirm_password = '';
$new_password_err = $confirm_password_err = '';
 
// Processing form data when form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST'){
 
    // Validate new password
    if(empty(trim($_POST['new_password']))){
        $new_password_err = 'Please enter the new password.';     
    } elseif(strlen(trim($_POST['new_password'])) < 6){
        $new_password_err = 'Password must have atleast 6 characters.';
    } else{
        $new_password = trim($_POST['new_password']);
    }
    
    // Validate confirm password
    if(empty(trim($_POST['confirm_password']))){
        $confirm_password_err = 'Please confirm the password.';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = 'Password did not match.';
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = 'UPDATE users SET password = ? WHERE id = ?';
        
        if($stmt = $mysql_db->prepare($sql)){
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_password, $param_id);
            
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Sorry! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }

        // Close connection
        $mysql_db->close();
    }
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="All.css">
    <title>Password_reset</title>
</head>
<body>
    <main >
        <section class="container">
            <h2>Reset Password</h2>
          
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="main-block">
                <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                    <span class="help-block"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <!-- <div class="form-group">
                    <input type="submit" class="btn btn-block btn-primary" value="Submit"> -->
                   <button> <a href="login.php">Cancel</a></button>
                
                <button type="submit">Submit</button>
                </div> 
            </form>
        </section>
    </main>    
</body>

</html>