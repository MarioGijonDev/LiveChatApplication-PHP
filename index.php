
<?php require_once 'pages/header.php' ?>

<body>
  <main>
    <section class="form signup">
      <header>Realtime Chat App</header>
      <form action="php/signup.php" enctype="multipart/form-data">
        <div class="error-txt">This is an error message!</div>
        <div class="name-details">
          <div class="field input">
            <label for="">First name</label>
            <input type="text" name="fName">
          </div>
          <div class="field input">
            <label for="">Last name</label>
            <input type="text" name="lName">
          </div>
        </div>
        <div class="field input">
          <label for="password">Email address</label>
          <input type="email" name="email">
        </div>
        <div class="field input">
          <label for="password">Password</label>
          <input type="password" name="password">
          <i class="fas fa-eye"></i>
        </div>
        <div class="field image">
          <label for="">Profile picture</label>
          <input type="file" name="image">
        </div>
        <div class="field button">
          <input type="submit" value="Continue to chat">
        </div>
        <div class="link">Already signed up? <a href="login.php">Login now</a></div>
      </form>
    </section>
  </main>

  <script src="js/signup.js"></script>
  <script src="js/pass-show-hide.js"></script>
</body>
</html>