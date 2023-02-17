<?php

session_start();

if(!isset($_SESSION['unique_id'])){
  header('../login.php');
  exit;
}
  
require_once 'DBConnection.php';
$db = new DBConnection();

$postStringData = file_get_contents('php://input');
$postArrayData = (json_decode($postStringData, true));

$msgData = [
  'incoming_msg_id' => $_SESSION['incoming_id'],
  'outgoing_msg_id' => $_SESSION['unique_id'],
  'msg' => $postArrayData['inputField']
];

$msg = $db->setMessage($msgData);


