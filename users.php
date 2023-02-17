
<?php

require_once 'pages/header.php';
require_once 'php/DBConnection.php';

$db = new DBConnection();

session_start();

if(!isset($_SESSION['unique_id'])){
  header('Location: login.php');
  exit;
}else{
  $userData = $db->getUser($_SESSION['unique_id']);
}


?>

<body>
  <main class="more-width">
    <section class="users">
      <header>
        <div class="content">
          <img src="php/userImages/<?= $userData['img'] ?>" alt="">
          <div class="details">
            <span><?= $userData['fname'] . ' ' . $userData['lname'] ?></span>
            <p><?= $userData['status'] ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?= $_SESSION['unique_id'] ?>" class="logout">Logout</a>
      </header>
      <article class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </article>
      <article class="users-list"></article>
    </section>
  </main>

  <script src="js/users.js"></script>
</body>
</html>