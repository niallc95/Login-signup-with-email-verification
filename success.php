<?php
require_once 'codeBase/loginCode.php';

$user = new User();

if(!$user->loggedIn()){
	redirect('login.php');
}

?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8"/>
		<title>Logged in Baby!! </title>
		<link href="Styling/css/style.css" rel="stylesheet" />


			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

	</head>

	<body>

		<div id="success-page">
			<img src="Styling/img/lock.jpg" alt="Lock" />
			<h1>You are logged in!</h1>

			<p>Email: <b><?php echo $user->email ?></b><br /> Rank: <b style="text-transform:capitalize"><?php echo $user->rank() ?></b>
			</p>

			<a href="login.php?logout=1" class="logout-button">Logout</a>

		</div>

	</body>
</html>