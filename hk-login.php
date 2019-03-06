<?php
/*
  Plugin Name: 1TOUR User Login
  Version: 1.0
  Author: Hwuk Kok
  Author URI: https://1touradventure.com
  Description: Customer Login System Plugin
  Text Domain: 1touradventure.com
  Domain Path: /languages
  License: GPL
*/

// include("signup.php");
include("language.php");
include("config.php");



/**************** 20 Feb 2019 Form Validation ********************/

// === Enqueue form-validation scripts into register page ===
add_action( 'wp_enqueue_scripts', 'form_validation' );

function form_validation() {

	wp_enqueue_script( 'form-validation', plugin_dir_url( __FILE__ ) . 'form-validation.js', array(), '123', true );
	
	wp_localize_script(
	    'form-validation',  // ajax script handler
	    'admin_ajax',       // ajax object
	    array('ajaxurl' => admin_url('admin-ajax.php?signup=true')) // ajax url
	    );
}

// === Register check_email into wp_ajax ===
add_action('wp_ajax_nopriv_check_email', 'check_email');

function check_email(){
    
    /******** TEST *******/
    // echo "Check Email Now!";                                     /*TEST*/
    // echo json_encode(array('error' => 'email_success'));         /*TEST*/
    // echo json_encode(array('error' => 'email_fail'));            /*TEST*/
    // die(json_encode($_POST));                                    /*TEST*/
    // echo $_POST['check_email'];                                  /*TEST*/
    
    global $db_login_dbname, $db_login_user, $db_login_pwd;
    
    $db = new PDO($db_login_dbname, $db_login_user, $db_login_pwd);

    if(isset($_POST['check_email'])){
        $email = $_POST['check_email'];
        $query = $db->prepare('SELECT email FROM users WHERE email = ?');
        $query->execute(array($email));
        if($query->rowCount() == 0){
            echo json_encode(array('error' => 'email_success'));
        }else{
            echo json_encode(array('error' => 'email_fail', 'message' => 'Sorry this email is already exist'));
        }// end else
    }// end if
   
    wp_die();
}// end check_email


// === Register signup_submit into wp_ajax ===
add_action('wp_ajax_nopriv_signup_submit', 'signup_submit');

function signup_submit(){
    
    /******** TEST *******/
    // echo "check signup form";
    // echo $_POST['first_name'];
    
    if(isset($_GET['signup']) && $_GET['signup'] == 'true'){ 
    // if($_GET['signup'] == 'true'){                               /*TEST*/
    // if(isset($_POST['first_name'])){                             /*TEST*/
        
        /******** Assigning POST into variables *******/
        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
        /******** Create new user for users db *******/
        global $db_login_dbname, $db_login_user, $db_login_pwd;
        $db_users = new PDO($db_login_dbname, $db_login_user, $db_login_pwd);
    
        $query = $db_users->prepare("INSERT INTO users (username, first_name, last_name, email, password) VALUES (?,?,?,?,?)");
        $query->execute([$username, $first_name, $last_name, $email, $password]);
        
        /******** Create new guest for guest_profile db *******/
        global $db_guest_details_dbname, $db_guest_details_user, $db_guest_details_pwd;
        $db_guest_details = new PDO($db_guest_details_dbname, $db_guest_details_user, $db_guest_details_pwd);
        
        $query_guest_details = $db_guest_details->prepare("INSERT INTO guest_profile (username, first_name, last_name, email) VALUES (?,?,?,?)");
        $query_guest_details->execute([$username, $first_name, $last_name, $email]);
        
        
        if($query && $query_guest_details){
    //         $_SESSION['guest_username'] = $username;
            $_SESSION['annoucement'] = 'User successfully registered';
    //         echo json_encode(['error'=> 'success', 'msg' => 'success.php']);
            echo json_encode(['error'=> 'success', 'msg' => 'login']);
        }// end if
    
    // echo $_POST['first_name'];                                   /*TEST*/
    // echo $_POST['last_name'];                                    /*TEST*/
    // echo $_POST['username'];                                     /*TEST*/
    // echo $_POST['email'];                                        /*TEST*/
    // echo $_POST['password'];                                     /*TEST*/
    // echo $_POST['password_confirm'];                             /*TEST*/
    // echo json_encode(array('msg' => 'https://1touradventure.com'));            /*TEST*/
       
    }// end if
    
    wp_die();
    
}// end signup_submit



