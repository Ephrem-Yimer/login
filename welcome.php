<?php
	// Initialize session
	session_start();

	if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== false) {
		header('location: login.php');
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="All.css">
    <title>Welcome</title>
</head>
<body>
	<main>
		<section class="container">
			<div class="page-header">
				<h2>Welcome <?php echo $_SESSION['username']; ?></h2>
			</div>

			<!-- <a href="password_reset.php" class="btn btn-block btn-outline-warning">Reset Password</a> -->
			<button><a href="logout.php" class="button">Sign Out</a></button>
		</section>
	</main>
</body>
</html>