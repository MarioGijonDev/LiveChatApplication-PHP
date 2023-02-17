<?php

if(!isset($_SESSION['unique_id'])){
  header('Location: ../login.php');
  exit;
}

$usersElements = '';

foreach($allUsers as $user) {

  /* $lastMsgData = [
    'outgoing_msg_id' => $user['unique_id'],
    'incoming_msg_id' => $_SESSION['unique_id']
  ]; */

  /* $lastMsg = $db->getLastMsg($lastMsgData);

  if($lastMsg){
    $msg = $lastMsg['msg'];
    $emitter = $lastMsg['outgoing_msg_id'] === $_SESSION['unique_id'] ? 'You: ' : '' ;
  }else{
    $msg = '<i>No message available</i>';
    $emitter = '';
  } */

  $statusDot = ($user['status'] === 'Offline') ? 'offline'  : '' ;

  /* echo "<pre>";
  var_dump($msg);
  echo "<pre>"; */

  $usersElements .= <<< _USER
    <a href="chat.php?user_id={$user['unique_id']}">
      <div class="content">
        <img src="php/userImages/{$user['img']}" alt="">
        <div class="details">
          <span>{$user['fname']} {$user['lname']}</span>
          
        </div>
      </div>
      <div class="status-dot $statusDot">
        <i class="fas fa-circle"></i>
      </div>
    </a>
  _USER;
}

echo $usersElements;