/****************db functions ********************/

$con = $con_global;


function row_count($result){

	return mysqli_num_rows($result);
 }


function escape($string){
    
    global $con;
    
    return mysqli_real_escape_string($con, $string);
    
}


function confirm($result){
    
    global $con;
    
    if(!$result){
        
        die("Query Failed". mysqli_error($con));
    }
    
}


function query($query){
    
    global $con;
    
    return mysqli_query($con,$query);
    
}



function fetch_array($result){
    
    global $con;
       
    return mysqli_fetch_array($result);
    
}



/****************helper functions ********************/

function clean($string) {

	return htmlentities($string);
}


function redirect($location){

	return header("Location: {$location}");
}


function set_message($message) {

	if(!empty($message)){

		$_SESSION['message'] = $message;

	}else {

		$message = "";
	}
}



function display_message(){	
	
	if(isset($_SESSION['message'])) {

		echo $_SESSION['message'];

		unset($_SESSION['message']);
	}
}



function token_generator(){

$token = $_SESSION['token'] =  md5(uniqid(mt_rand(), true));

return $token;

}


function validation_errors($error_message) {

$error_message = <<<DELIMITER

<div class="alert alert-danger alert-dismissible" role="alert">
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	<strong>Warning!</strong> $error_message
 </div>
DELIMITER;

return $error_message;
		
}


function email_exist($email){

	$sql = "SELECT id FROM users WHERE email = '$email'";

	$result = query($sql);

	if(row_count($result) == 1 ) {

		return true;

	} else {

		return false;

	}

}


function username_exist($username) {

	$sql = "SELECT id FROM users WHERE username = '$username'";

	$result = query($sql);

	if(row_count($result) == 1 ) {

		return true;

	} else {
		
		return false;
	}
}


function send_email($email, $subject, $msg, $headers){


return mail($email, $subject, $msg, $headers);

}





/**************** Validation functions ********************/

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

	} // post request 
	
} // function 



/****************Register user functions ********************/

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

} // function




/****************Activate user functions ********************/

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
} // function 
 


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

} // function 


// Use in new version login.php
function new_validate_user_login(){

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
					
						//redirect("https://1touradventure.com/guest-dashboard/");
						redirect("https://1touradventure.com/guest-profile/");
							
				} else {

				echo validation_errors("Your credentials are not correct");		

				}
			}
	}

} // function


/****************User login functions ********************/
// Helper function used by validate_user_login function
function login_user($email, $password, $remember) {
//      old version 
// 		$sql = "SELECT password, id, username FROM users WHERE email = '".escape($email)."' AND active = 1";
		
		$sql = "SELECT password, id, username FROM users WHERE email = '".escape($email)."' ";

		$result = query($sql);

		if(row_count($result) == 1) {

			$row = fetch_array($result);

			$db_password = $row['password'];
			
			$guest_username = $row['username'];
			
            // old version password hash
// 			if(md5($password) === $db_password) {


            // new version password hash
            if(password_verify($password, $db_password)) {
                
				// if($remember == "on") {

				// 	setcookie('email', $email, time() + 86400);

				// } 

				$_SESSION['email'] = $email;
				
				$_SESSION['guest_username'] = $guest_username;

				return true;

			} else {

				return false;
			}

			return true;

		} else {
			
			return false;
		}

	} // function
	
	
/**************** Logged In function use in all guest account page ********************/
// Function have not been used
function logged_in(){

	if(isset($_SESSION['email']) || isset($_COOKIE['email'])){
		
		return true;

	} else {

		return false;
	}

} // function




/**************** Recover Password function ********************/

