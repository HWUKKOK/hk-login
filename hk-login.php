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
include("deprecated-functions.php");


/**************** db functions ********************/

$con = $con_global;

function row_count($result){

	return mysqli_num_rows($result);
	
 }// end function


function escape($string){
    
    global $con;
    
    return mysqli_real_escape_string($con, $string);
    
}// end function


function confirm($result){
    
    global $con;
    
    if(!$result){
        
        die("Query Failed". mysqli_error($con));
    }
    
}// end function


function query($query){
    
    global $con;
    
    return mysqli_query($con,$query);
    
}// end function


function fetch_array($result){
    
    global $con;
       
    return mysqli_fetch_array($result);
    
}// end function



/**************** helper functions ********************/

function clean($string) {

	return htmlentities($string);
	
}// end function


function redirect($location){

	return header("Location: {$location}");
	
}// end function


function set_message($message) {

	if(!empty($message)){
		$_SESSION['message'] = $message;

	}else {
		$message = "";
		
	}// end else
}// end function


function display_message(){	
	
	if(isset($_SESSION['message'])) {
		echo $_SESSION['message'];

		unset($_SESSION['message']);
		
	}// end if
}// end function


// Use for password recovery in recover.php
function token_generator(){

    $token = $_SESSION['token'] =  md5(uniqid(mt_rand(), true));

    return $token;

}// end function


function validation_errors($error_message) {

$error_message = <<<DELIMITER

<div class="alert alert-danger alert-dismissible" role="alert">
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  	<strong>Warning!</strong> $error_message
 </div>
DELIMITER;

return $error_message;
		
}// end function


function email_exist($email){

	$sql = "SELECT id FROM users WHERE email = '$email'";
	$result = query($sql);

	if(row_count($result) == 1 ) {
		return true;

	} else {
		return false;
		
	}// end else
}// end function


function username_exist($username) {

	$sql = "SELECT id FROM users WHERE username = '$username'";
	$result = query($sql);

	if(row_count($result) == 1 ) {
		return true;

	} else {
		return false;
		
	}// end else
}// end function


function send_email($email, $subject, $msg, $headers){

    return mail($email, $subject, $msg, $headers);

}// end function



/**************** Form Validation ********************/

// === Enqueue form-validation scripts into register page ===
add_action( 'wp_enqueue_scripts', 'form_validation' );

function form_validation() {

	wp_enqueue_script( 'form-validation', plugin_dir_url( __FILE__ ) . 'form-validation.js', array(), '123', true );
	
	wp_localize_script(
	    'form-validation',  // ajax script handler
	    'admin_ajax',       // ajax object
	    array('ajaxurl' => admin_url('admin-ajax.php?signup=true')) // ajax url
	    );
}// end function


// === Register recover_pwd into wp_ajax ===
add_action('wp_ajax_nopriv_recover_pwd', 'recover_pwd');

function recover_pwd(){
    
    /******** TEST *******/
    // echo "Check password";                                   /*TEST*/
    // echo $_POST['email'];                                    /*TEST*/
    // echo $_POST['token'];                                    /*TEST*/
    
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
			$message =  "Here is your password reset code = {$validation_code} \nClick the link below to reset your password: \nhttps://1touradventure.com/code/?email=$email&code=$validation_code";
			$headers = "From: password_reset@1touradventure.com";

			send_email($email, $subject, $message, $headers);

			$_SESSION['annoucement'] = '<i class="fa fa-check-circle"></i> Please check your email or spam folder for a password reset code';
			
			echo json_encode(['error'=> 'success', 'msg' => 'login']);

			} else {
			    echo json_encode(['error'=> 'fail', 'msg' => '']);

			}// end else    

    }// end if
   
    wp_die();
}// end function



// === Register check_email function into wp_ajax ===
add_action('wp_ajax_nopriv_check_email_availability', 'check_email_availability');

