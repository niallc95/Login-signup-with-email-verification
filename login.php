<?php

require_once 'codeBase/loginCode.php';


/**
 * Token based authentication
 */

if(isset($_GET['tkn'])){

	$user = User::findByToken($_GET['tkn']);

	/**
	 *If the user is logged in redirect to success page
	 */
	if($user){
		$user->login();
		redirect('success.php');
	}

	/**
	 * If not logged in redirect back to login page
	 */
	redirect('login.php');
}


/**
 *Handle logout
 */

if(isset($_GET['logout'])){

	$user = new User();

	if($user->loggedIn()){
		$user->logout();
	}

	redirect('login.php');
}


/**
 *Ensure logged in users are redirected
 */
$user = new User();

if($user->loggedIn()){
	redirect('success.php');
}

/**
 * Submit login page with AJAX
 * (_SERVER['HTTP_X_REQUESTED_WITH']) Assures request is AJAX
 */

try{
	//If post is not empty and request is ajax
	if(!empty($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])){

		//Output JSON header
		header('Content-type: application/json');

		//Ensure email address is valid
		if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			throw new Exception('Please ensure your email address is valid');
		}

		/**
		 * Login attempt counter and exception handler
		 */

			rate_limit($_SERVER['REMOTE_ADDR']);

			// Record login attempt
			rate_limit_tick($_SERVER['REMOTE_ADDR'], $_POST['email']);

			// Send the message to the user
			$message = 'Please verify your Toucan account';
			$email = $_POST['email'];
			$subject = 'Toucan registration';

			if(!User::exists($email)){
				$subject = "Wow!! You're great!! ";
				$message = "Thanks for registering BRUUUHHHHH\n\n";
			}

		/**
		 * Login or register the user
		 */

			$user = User::loginOrRegister($_POST['email']);

			$message.= "You can login from this URL:\n";
			$message.= get_page_url()."?tkn=".$user->generateToken()."\n\n";

			$message.= "The link is going expire automatically after 10 minutes.";

			$result = send_email($fromEmail, $_POST['email'], $subject, $message);

			if(!$result){
				throw new Exception("There was an error sending your email. Please try again.");
			}

			die(json_encode(array(
				'message' => 'Thank you! We\'ve sent a link to your inbox. Check your spam folder as well.'
			)));
		}
	}
catch(Exception $e){

	die(json_encode(array(
		'error'=>1,
		'message' => $e->getMessage()
	)));
}
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8"/>
		<title>Login</title>

		<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
		<link href="Styling/css/style.css" rel="stylesheet" />
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

	</head>

	<body>

		<form id="login-register" method="post" action="login.php">

			<h1>Login or Register</h1>

			<input type="text" placeholder="your@email.com" name="email" autofocus />
			<p>Please enter your email address</p>

			<button type="submit">Login / Register</button>

			<span></span>

		</form>

		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="Styling/js/script.js"></script>

	</body>
</html>