function recover_password() {

	if($_SERVER['REQUEST_METHOD'] == "POST") {

		if(isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {

			$email = clean($_POST['email']);

			if(email_exist($email)) {

			$validation_code = md5($email . microtime());

			setcookie('temp_access_code', $validation_code, time() + 900, '/');

			$sql = "UPDATE users SET validation_code = '".escape($validation_code)."' WHERE email = '".escape($email)."'";
			$result = query($sql);


			$subject = "Please reset your password";
			$message =  " Here is your password reset code {$validation_code}
			Click here to reset your password https://1touradventure.com/code/?email=$email&code=$validation_code";

			$headers = "From: noreply@1touradventure.com";

			send_email($email, $subject, $message, $headers);

			set_message("<p class='bg-success text-center'>Please check your email or spam folder for a password reset code</p>");

			} else {
			
				echo validation_errors("This emails does not exist");
			}

		} else {

			redirect("index.php");

		}

		// token checks
 
		if(isset($_POST['cancel_submit'])) {

			redirect("https://1touradventure.com/login/");

		}

	} // post request

} // functions




/**************** Code  Validation ********************/

function validate_code () {

	if(isset($_COOKIE['temp_access_code'])) {

			if(!isset($_GET['email']) && !isset($_GET['code'])) {

				//redirect("index.php");
				set_message("<p class='bg-danger text-center'>global Get_email and code not set</p>");

			} else if (empty($_GET['email']) || empty($_GET['code'])) {

				//redirect("index.php");
				set_message("<p class='bg-danger text-center'>empty global Get_email and code</p>");			
				
			} else {

				if(isset($_POST['code'])) {

					$email = clean($_GET['email']);

					$validation_code = clean($_POST['code']);

					$sql = "SELECT id FROM users WHERE validation_code = '".escape($validation_code)."' AND email = '".escape($email)."'";
					$result = query($sql);

					if(row_count($result) == 1) {

						setcookie('temp_access_code', $validation_code, time() + 900, '/');

						redirect("https://1touradventure.com/reset/?email=$email&code=$validation_code");

					} else {

						echo validation_errors("Sorry wrong validation code");
					}		
				}
			}

			} else {

		set_message("<p class='bg-danger text-center'>Sorry your validation cookie was expired</p>");

		redirect("https://1touradventure.com/recover/");
	}		
}



/**************** Password Reset Function ********************/

function password_reset() {

	if(isset($_COOKIE['temp_access_code'])) {

		if(isset($_GET['email']) && isset($_GET['code'])) {
			
			if(isset($_COOKIE['temp_access_code'])) {

			if(isset($_SESSION['token']) && isset($_POST['token'])) {

				if($_POST['token'] === $_SESSION['token']) {

				if($_POST['password'] === $_POST['confirm_password'])  { 

					$updated_password = md5($_POST['password']);

					$sql = "UPDATE users SET password = '".escape($updated_password)."', validation_code = 0 WHERE email = '".escape($_GET['email'])."'";
					
					query($sql);

					set_message("<p class='bg-success text-center'>You passwords has been updated, please login</p>");

					redirect("https://1touradventure.com/login/");
						
					} else {

					echo validation_errors("Password fields don't match");

						}
				  }	
			} 

			}else {

		set_message("<p class='bg-danger text-center'>Sorry your time has expired</p>");

		//redirect("http://localhost/wordpress/recover/");

		}
		} 

	}else {

		set_message("<p class='bg-danger text-center'>Sorry your time has expired</p>");

		redirect("https://1touradventure.com/recover/");

		}
} // function



/**************** Log In & Log Out functions use by hook ********************/
// Helper function used by add_login_logout_link function
function logged_inn(){

	if(isset($_SESSION['email']) || isset($_COOKIE['email'])){
		
		return true;

	} else {

		return false;
	}

} // function

// Function have not been used
function logged_out(){

	session_destroy();

	if(isset($_COOKIE['email'])) {

	unset($_COOKIE['email']);

	}

} // function


/**************** Guest Account Log In & Log Out Menu ********************/
add_filter('wp_nav_menu_loginout_items', 'add_login_logout_link', 10, 2);

function add_login_logout_link($items, $args) { 
 
    if ( logged_inn() && $args->theme_location == 'login' ) {	

	//	$loginoutlink .= '<a href="https://1touradventure.com/logout/">Log out</a>';
		
		 $loginoutlink =   $_SESSION['guest_username'];
		
	//	$items .= '<li>'. $loginoutlink .'</li>'; 
		
		$items .= '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'. $loginoutlink .' <span class="caret"></span></a>
		
		<ul class="dropdown-menu">
            <li><a href="https://1touradventure.com/guest-profile/">My Profile</a></li>
            <li><a href="https://1touradventure.com/guest-my-booking/">My Booking</a></li>
            <li><a href="https://1touradventure.com/guest-wish-list/">Wish List</a></li>
            <li><a href="https://1touradventure.com/guest-booking-history/">Booking History</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="https://1touradventure.com/logout/">Log Out</a></li>
          </ul> </li>';
		
    }else{
	 
	 if($args->theme_location == 'login'){
	     
	 $loginoutlink .= '<a href="https://1touradventure.com/login/">Log in | Sign up</a>';
	 
	// $loginoutlink =   $_SESSION['admin-username'];
		
		$items .= '<li>'. $loginoutlink .'</li>';
		
	 //$items .= '<li><a href="https://1touradventure.com/logout/">'. $loginoutlink .'</a></li>'; 
		
 	} 
 }	
		return $items;

} // function


// Use in guest-profile.php, guest-wishlist.php, guest-my-booking.php, guest-booking-history.php, guest-dashboard.php
function check_logged_inn(){

	if(logged_inn()){
		
		return true;

	} else {

		redirect("https://1touradventure.com/login/");	
	}

} // function



/****************************************** Admin User login functions ***********************************************/

function validate_admin_user_login(){

	$errors = [];
	$min = 3;
	$max = 20;

	if($_SERVER['REQUEST_METHOD'] == "POST") {

		$email 		= clean($_POST['admin-email']);
		$password	= clean($_POST['admin-password']);

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

				if(login_admin_user($email, $password)) {
					
					redirect("https://1touradventure.com/admin/");
							
				} else {


				echo validation_errors("Your credentials are not correct");		

				}
			}
	}

} // function 