function check_email_availability(){
    
    /******** TEST *******/
    // echo "Check Email Now!";                                     /*TEST*/
    // echo json_encode(array('error' => 'email_success'));         /*TEST*/
    // echo json_encode(array('error' => 'email_fail'));            /*TEST*/
    // echo $_POST['check_email'];                                  /*TEST*/
    
    global $db_login_dbname, $db_user, $db_pwd;
    
    $db = new PDO($db_login_dbname, $db_user, $db_pwd);

    if(isset($_POST['temp_email'])){
        $email = $_POST['temp_email'];
        $query = $db->prepare('SELECT email FROM users WHERE email = ?');
        $query->execute(array($email));
        
        if($query->rowCount() == 0){
            echo json_encode(array('error' => 'email_success'));
            
        }else{
            echo json_encode(array('error' => 'email_fail', 'message' => 'Sorry this email is already exist'));
        
        }// end else
    }// end if
   
    wp_die();
}// end function


// === Register signup_submit function into wp_ajax ===
add_action('wp_ajax_nopriv_signup_submit', 'signup_submit');

function signup_submit(){
    
    /******** TEST *******/
    // echo "check signup form";                                    /*TEST*/
    // echo $_POST['first_name'];                                   /*TEST*/
    
    if(isset($_GET['signup']) && $_GET['signup'] == 'true'){ 
    // if($_GET['signup'] == 'true'){                               /*TEST*/
    // if(isset($_POST['first_name'])){                             /*TEST*/
    
        
        /******** Assigning POST into variables *******/
        $username = $_POST['username'];
        // $first_name = $_POST['first_name'];
        // $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    
        /******** Create new user for users db *******/
        global $db_login_dbname, $db_user, $db_pwd;
        $db_users = new PDO($db_login_dbname, $db_user, $db_pwd);
        
        $query = $db_users->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
        $query->execute([$username, $email, $password]);
        
        // --- Query with full guest details --- 
        // $query = $db_users->prepare("INSERT INTO users (username, first_name, last_name, email, password) VALUES (?,?,?,?,?)");
        // $query->execute([$username, $first_name, $last_name, $email, $password]);
        
        
        /******** Create new guest for guest_profile db *******/
        global $db_guest_details_dbname, $db_user, $db_pwd;
        $db_guest_details = new PDO($db_guest_details_dbname, $db_user, $db_pwd);
        
        $query_guest_details = $db_guest_details->prepare("INSERT INTO guest_profile (username, email) VALUES (?,?)");
        $query_guest_details->execute([$username, $email]);
        
        // --- Query with full guest details ---
        // $query_guest_details = $db_guest_details->prepare("INSERT INTO guest_profile (username, first_name, last_name, email) VALUES (?,?,?,?)");
        // $query_guest_details->execute([$username, $first_name, $last_name, $email]);
        
        if($query && $query_guest_details){
    //         $_SESSION['guest_username'] = $username;
            $_SESSION['annoucement'] = 'You are successfully registered';
    //         echo json_encode(['error'=> 'success', 'msg' => 'success.php']);
            echo json_encode(['error'=> 'success', 'msg' => 'login']);
        }// end if
    
    // echo $_POST['first_name'];                                       /*TEST*/
    // echo $_POST['last_name'];                                        /*TEST*/
    // echo $_POST['username'];                                         /*TEST*/
    // echo $_POST['email'];                                            /*TEST*/
    // echo $_POST['password'];                                         /*TEST*/
    // echo $_POST['password_confirm'];                                 /*TEST*/
    // echo json_encode(array('msg' => 'https://1touradventure.com'));  /*TEST*/
       
    }// end if
    
    wp_die();
    
}// end function


// === Register guest_login function into wp_ajax ===
add_action('wp_ajax_nopriv_guest_login', 'guest_login');

