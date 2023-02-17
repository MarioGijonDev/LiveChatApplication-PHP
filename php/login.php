<?php

require_once './DBConnection.php';

session_start();

$db = new DBConnection();

// Get post form values
$email =  htmlentities($_POST['email']) ?? '';
$password =  htmlentities($_POST['password']) ?? '';

// Check field isn't emptys
if(!empty($email) &&!empty($password)){

  // Check valid email format
  if(filter_var($email, FILTER_VALIDATE_EMAIL)){

    // Check email exists in database
    if($db->checkEmailExists($email)){

      $userData = [
        'email' => $email,
        'password' => $password
      ];

      $unique_id = $db->checkUser($userData) ?? '';

      if($unique_id){

        if($unique_id > 0){

          // Set session unique id to identify de session
          $_SESSION['unique_id'] = $unique_id;
          $db->updateStatus('Active now', $unique_id);
          echo 'success';

        }
        
      }else{

        echo "Incorrect password";

      }

    }else{

      echo "Email not found";

    }

  }else{

    echo 'This is not a valid email address';    

  }

}else{

  echo 'All fields are required!';

}