function login_admin_user($email, $password) {

		$sql = "SELECT password, id, username FROM admin_users WHERE email = '".escape($email)."' ";

		$result = query($sql);

		if(row_count($result) == 1) {

			$row = fetch_array($result);

			$db_password = $row['password'];
			
			$db_username = $row['username'];

			if($password === $db_password) {

				$_SESSION['admin-email'] = $email;
				
				$_SESSION['admin-username'] = $db_username;

				return true;

			} else {

				return false;
			}

			return true;

		} else {
			
			return false;
		}

	} // function
	
	
	

function admin_logged_in(){

	if(isset($_SESSION['admin-email']) ){
		
		return true;

	} else {

		return false;
	}

} // function



function check_admin_logged_in(){

	if(admin_logged_in()){
		
		return true;

	} else {

		redirect("https://1touradventure.com/admin-login/");	
	}

}	// functions


/****************************************** Contact Us functions ***********************************************/


function process_contact_us(){
	    
	if(isset($_POST['submit'])) {
       
		$name 		= clean($_POST['contact-name']);
		$email 		= clean($_POST['contact-email']);
		$subject 	= clean($_POST['contact-subject']);
		$message	= clean($_POST['contact-msg']);
		
		email_contact_us($name,$email,$subject,$message);
                
        set_message("<hr><p class='bg-success text-center'>Message Submitted. We will revert to you as soon as possible!</p>");

 	}

} // end function


function email_contact_us($name,$email,$contactSubject,$message){
    
        $subject    = "Contact Us";
        $email      = "leehwukkok@gmail.com";
		$headers    = "From: contact_us@1touradventure.com";
		
		$msg = "Name: $name, \nEmail: $email, \nSubject: $contactSubject, \nMessage: $message";

		send_email($email, $subject, $msg, $headers);
    
}// end function

?>