
<?php require_once 'pages/header.php' ?>

<body>
  <main>
    <section class="form signup">
      <header>Realtime Chat App</header>
      <form action="#">
        <div class="error-txt">This is an error message!</div>
        <div class="field input more-margin">
          <label for="password">Email Address</label>
          <input type="email" name="email">
        </div>
        <div class="field input">
          <label for="password">Password</label>
          <input type="password" name="password">
          <i class="fas fa-eye"></i>
        </div>
        <div class="field button">
          <input type="submit" value="Continue to chat">
        </div>
        <div class="link">Not yet signed up? <a href="index.php">Sign up now</a></div>
      </form>
    </section>
  </main>

  <script src="js/pass-show-hide.js"></script>
  <script src="js/login.js"></script>
</body>
</html>