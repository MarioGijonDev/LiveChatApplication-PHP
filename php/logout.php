<?php

session_start();

if(!isset($_SESSION['unique_id']) || !isset($_GET['logout_id'])){
  header('Location: ../login.php');
  exit;
}
  

require_once 'DBConnection.php';
$db = new DBConnection();

$logout_id = htmlentities($_GET['logout_id']);

$logout = $db->updateStatus('Offline', $logout_id);

session_unset();
session_destroy();
header('Location: ../login.php');
exit;

