<?php

require_once './DBConnection.php';

session_start();

$db = new DBConnection();

// Get post form values
$fName =  htmlentities($_POST['fName']) ?? '';
$lName =  htmlentities($_POST['lName']) ?? '';
$email =  htmlentities($_POST['email']) ?? '';
$password =  htmlentities($_POST['password']) ?? '';

// Check field isn't emptys
if(!empty($fName) &&!empty($lName) &&!empty($email) &&!empty($password)){

  // Check valid email format
  if(filter_var($email, FILTER_VALIDATE_EMAIL)){

    // Check email exists in database
    if($db->checkEmailExists($email)){

      echo 'Email already exists';

    }else{

      // Check image file uploaded
      if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){

        // Get image properties
        $imgName = $_FILES['image']['name'];
        $imgType = $_FILES['image']['type'];
        $tmpName = $_FILES['image']['tmp_name'];
        
        // Split name and extension image
        $imgExplode = explode('.', $imgName);

        // Get extension
        $imgExt =  end($imgExplode);

        // Check valid img extension
        if(in_array($imgExt, ['png', 'jpeg', 'jpg', 'webp'])){

          // Get current time
          $time = time();
          
          // Set new image concating current time and image name without spaces
          $newImgName = $time . str_replace(' ', '', $imgName);

          // If user uploaded a file, move to our folder
          if(move_uploaded_file($tmpName, "userImages/" . $newImgName)){

            // Set status active
            $status = 'Active now';

            $randId = rand(time(), 10000000);

            $passwordHashed = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);

            $userData = [
              'unique_id' => $randId,
              'fname' => $fName,
              'lname' => $lName,
              'email' => $email,
              'password' => $passwordHashed,
              'img' => $newImgName,
              'status' => $status
            ];

            // Insert user
            $unique_id = $db->setUser($userData) ?? '';

            // Check data insert successfully
            if($unique_id){

              if($unique_id > 0){

                // Set session unique id to identify de session
                $_SESSION['unique_id'] = $unique_id;
                echo 'success';

              }

            }else{

              echo 'Something went wrong!';

            } 

          }

        }else{

          echo 'Please select an Image file - jpeg, jpg, png!';

        }

      }else{
        
        echo 'Please upload an image';

      }

    }

  }else{

    echo 'This is not a valid email address';    

  }

}else{

  echo 'All fields are required!';

}