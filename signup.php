<?php

function check_email(){


    echo json_encode(array('error' => 'email_success'));
    
    //  GLOBAL $db;

    //  if(isset($_POST['check_email'])){
    //      $email = $_POST['check_email'];
    //      $query = $db->prepare('SELECT email FROM users WHERE email = ?');
    //      $query->execute(array($email));
    //      if($query->rowCount() == 0){
    //          echo json_encode(array('error' => 'email_success'));
    //      }else{
    //          echo json_encode(array('error' => 'email_fail', 'message' => 'Sorry this email is already exist'));
    //      }
    //  }
}

check_email();






?>