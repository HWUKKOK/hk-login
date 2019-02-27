$ = jQuery.noConflict();

$(document).ready(function(){
    
    // var name = "";
    // var email = "";
    // var password = "";
    // var password_confirm = "";

    // var name_regExp= /^[a-z ]+$/i;
    // var email_regExp = /^[a-z]+[0-9a-zA-Z_\.]*@[a-z_]+\.[a-z]+$/;
    // var password_regExp = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{8,}$/;
    
    // // === Name Validation ===
    // $("#first_name").focusout(function(){
    //     var temp_name = $.trim($("#first_name").val());
    //     if(temp_name.length == ""){
    //         $(".name-error").html("Name is required!");
    //         $("#first_name").addClass("border-red");
    //         $("#first_name").removeClass("border-green");
    //         name = ""; 
    //         // alert("Empty Name " + name); }
    //     }else if(name_regExp.test(temp_name)){
    //         $(".name-error").html("");
    //         $("#first_name").addClass("border-green");
    //         $("#first_name").removeClass("border-red");
    //         name = temp_name;
    //         //alert("Success " + name);
    //     }else{
    //         $(".name-error").html("Integer is not allowed!");
    //         $("#first_name").addClass("border-red");
    //         $("#first_name").removeClass("border-green");
    //         name = "";
    //         //alert("Integer is needed " + name);
    //     }
    // })// close name validation
    
    
    // === Email Validation ===
    // $("#register_email").on('focusout',function(e){
    //     e.preventDefault();
        
    //     // alert("Test");
    //     var temp_email = $.trim($("#register_email").val());

    //     if(temp_email.length == ""){
    //         $(".email-error").html("Email is required!");
    //         $("#register_email").addClass("border-red");
    //         $("#register_email").removeClass("border-green");
    //         email = "";

    //     }else if(email_regExp.test(temp_email)){
    //         $.ajax({
    //             type: 'post',
    //             // dataType: 'JSON',
    //             beforeSend: function(){
    //                 $(".email-error").html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>')
    //             },
                
    //             data: {
    //                 'action': 'check_email',
    //                 'check_email' : temp_email
    //             },
    //             url: admin_ajax.ajaxurl,
    //             success : function(result){
    //                 var feedback = JSON.parse(result);
    //                 // console.log(result);
                    
    //                 if(feedback['error'] == 'email_success'){
    //                         $(".email-error").html('<div class="text-success"><i class="fa fa-check-circle"></i>  Available</div>');
    //                 }
                    
    //                 setTimeout(function(){
    //                     if(feedback['error'] == 'email_success'){
    //                         $(".email-error").html('<div class="text-success"><i class="fa fa-check-circle"></i>  Available</div>');
    //                         $("#register_email").addClass("border-green");
    //                         $("#register_email").removeClass("border-red");
    //                         email = temp_email;
    //                     }else if(feedback['error'] == 'email_fail'){
    //                         $(".email-error").html("Sorry this email already exist! Please try other email address.");
    //                         $("#register_email").addClass("border-red");
    //                         $("#register_email").removeClass("border-green");
    //                         email = "";
    //                     }
    //                 }, 1000);
                    
    //             }
    //         });
    //     }else{
    //         $(".email-error").html("Invalid Email Format!");
    //         $("#register_email").addClass("border-red");
    //         $("#register_email").removeClass("border-green");
    //         email = "";
    //     }

    // }); //close email validation
});