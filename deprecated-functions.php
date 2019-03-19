<?php

/**************** Validation functions ********************/

// Use in old version register.php
function validate_user_registration(){

	$errors = [];
	$min = 3;
	$max = 20;

	if($_SERVER['REQUEST_METHOD'] == "POST") {

		$first_name 		= clean($_POST['first_name']);
		$last_name 		    = clean($_POST['last_name']);
		$username 		    = clean($_POST['username']);
		$email 			    = clean($_POST['email']);
		$password		    = clean($_POST['password']);
		$confirm_password	= clean($_POST['confirm_password']);	

		if(strlen($first_name) < $min) {
			$errors[] = "Your first name cannot be less than {$min} characters";
		}

		if(strlen($first_name) > $max) {
			$errors[] = "Your first name cannot be more than {$max} characters";
		}

		if(strlen($last_name) < $min) {
			$errors[] = "Your Last name cannot be less than {$min} characters";
		}

		if(strlen($last_name) > $max) {
			$errors[] = "Your Last name cannot be more than {$max} characters";
		}

		if(strlen($username) < $min) {
			$errors[] = "Your Username cannot be less than {$min} characters";
		}

		if(strlen($username) > $max) {
			$errors[] = "Your Username cannot be more than {$max} characters";
		}
	
		if(username_exist($username)){
			$errors[] = "Sorry that username is already is taken";
		}

		if(email_exist($email)){
			$errors[] = "Sorry that email already is registered";
		}

		if(strlen($email) < $min) {
			$errors[] = "Your email cannot be more than {$max} characters";
		}

		if($password !== $confirm_password) {
			$errors[] = "Your password fields do not match";
		}

		if(!empty($errors)) {
		    
			foreach ($errors as $error) {
			    echo validation_errors($error);			
			}

		} else {

			if(register_user($first_name, $last_name, $username, $email, $password)) {
				set_message("<hr><p class='bg-success text-center'>Please check your email or spam folder for activation link</p>");
				//redirect("index.php");

			} else {
				set_message("<p class='bg-danger text-center'>Sorry we could not register the user</p>");
				redirect("index.php");
			}
		}

	}// post request 
	
}// end function 



/**************** Register user functions ********************/

// deprecated function
function register_user($first_name, $last_name, $username, $email, $password) {

	$first_name = escape($first_name);
	$last_name  = escape($last_name);
	$username   = escape($username);
	$email      = escape($email);
	$password   = escape($password);

	if(email_exist($email)) {

		return false;

	} else if (username_exist($username)) {

		return false;

	} else {

		$password   = md5($password);

		$validation_code = md5($username . microtime());
        
        // register new user in users db
		$sql_users = "INSERT INTO users(first_name, last_name, username, email, password, validation_code, active)";
		$sql_users.= " VALUES('$first_name','$last_name','$username','$email','$password','$validation_code', 0)";
		$result_users = query($sql_users);
		confirm($result_users);
		
		// create new guest profile in guest details
		$sql_guest_profile = "INSERT INTO guest_profile(first_name, last_name, username, email)";
		$sql_guest_profile.= " VALUES('$first_name','$last_name','$username','$email')";
		$result_guest_profile = query_guest_details($sql_guest_profile);
		confirm_guest_details($result_guest_profile);

		$subject = "Activate Account";
		$msg = " Please click the link below to activate your Account
		https://1touradventure.com/activate/?email=$email&code=$validation_code";

		$headers = "From: noreply@1touradventure.com";

		send_email($email, $subject, $msg, $headers);

		return true;

	}

}// end function


/**************** Activate user functions ********************/

// Use in old version activate.php
function activate_user() {

	if($_SERVER['REQUEST_METHOD'] == "GET") {

		if(isset($_GET['email'])) {

			$email = clean($_GET['email']);
			$validation_code = clean($_GET['code']);

			$sql = "SELECT id FROM users WHERE email = '".escape($_GET['email'])."' AND validation_code = '".escape($_GET['code'])."' ";
			$result = query($sql);
			confirm($result);

			if(row_count($result) == 1) {

			$sql2 = "UPDATE users SET active = 1, validation_code = 0 WHERE email = '".escape($email)."' AND validation_code = '".escape($validation_code)."' ";	
			$result2 = query($sql2);
			confirm($result2);

			set_message("<p class='bg-success'>Your account has been activated please login</p>");

			redirect("https://1touradventure.com/login/");

		} else {

			set_message("<p class='bg-danger'>Sorry Your account could not be activated </p>");

			redirect("https://1touradventure.com/login/");

			}
		} 
	}
}// end function 
 


/**************** Validate user login functions ********************/

// Use in old version login.php
function validate_user_login(){

	$errors = [];
	$min = 3;
	$max = 20;

	if($_SERVER['REQUEST_METHOD'] == "POST") {

		$email 		    = clean($_POST['email']);
		$password	    = clean($_POST['password']);
		$remember   	= isset($_POST['remember']);

		if(empty($email)) {

			$errors[] = "Email field cannot be empty";

		}
		
		if(empty($password)) {

			$errors[] = "Password field cannot be empty";

		}

		if(!empty($errors)) {

				foreach ($errors as $error) {

				echo validation_errors($error);
				
				}

			} else {

				if(login_user($email, $password, $remember)) {
					
					if(isset($_SESSION['buy'])){
						unset($_SESSION['buy']);
						redirect("https://1touradventure.com/checkout/");
						//redirect("https://1touradventure.com/dummy-checkout/");
						//redirect("https://1touradventure.com/dev-confirm-order/");

					} else {

						//redirect("https://1touradventure.com/guest-dashboard/");
						redirect("https://1touradventure.com/guest-profile/");
					}
							
				} else {

				echo validation_errors("Your credentials are not correct");		

				}
			}
	}

}// end function 


/**************** Logged In function use in all guest account page ********************/

// Function have not been used
function logged_in(){

	if(isset($_SESSION['email']) || isset($_COOKIE['email'])){
		
		return true;

	} else {

		return false;
	}

}// end function


// Function have not been used
function logged_out(){

	session_destroy();

	if(isset($_COOKIE['email'])) {

	unset($_COOKIE['email']);

	}

}// end function


/**************** Recover Password function ********************/

// old version used in recover.php
function recover_password() {

	if($_SERVER['REQUEST_METHOD'] == "POST") {

        // check token
		if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {

			$email = clean($_POST['email']);

            // check email existance
			if(email_exist($email)) {

            // create validation code
			$validation_code = md5($email . microtime());

            // set validation cookies
			setcookie('temp_access_code', $validation_code, time() + 900, '/');

            // update users db validation code
			$sql = "UPDATE users SET validation_code = '".escape($validation_code)."' WHERE email = '".escape($email)."'";
			$result = query($sql);

            // prepare validation code and email to user
			$subject = "Please reset your password";
			$message =  "Here is your password reset code = {$validation_code}, \nClick here to reset your password https://1touradventure.com/code/?email=$email&code=$validation_code";
			$headers = "From: noreply@1touradventure.com";

			send_email($email, $subject, $message, $headers);

			set_message("<p class='bg-success text-center'>Please check your email or spam folder for a password reset code</p>");

			} else {
			
				echo validation_errors("This emails does not exist");
			}

		} else {

			redirect("index.php");

		}// token checks
 
		if(isset($_POST['cancel_submit'])) {

			redirect("https://1touradventure.com/login/");

		}

	} // post request

}// end function




?>