function guest_login(){
    
    /******** TEST *******/
    // echo $_POST['email'];                                       /*TEST*/
    // echo $_POST['password'];                                    /*TEST*/

    $email      = clean($_POST['email']);
    $password   = clean($_POST['password']);
     
        $sql = "SELECT password, id, username FROM users WHERE email = '".escape($email)."' ";
		$result = query($sql);

        // check email exist
		if(row_count($result) == 1) {

			$row = fetch_array($result);

			$db_password    = $row['password'];    
			$guest_username = $row['username'];

            // check password valid
            if(password_verify($password, $db_password)) {
			
			// success login and set SESSION for guest's email and username
			$_SESSION['email'] = $email;
			$_SESSION['guest_username'] = $guest_username;
			
	        echo json_encode(['error'=> 'success', 'msg' => 'guest-profile']);
	        
            } else {
                $_SESSION['annoucement'] = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>Sorry, your password is incorrect!</div>';
                            
    	        echo json_encode(['error'=> 'fail', 'msg' => 'login']);
    	        
            }// end else

	    } else {
    	    $_SESSION['annoucement'] = '<div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>Sorry, your email is incorrect!</div>';
                            
    	    echo json_encode(['error'=> 'fail', 'msg' => 'login']);

	}// end else    
   
    wp_die();
}// end function


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

		}// end if
		
		if(empty($password)) {
			$errors[] = "Password field cannot be empty";

		}// end if

		if(!empty($errors)) {
			foreach ($errors as $error) {
			echo validation_errors($error);
				
			    }// end foreach

		} else {

			if(login_user($email, $password, $remember)) {
					//redirect("https://1touradventure.com/guest-dashboard/");
					redirect("https://1touradventure.com/guest-profile/");
						
			} else {
			echo validation_errors("Your credentials are not correct");		

			}// end else
		}// end else
	}// end if

}// end function


/**************** User login functions ********************/

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

                // Remove Remember's feature                
				// if($remember == "on") {
				// 	setcookie('email', $email, time() + 86400);
				// } 

				$_SESSION['email'] = $email;
				$_SESSION['guest_username'] = $guest_username;

				return true;

			} else {
				return false;
			
			}// password_verify fail
			return true;

		} else {
			return false;
			
		}// row_count fail

	}// end function
	


/**************** Code  Validation ********************/

// Used in code.php
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
						
					}// end else		
				}// end else
			}// end else

			} else {
        		set_message("<p class='bg-danger text-center'>Sorry your validation cookie was expired</p>");
        
        		redirect("https://1touradventure.com/recover/");
		
	}// end else		
}// end function



/**************** Password Reset Function ********************/

// Used in reset.php
function password_reset() {

    // check temp_access_code cookie
	if(isset($_COOKIE['temp_access_code'])) {

        // check email & code 
		if(isset($_GET['email']) && isset($_GET['code'])) {
			
			// check temp_access_code cookie
			if(isset($_COOKIE['temp_access_code'])) {

            // check SESSION and POST token are set
			if(isset($_SESSION['token']) && isset($_POST['token'])) {

                // check POST & SESSION token to be the same
				if($_POST['token'] === $_SESSION['token']) {

                // check password with confirm password
				if($_POST['password'] === $_POST['confirm_password'])  { 

                //--- deprecated ---
				// $updated_password = md5($_POST['password']);
				
				    // Update password into db
				    $updated_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
					$sql = "UPDATE users SET password = '".escape($updated_password)."', validation_code = 0 WHERE email = '".escape($_GET['email'])."'";
					query($sql);

					set_message("<p class='bg-success text-center'>Your passwords has been updated, you may login now!</p>");

					redirect("https://1touradventure.com/login/");
						
					} else {
					    echo validation_errors("Sorry, the Password fields do not match");

						}// end else
				  }// end else	
			}// end else 

			}else {
		          set_message("<p class='bg-danger text-center'>Sorry your time has expired</p>");

		    }// end else
		}// end if  

	}else {
		set_message("<p class='bg-danger text-center'>Sorry your time has expired</p>");

		redirect("https://1touradventure.com/recover/");

		}// end else
		
}// end function



/**************** Log In & Log Out functions use by hook ********************/

// Helper function used by add_login_logout_link function
function logged_inn(){

	if(isset($_SESSION['email']) || isset($_COOKIE['email'])){
		return true;

	} else {
		return false;
		
	}// end else

}// end function


/**************** Guest Account Log In & Log Out Menu ********************/

// === Add filter into wp_nav_menu_loginout_items ===
add_filter('wp_nav_menu_loginout_items', 'add_login_logout_link', 10, 2);

