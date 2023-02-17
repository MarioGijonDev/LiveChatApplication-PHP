
<?php

session_start();

if(!isset($_SESSION['unique_id'])){
  header('Location: ../login.php');
  exit;
}

require_once 'DBConnection.php';

$db = new DBConnection();

$postStringData = file_get_contents('php://input');

$postArrayData = json_decode($postStringData, true);

$searchTerms = $postArrayData['searchValue'] ?? '';

$allUsers = $db->getUserByName($searchTerms, $_SESSION['unique_id']);

if($allUsers)
  require_once 'renderUsers.php';
else
  echo "No users available";


