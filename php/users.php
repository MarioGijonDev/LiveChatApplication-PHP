<?php

require_once './DBConnection.php';

session_start();

if(!isset($_SESSION['unique_id'])){
  header('Location: ../login.php');
  exit;
}

$db = new DBConnection();

$allUsers = $db->getAllUsers($_SESSION['unique_id']);

if($allUsers)
  require_once 'renderUsers.php';
else
  echo "No users available";