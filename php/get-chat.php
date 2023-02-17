<?php

session_start();

if(!isset($_SESSION['unique_id'])){
  header("Location: ../login.php");
  exit;
}

require_once 'DBConnection.php';
$db = new DBConnection();

$postStringData = file_get_contents('php://input');
$postArrayData = (json_decode($postStringData, true));

$msgData = [
  'incoming_msg_id' => $_SESSION['incoming_id'],
  'outgoing_msg_id' => $_SESSION['unique_id']
];

$messages = $db->getMessages($msgData);

if(!$messages)
  exit;

$msgElements = '';

foreach($messages as $msg){
  if($msg['outgoing_msg_id'] === $_SESSION['unique_id']){

    $msgElements .= <<< _USER
      <div class="chat outgoing">
        <div class="details">
          <p>{$msg['msg']}</p>
        </div>
      </div>
    _USER;

  }else{

    $msgElements .= <<< _USER
    <div class="chat incoming">
      <img src="php/userImages/{$msg['img']}" alt="">
      <div class="details">
        <p>{$msg['msg']}</p>
      </div>
    </div>
    _USER;

  }
}

echo $msgElements;