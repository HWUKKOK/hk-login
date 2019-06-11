/*
Modal.js is mainly used for WordPress Modal. As WordPress Modal have special scope to deal with jQuery after the WordPress load the scripts.

jQuery's focusout() function can't be properly used in WordPress Modal. Instead, use html onfocusout() function in php file as form event listener.

*/

// $ = jQuery.noConflict();

    // === Variable ===
    var modal_current_password  = "";
    var modal_new_password      = "";
    var modal_confirm_password  = "";
    var pwd_regExp = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{8,}$/;



    // === Current Password Validation ===
    function check_current_pwd(){
      
        var temp_current_password = $.trim($("#current_password_modal").val());
          
        if(temp_current_password.length == ""){
                $(".current-password-modal-error").html("Current password is required!");
                $("#current_password_modal").addClass("border-red");
                $("#current_password_modal").removeClass("border-green");
                modal_current_password = "";
        }else{
                $(".current-password-modal-error").html("");
                $("#current_password_modal").addClass("border-green");
                $("#current_password_modal").removeClass("border-red");
                
          $.ajax({
                    type: "POST",
                    url: admin_ajax.ajaxurl,
                    data: {
                        'action': 'check_current_pwd',
                        'password': temp_current_password
                    },
                    success: function(result){
                        // console.log(result);
                        var feedback = JSON.parse(result);
                        
                        if(feedback['error'] == 'success'){
                            $(".current-password-modal-error").html('<div class="text-success"><i class="fa fa-check-circle"></i> Password Correct!</div>');
                            $("#current_password_modal").addClass("border-green");
                            $("#current_password_modal").removeClass("border-red");
                            modal_current_password = temp_current_password;
                            
                        }else if(feedback['error'] == 'fail'){
                            $(".current-password-modal-error").html("Incorrect password!");
                            $("#current_password_modal").addClass("border-red");
                            $("#current_password_modal").removeClass("border-green");
                            modal_current_password = "";
                        }// end else 
                    }// success
                })// ajax       
          
        }// end else 
          
    }// check_current_pwd



    // === New Password Validation ===
    function check_new_pwd(){
        
        var temp_new_password = $.trim($("#new_password_modal").val());
        // alert(temp_password);

        if(temp_new_password.length == ""){
            $(".new-password-modal-error").html("Password is required!");
            $("#new_password_modal").addClass("border-red");
            $("#new_password_modal").removeClass("border-green");
            modal_new_password = "";
        }else if(pwd_regExp.test(temp_new_password)){
            $(".new-password-modal-error").html('<div class="text-success"><i class="fa fa-check-circle"></i> Your Password Is Strong!</div>');
            $("#new_password_modal").addClass("border-green");
            $("#new_password_modal").removeClass("border-red");
            modal_new_password = temp_new_password;
        }else{
            $(".new-password-modal-error").html("Password require at least 8 characters or longer. Combine upper and lowercase letters and numbers.");
            $("#new_password_modal").addClass("border-red");
            $("#new_password_modal").removeClass("border-green");
            modal_new_password = "";
        }// end else

    }// close password validation
    
 
    
    // === Confirm Change Password Validation ===
    // $("#confirm_password_modal").focusout(function(){
    function check_confirm_pwd(){
        var temp_confirm_password = $.trim($("#confirm_password_modal").val());

        if(temp_confirm_password.length == ""){
            $(".confirm-password-modal-error").html("Confirm Password is required!");
            $("#confirm_password_modal").addClass("border-red");
            $("#confirm_password_modal").removeClass("border-green");
            modal_confirm_password = "";
        
        }else if(temp_confirm_password != modal_new_password){
            $(".confirm-password-modal-error").html("Password is not matched.");
            $("#confirm_password_modal").addClass("border-red");
            $("#confirm_password_modal").removeClass("border-green");
            modal_confirm_password = "";
        
        }else{
            $(".confirm-password-modal-error").html('<div class="text-success"><i class="fa fa-check-circle"></i> Password Matched!</div>');
            $("#confirm_password_modal").addClass("border-green");
            $("#confirm_password_modal").removeClass("border-red");
            modal_confirm_password = temp_confirm_password;
        }// end else

    // })// close confirm password validation
    
    }// close password validation



    // === Submit Change Password ===
    function submit_change_pwd(){
        
        //========== Test Success ===========
        // alert(current_pwd + ' ' + new_pwd);
        // let pwd = $("#new_password").val();
        // console.log(pwd);
        
        if(modal_current_password.length == ""){
            $(".current-password-modal-error").html("Required current password!");
            $("#current_password_modal").addClass("border-red");
            $("#current_password_modal").removeClass("border-green");
        }
    
        if(modal_new_password.length == ""){
            $(".new-password-modal-error").html("Required new password!");
            $("#new_password_modal").addClass("border-red");
            $("#new_password_modal").removeClass("border-green");
        }
        
        if(modal_confirm_password.length == ""){
            $(".confirm-password-modal-error").html("Confirm Password is required!");
            $("#confirm_password_modal").addClass("border-red");
            $("#confirm_password_modal").removeClass("border-green");
        }
    
        if(modal_current_password.length != "" && modal_new_password.length != "" && modal_confirm_password.length != "" && modal_new_password === modal_confirm_password){
            // alert("Password Submited");
            $.ajax({
                    type: "POST",
                    url: admin_ajax.ajaxurl,
                    data: {
                        'action': 'submit_change_pwd',
                        'password': modal_new_password
                    },
                    success: function(result){
                        // alert(result);
                        var feedback = JSON.parse(result);
                        
                        alert(feedback['message']);
                        
                    }// success
                })// ajax          
        }// end if
            
    }// close submit_change_pwd



    // === Modal Close function ===
    function modal_close(){
        
        $("#current_password_modal").val("");
        $("#current_password_modal").removeClass("border-red");
        $("#current_password_modal").removeClass("border-green");
        $(".current-password-modal-error").html("");
        
        $("#new_password_modal").val("");
        $("#new_password_modal").removeClass("border-red");
        $("#new_password_modal").removeClass("border-green");
        $(".new-password-modal-error").html("");
        
        $("#confirm_password_modal").val("");
        $("#confirm_password_modal").removeClass("border-red");
        $("#confirm_password_modal").removeClass("border-green");
        $(".confirm-password-modal-error").html("");
        
    }// close modal_close