function add_login_logout_link($items, $args) { 
 
    if ( logged_inn() && $args->theme_location == 'login' ) {	

	//	$loginoutlink .= '<a href="https://1touradventure.com/logout/">Log out</a>';
		
		$loginoutlink =   $_SESSION['guest_username'];
		
	//	$items .= '<li>'. $loginoutlink .'</li>'; 
		
		$items .= '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" 
		            role="button" aria-haspopup="true" aria-expanded="false">'. $loginoutlink .' <span class="caret"></span></a>
		
            		  <ul class="dropdown-menu">
                        <li><a href="https://1touradventure.com/guest-profile/">My Profile</a></li>
                        <li><a href="https://1touradventure.com/guest-my-booking/">My Booking</a></li>
                        <li><a href="https://1touradventure.com/guest-wish-list/">Wish List</a></li>
                        <li><a href="https://1touradventure.com/guest-booking-history/">Booking History</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="https://1touradventure.com/logout/">Log Out</a></li>
                      </ul> 
                  
                  </li>';
    }else{
	 
	 if($args->theme_location == 'login'){
	     
	    $loginoutlink .= '<a href="https://1touradventure.com/login/">Log in | Sign up</a>';
	 
	// $loginoutlink =   $_SESSION['admin-username'];
		
		$items .= '<li>'. $loginoutlink .'</li>';
		
	 //$items .= '<li><a href="https://1touradventure.com/logout/">'. $loginoutlink .'</a></li>'; 
		
 	}// end if 
 }// end else	
		return $items;

}// end function


// Use in guest-profile.php, guest-wishlist.php, guest-my-booking.php, guest-booking-history.php, guest-dashboard.php
function check_logged_inn(){

	if(logged_inn()){
		return true;

	} else {
		redirect("https://1touradventure.com/login/");
		
	}// end else

}// end function



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

		}// end if
		
		if(empty($password)) {
			$errors[] = "Password field cannot be empty";

		}// end if

		if(!empty($errors)) {

				foreach ($errors as $error) {
				echo validation_errors($error);
				
				}// end foreach

			} else {

				if(login_admin_user($email, $password)) {
					redirect("https://1touradventure.com/admin/");
							
				} else {
				echo validation_errors("Your credentials are not correct");		

				}// end else
			}// end else
	}// end if

}// end function 


function login_admin_user($email, $password) {

		$sql = "SELECT password, id, username FROM admin_users WHERE email = '".escape($email)."' ";
		$result = query($sql);

		if(row_count($result) == 1) {

			$row = fetch_array($result);

			$db_password = $row['password'];
			$db_username = $row['username'];

			if($password === $db_password) {

				$_SESSION['admin-email']    = $email;
				$_SESSION['admin-username'] = $db_username;

				return true;

			} else {
				return false;
			
			}// end else
			    return true;

		} else {
			return false;
			
		}// end else

	}// end function
	

function admin_logged_in(){

	if(isset($_SESSION['admin-email']) ){
        return true;

	} else {
		return false;
		
	}// end else

}// end function


function check_admin_logged_in(){

	if(admin_logged_in()){
		return true;

	} else {
		redirect("https://1touradventure.com/admin-login/");
		
	}// end else

}// end functions


/****************************************** Contact Us functions ***********************************************/

function process_contact_us(){
	    
	if(isset($_POST['submit'])){
       
		$name 		= clean($_POST['contact-name']);
		$email 		= clean($_POST['contact-email']);
		$subject 	= clean($_POST['contact-subject']);
		$message	= clean($_POST['contact-msg']);
		
		email_contact_us($name,$email,$subject,$message);
                
        set_message("<hr><p class='bg-success text-center'>Message Submitted. We will revert to you as soon as possible!</p>");

 	}// end if

}// end function


function email_contact_us($name,$email,$contactSubject,$message){
    
        $subject    = "Contact Us";
        $email      = "leehwukkok@gmail.com";
		$headers    = "From: contact_us@1touradventure.com";
		
		$msg = "Name: $name, \nEmail: $email, \nSubject: $contactSubject, \nMessage: $message";

		send_email($email, $subject, $msg, $headers);
    
}// end function

?>