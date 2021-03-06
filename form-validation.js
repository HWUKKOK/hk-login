$(document).ready(function(){

    // === Create Var ===
    var first_name = "";
    var last_name = "";
    var username = "";
    var email = "";
    var password = "";
    var password_confirm = "";

    // === Regular Expression ===
    var name_regExp= /^[a-z ]+$/i;
    var email_regExp = /^[a-z]+[0-9a-zA-Z_\.]*@[a-z_]+\.[a-z]+$/;
    var password_regExp = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{8,}$/;

    
    // === First Name Validation ===
    $("#first_name").focusout(function(){
        var temp_first_name = $.trim($("#first_name").val());
        if(temp_first_name.length == ""){
            $(".first-name-error").html("First name is required!");
            $("#first_name").addClass("border-red");
            $("#first_name").removeClass("border-green");
            first_name = ""; 
            // alert("Empty Name " + name); }
        }else if(name_regExp.test(temp_first_name)){
            $(".first-name-error").html("");
            $("#first_name").addClass("border-green");
            $("#first_name").removeClass("border-red");
            first_name = temp_first_name;
            //alert("Success " + name);
        }else{
            $(".first-name-error").html("Integer is not allowed!");
            $("#first_name").addClass("border-red");
            $("#first_name").removeClass("border-green");
            first_name = "";
            //alert("Integer is needed " + name);
        }
    })// close first name validation
    
    
    // === Last Name Validation ===
    $("#last_name").focusout(function(){
        var temp_last_name = $.trim($("#last_name").val());
        if(temp_last_name.length == ""){
            $(".last-name-error").html("Last name is required!");
            $("#last_name").addClass("border-red");
            $("#last_name").removeClass("border-green");
            last_name = ""; 
            // alert("Empty Name " + name); }
        }else if(name_regExp.test(temp_last_name)){
            $(".last-name-error").html("");
            $("#last_name").addClass("border-green");
            $("#last_name").removeClass("border-red");
            last_name = temp_last_name;
            //alert("Success " + name);
        }else{
            $(".last-name-error").html("Integer is not allowed!");
            $("#last_name").addClass("border-red");
            $("#last_name").removeClass("border-green");
            last_name = "";
            //alert("Integer is needed " + name);
        }
    })// close last name validation
    
    
    // === Userame Validation ===
    $("#username").focusout(function(){
        var temp_username = $.trim($("#username").val());
        if(temp_username.length == ""){
            $(".username-error").html("Username is required!");
            $("#username").addClass("border-red");
            $("#username").removeClass("border-green");
            username = ""; 
            // alert("Empty Name " + name); }
        }else if(name_regExp.test(temp_username)){
            $(".username-error").html("");
            $("#username").addClass("border-green");
            $("#username").removeClass("border-red");
            username = temp_username;
            //alert("Success " + name);
        }else{
            $(".username-error").html("Integer is not allowed!");
            $("#username").addClass("border-red");
            $("#username").removeClass("border-green");
            username = "";
            //alert("Integer is needed " + name);
        }
    })// close username validation
    
    
    // === Email Validation ===
    $("#register_email").on('focusout',function(e){
        e.preventDefault();
        
        // alert("Test");
        var temp_email = $.trim($("#register_email").val());

        if(temp_email.length == ""){
            $(".email-error").html("Email is required!");
            $("#register_email").addClass("border-red");
            $("#register_email").removeClass("border-green");
            email = "";

        }else if(email_regExp.test(temp_email)){
            $.ajax({
                type: 'post',
                // dataType: 'JSON',
                url: admin_ajax.ajaxurl,
                data: {
                    'action': 'check_email_availability',
                    'temp_email' : temp_email
                },// data
                beforeSend: function(){
                    $(".email-error").html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>')
                },// before send
                success : function(result){
                    var feedback = JSON.parse(result);
                    // console.log(result);
                    
                    if(feedback['error'] == 'email_success'){
                            $(".email-error").html('<div class="text-success"><i class="fa fa-check-circle"></i>  Available</div>');
                    }// end if
                    
                    setTimeout(function(){
                        if(feedback['error'] == 'email_success'){
                            $(".email-error").html('<div class="text-success"><i class="fa fa-check-circle"></i>  Available</div>');
                            $("#register_email").addClass("border-green");
                            $("#register_email").removeClass("border-red");
                            email = temp_email;
                        }else if(feedback['error'] == 'email_fail'){
                            $(".email-error").html("Sorry this email already exist! Please try other email address.");
                            $("#register_email").addClass("border-red");
                            $("#register_email").removeClass("border-green");
                            email = "";
                        }// end else if
                    }, 1000);//end setTimeout
                }// success
            });// end ajax
            
        }else{
            $(".email-error").html("Invalid Email Format!");
            $("#register_email").addClass("border-red");
            $("#register_email").removeClass("border-green");
            email = "";
        }// end else

    }); //close email validation
    
    
    
    // === Password Validation ===
    $("#password").focusout(function(){
        var temp_password = $.trim($("#password").val());

        if(temp_password.length == ""){
            $(".password-error").html("Password is required!");
            $("#password").addClass("border-red");
            $("#password").removeClass("border-green");
            password = "";
        }else if(password_regExp.test(temp_password)){
            $(".password-error").html('<div class="text-success"><i class="fa fa-check-circle"></i> Your Password Is Strong!</div>');
            $("#password").addClass("border-green");
            $("#password").removeClass("border-red");
            password = temp_password;
        }else{
            $(".password-error").html("Password require at least 8 characters or longer. Combine upper and lowercase letters and numbers.");
            $("#password").addClass("border-red");
            $("#password").removeClass("border-green");
            password = "";
        }

    })// close password validation
    
    
    
    // === Confirm Password Validation ===
    $("#confirm_password").focusout(function(){
        var temp_confirm = $.trim($("#confirm_password").val());

        if(temp_confirm.length == ""){
            $(".confirm-error").html("Confirm Password is required!");
            $("#confirm_password").addClass("border-red");
            $("#confirm_password").removeClass("border-green");
            password_confirm = "";
        
        }else if(temp_confirm != password){
            $(".confirm-error").html("Password is not matched.");
            $("#confirm_password").addClass("border-red");
            $("#confirm_password").removeClass("border-green");
            password_confirm = "";
        
        }else{
            $(".confirm-error").html('<div class="text-success"><i class="fa fa-check-circle"></i> Password Matched!</div>');
            $("#confirm_password").addClass("border-green");
            $("#confirm_password").removeClass("border-red");
            password_confirm = temp_confirm;
        }

    })// close confirm password validation
    
    
    
    // === Submit the User Registration Form ===
    $("#submit").click(function(){

        // if(first_name.length == ""){
        //     $(".first-name-error").html("First name is required!");
        //     $("#first_name").addClass("border-red");
        //     $("#first_name").removeClass("border-green");
        //     first_name = "";    
        // }
        
        // if(last_name.length == ""){
        //     $(".last-name-error").html("Last name is required!");
        //     $("#last_name").addClass("border-red");
        //     $("#last_name").removeClass("border-green");
        //     last_name = "";    
        // }
        
        if(username.length == ""){
            $(".username-error").html("Username is required!");
            $("#username").addClass("border-red");
            $("#username").removeClass("border-green");
            username = "";    
        }

        if(email.length == ""){
            $(".email-error").html("Email is required!");
            $("#register_email").addClass("border-red");
            $("#register_email").removeClass("border-green");
            email = "";    
        }

        if(password.length == ""){
            $(".password-error").html("Password is required!");
            $("#password").addClass("border-red");
            $("#password").removeClass("border-green");
            password = "";    
        }

        if(password_confirm.length == ""){
            $(".confirm-error").html("Confirm Password is required!");
            $("#confirm_password").addClass("border-red");
            $("#confirm_password").removeClass("border-green");
            password_confirm = "";    
        }

        if(username.length != "" && email.length != "" && password.length != "" && password_confirm != ""){
//      if(first_name.length != "" && last_name.length != "" && username.length != "" && email.length != "" && password.length != "" && password_confirm != ""){

        // if(first_name.length !== ""){                                   /*TEST*/
            $.ajax({
                type: "POST",
                url: admin_ajax.ajaxurl,
                data: {
                    'action': 'signup_submit',
                    // 'first_name' : first_name,
                    // 'last_name' : last_name,
                    'username' : username,
                    'email' : email,
                    'password' : password,
                    'password_confirm' : password_confirm
                },
                // data: $("#signup_submit").serialize(),
                // dataType: 'JSON',
                beforeSend: function(){
                    $('.show-progress').html("Progress!");
                },
                success: function(result){
                // console.log(result);
                var feedback = JSON.parse(result);
                
        //                 setTimeout(function(){
                            if(feedback['error'] == 'success'){
                                location = 'https://1touradventure.com/'+ feedback.msg;
                            }
        //                 }, 3000)
                    
                }//success
            })// ajax            
        }// end if

    })// close submit form
    
    
// === Password Recovery - Email Validation ===    
$("#email_recover_password").on('focusout',function(e){
        e.preventDefault();
        
        var temp_email = $.trim($("#email_recover_password").val());

        if(temp_email.length == ""){
            $(".email-recover-pwd-error").html("Email is required!");
            $("#email_recover_password").addClass("border-red");
            $("#email_recover_password").removeClass("border-green");
            email = "";
        }
        
        if(!email_regExp.test(temp_email)){
            $(".email-recover-pwd-error").html("Invalid Email Format!");
            $("#email_recover_password").addClass("border-red");
            $("#email_recover_password").removeClass("border-green");
            email = "";
            
        }else{
            $(".email-recover-pwd-error").html("");
            $("#email_recover_password").addClass("border-green");
            $("#email_recover_password").removeClass("border-red"); 
        }

    }); //close email validation
    
    
    
// === Password Recovery Form Submission ===
    $("#password_recover_submit").click(function(){
        
        var email_recover_pwd = $.trim($("#email_recover_password").val());
        
        var token = $.trim($("#token").val());
        
        if(email_recover_pwd.length === ""){
            $(".email-recover-pwd-error").html("Email is required!");
            $("#email_recover_password").addClass("border-red");
            $("#email_recover_password").removeClass("border-green");
            email = "";    
            
        }else if(email_regExp.test(email_recover_pwd)){
           
            $.ajax({
                type: "POST",
                url: admin_ajax.ajaxurl,
                data: {
                    'action': 'recover_pwd',
                    'email' : email_recover_pwd,
                    'token' : token
                },
                // beforeSend: function(){
                //     alert("test");
                // },
                success: function(result){
                    
                var feedback = JSON.parse(result);
               
                    if(feedback['error'] == 'success'){
                        location = 'https://1touradventure.com/'+ feedback.msg;
                        
                    }else if(feedback['error'] == 'fail'){
                        
                        $("#annoucement").html(`<div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Sorry, this emails does not exist!</div>`);
                        $("#email_recover_password").addClass("border-red");
                        $("#email_recover_password").removeClass("border-green");
                        email = "";
                        
                    }// else if
                    
                }//success
            });// ajax
            
        }else{
            $(".email-recover-pwd-error").html("Invalid Email Format!");
            $("#email_recover_password").addClass("border-red");
            $("#email_recover_password").removeClass("border-green");
            email = "";
            
        }// end else

    })// close submit form
    





// === Password Change - Guest Profile ===
function change_password(current_password, confirm_password){
    var current_password = $.trim(current_password);
    var confirm_password = $.trim(confirm_password);

    if(current_password.length == ""){
        $(".current-password-error").html("Required current password!");
        $("#current_password").css("border-color","red");
    }else{
        $(".current-password-error").html();
        $("#current_password").css("border-color","green");
    }

    if(confirm_password.length == ""){
        $(".confirm-error").html("Required new password!");
        $("#confirm_password").css("border-color","red");
    }else{
        $(".confirm-error").html();
        $("#confirm_password").css("border-color","green");
    }

    // if(current_password.length != "" && new_password.length != ""){
    //     $.ajax({
    //         type: 'POST',
    //         url: 'parts/ajax.php?password=true',
    //         data: {'current_password': current_password, 'new_password': new_password},
    //         dataType: 'JSON',
    //         success: function(feedback){
    //             // alert(feedback);
    //             if(feedback['error'] == 'success'){
    //                 location = "index.php";
    //             }else if(feedback['error'] == 'patern'){
    //                 $('.new-password-error').html(feedback['msg']);
    //                 $('#new_password').css('border-color', 'red');
    //             }else if(feedback['error'] == 'current_password_wrong'){
    //                 $('.current-password-error').html(feedback['msg']);
    //                 $('#current_password').css('border-color', 'red');
    //             }
    //         }//success
    //     })// ajax
    // }// end if
}
    

// === Login Email Validation ===    

$("#login_email").focusout(function(){
    
        var temp_login_email = $.trim($("#login_email").val());
        
        if(temp_login_email.length == ""){
            $(".login-email-error").html("Email is required!");
            $("#login_email").addClass("border-red");
            $("#login_email").removeClass("border-green");
            email = "";
            
        }else if(!email_regExp.test(temp_login_email)){
            $(".login-email-error").html("Invalid Email Format!");
            $("#login-email").addClass("border-red");
            $("#login-email").removeClass("border-green");
            email = "";
            
        }else{
            $(".login-email-error").html("");
            $("#login_email").addClass("border-green");
            $("#login_email").removeClass("border-red");
            email = temp_login_email;
        }

    }); //close email validation 


// === Login Password Validation ===
    $("#login-password").focusout(function(){
        var temp_login_password = $.trim($("#login-password").val());

        if(temp_login_password.length == ""){
            $(".login-password-error").html("Password is required!");
            $("#login-password").addClass("border-red");
            $("#login-password").removeClass("border-green");
            password = "";
        }else {
            $(".login-password-error").html("");
            $("#login-password").addClass("border-green");
            $("#login-password").removeClass("border-red");
            password = temp_login_password;
        }

    })// close password validation
    
    
    
// === Login Form Submission ===
    $("#login-submit").click(function(){
    
        if(email.length == ""){
            $(".email-error").html("Email is required!");
            $("#register_email").addClass("border-red");
            $("#register_email").removeClass("border-green");
            email = "";    
        }

        if(password.length == ""){
            $(".password-error").html("Password is required!");
            $("#password").addClass("border-red");
            $("#password").removeClass("border-green");
            password = "";    
        }

        if(email.length != "" && password.length != ""){

            $.ajax({
                type: "POST",
                url: admin_ajax.ajaxurl,
                data: {
                    'action': 'guest_login',
                    'email' : email,
                    'password' : password
                },
                success: function(result){
                    // console.log(result);
                    var feedback = JSON.parse(result);
                    
                    if(feedback['error'] == 'success'){
                        location = 'https://1touradventure.com/'+ feedback.msg;
                        
                    }else if(feedback['error'] == 'fail'){
                        
                        location = 'https://1touradventure.com/'+ feedback.msg;
                    
                    }// end else 
                }// success
            })// ajax            
        }// end if    
        
    })// close login submit form
    

})// close document ready

