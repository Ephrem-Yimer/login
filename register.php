<?php
	// Include config file
	require_once 'config/config.php';


	// Define variables and initialize with empty values
	$username = $password = $confirm_password = "";

	$username_err = $password_err = $confirm_password_err = "";

	// Process submitted form data
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Check if username is empty
		if (empty(trim($_POST['username']))) {
			$username_err = "Please enter a username.";

			// Check if username already exist
		} else {

			// Prepare a select statement
			$sql = 'SELECT id FROM users WHERE username = ?';

			if ($stmt = $mysql_db->prepare($sql)) {
				// Set parmater
				$param_username = trim($_POST['username']);

				// Bind param variable to prepares statement
				$stmt->bind_param('s', $param_username);

				// Attempt to execute statement
				if ($stmt->execute()) {
					
					// Store executed result
					$stmt->store_result();

					if ($stmt->num_rows == 1) {
						$username_err = 'This username is already taken.';
					} else {
						$username = trim($_POST['username']);
					}
				} else {
					echo "Oops! ${$username}, something went wrong. Please try again later.";
				}

				// Close statement
				$stmt->close();
			} else {

				// Close db connction
				$mysql_db->close();
			}
		}

		// Validate password
	    if(empty(trim($_POST["password"]))){
	        $password_err = "Please enter a password.";     
	    } elseif(strlen(trim($_POST["password"])) < 6){
	        $password_err = "Password must have atleast 6 characters.";
	    } else{
	        $password = trim($_POST["password"]);
	    }
    
	    // Validate confirm password
	    if(empty(trim($_POST["confirm_password"]))){
	        $confirm_password_err = "Please confirm password.";     
	    } else{
	        $confirm_password = trim($_POST["confirm_password"]);
	        if(empty($password_err) && ($password != $confirm_password)){
	            $confirm_password_err = "Password did not match.";
	        }
	    }

	    // Check input error before inserting into database

	    if (empty($username_err) && empty($password_err) && empty($confirm_err)) {

	    	// Prepare insert statement
			$sql = 'INSERT INTO users (username, password) VALUES (?,?)';

			if ($stmt = $mysql_db->prepare($sql)) {

				// Set parmater
				$param_username = $username;
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Created a password

				// Bind param variable to prepares statement
				$stmt->bind_param('ss', $param_username, $param_password);

				// Attempt to execute
				if ($stmt->execute()) {
					// Redirect to login page
					header('location: ./login.php');
					// echo "Will  redirect to login page";
				} else {
					echo "Something went wrong. Try signing in again.";
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
			<title>register</title>
		</head>
    <body>
	    <main>
		    <section class="container">
			    <h2>Register Here</h2>
        	    <!-- <p class="text-center">Please fill in your credentials.</p> -->
        	    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			   		<div class="main-block">
						<div class="form-group <?php (!empty($username_err))?'has_error':'';?>">
							<label for="username">Username</label>
							<input type="text" name="username" id="username" class="form-control" value="<?php echo $username ?>">
							<span class="help-block"><?php echo $username_err;?></span>
						</div>

						<div class="form-group <?php (!empty($password_err))?'has_error':'';?>">
							<label for="password">Password</label>
							<input type="password" name="password" id="password" class="form-control" value="<?php echo $password ?>">
							<span class="help-block"><?php echo $password_err; ?></span>
						</div>

						<div class="form-group <?php (!empty($confirm_password_err))?'has_error':'';?>">
							<label for="confirm_password">Confirm Password</label>
							<input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
							<span class="help-block"><?php echo $confirm_password_err;?></span>
						</div>
						<button type="button"><a href="welcome.php">Submit</a></button>

						<!-- <div class="form-group">
							<input type="submit" class="button" value="Submit">
							<input type="reset" class="button" value="Reset">
						</div> -->
						<p>Already have an account? <a href="login.php">Login here</a>.</p>
						<p>If you want to change password<a href="password_reset.php">Reset</a>,</p>
				   </div>
         	    </form>
		   </section>
	   </main>
    </body>
</html>