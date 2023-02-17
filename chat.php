
<?php

require_once 'pages/header.php';
require_once 'php/DBConnection.php';

session_start();

if(!isset($_SESSION['unique_id'])){
  header('Location: users.php');
  exit;
}

$db = new DBConnection();

$userData = $db->getUser(htmlentities($_GET['user_id']));

if(!$userData)
  header('Location: users.php');

$_SESSION['incoming_id'] = $userData['unique_id'];

?>

<body>
  <main class="more-width">
    <section class="chat-area">
      <header>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/userImages/<?= $userData['img'] ?>" alt="">
        <div class="details">
          <span><?= $userData['fname'] . ' ' . $userData['lname'] ?></span>  
          <p><?= $userData['status'] ?></p>
        </div>
      </header>
      <article class="chat-box">
        
      </article>
      <form action="" class="typing-area">
        <input type="text" placeholder="Type a message here">
        <button>
          <i class="fa fa-paper-plane"></i>
        </button>
      </form>
    </section>
  </main>

  <script src="js/chat.js"></script>
</body>
</html>