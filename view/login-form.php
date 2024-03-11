<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CITERWEB</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");


    body {
      display: flex;
      justify-content: center;
      padding: 3rem 0;
      font-family: "Poppins", sans-serif;
      font-size: 1rem;
      color: white;
      background-color: #ff7a7a;
    }

    main {
      max-width: 350px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .intro {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      width: 100%;
      margin-bottom: 3rem;
    }

    .title {
      padding: 1rem;
      font-size: 1.75rem;
    }

    .sign-in {
      width: 100%;
    }

    .sign-in-form {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1.2rem;
      border-radius: 0.8rem;
      box-shadow: 0 8px 0px rgba(0 0 0/0.15);
      color: #b9b6d3;
      background-color: white;
    }

    .form-input {
      width: 100%;
      margin-bottom: 1em;
      position: relative;
    }

    .form-input span {
      position: absolute;
      top: 10%;
      right: 0;
      padding: 0 0.65em;
      border-radius: 50%;
      background-color: #ff7a7a;
      color: white;
      display: none;
    }

    .form-input.warning span {
      display: inline-block;
    }

    .form-input input {
      width: calc(100% - 20px);
      padding: 10px;
      border: 2px solid rgba(185, 182, 211, 0.25);
      border-radius: 0.5em;
      font-weight: 600;
      color: #3e3c49;
    }

    .form-input input:focus {
      outline: none;
      border: 2px solid #b9b6d3;
    }

    .form-input.warning input {
      border: 2px solid #ff7a7a;
    }

    .form-input p {
      margin: 0.2em 0.75em 0 0;
      display: none;
      font-size: 0.75rem;
      text-align: right;
      font-style: italic;
      color: #ff7a7a;
    }

    .form-input.warning p {
      display: block;
    }

    .form-input input::-ms-reveal {
      display: none;
    }

    .submit-btn {
      cursor: pointer;
      width: 100%;
      padding: 1em;
      margin-bottom: 1em;
      border: none;
      border-bottom: 5px solid #31bf81;
      border-radius: 0.5em;
      background-color: #38cc8c;
      color: white;
      font-weight: 600;
      text-transform: uppercase;
    }

    .submit-btn:hover {
      background-color: #5dd5a1;
    }
    
    @media (min-width: 768px) {
      body {
        align-items: center;
        min-height: 100vh;
      }

      main {
        max-width: 100vw;
        flex-direction: row;
        justify-content: center;
      }

      .intro {
        align-items: flex-start;
        text-align: left;
        width: 45%;
        margin-right: 1rem;
      }

      .title {
        padding: 0;
        margin-bottom: 2rem;
        font-size: 3rem;
        line-height: 1.25em;
      }

      .sign-in {
        width: 45%;
      }

      .sign-in-form {
        padding: 1.75rem;
      }

      .sign-in-form input {
        padding-left: 1.5em;
      }
    }

    form i {
      margin-left: -30px;
      cursor: pointer;
    }

    .error-message {
      padding: 7px 60px;
      background: #fff1f2;
      border: #ffd5da 1px solid;
      color: #d6001c;
      border-radius: 4px;
      margin: 10px 10px 10px 10px;
    }
  </style>
</head>

<body>
  <main>
    <!-- intro section -->
    <section class="intro">
      <h1 class="title">CITER APP Content Management System</h1>
    </section>

    <!-- sign-in section -->
    <section class="sign-in">
      <form action="./../login-action.php" method="post" class="sign-in-form" id="form-login">
        <?php 
          if(isset($_SESSION["errorMessage"])) {
        ?>
        <div class="error-message"><?php  echo $_SESSION["errorMessage"]; ?></div>
        <?php 
          unset($_SESSION["errorMessage"]);
          } 
        ?>
        <div class="form-input">
          <input type="text" name="username" id="username" placeholder="Username" autofocus required>
          <span>!</span>
          <p class="warning">Username cannot be empty</p>
        </div>

        <div class="form-input">
          <input type="password" name="password" id="password" placeholder="Password" required>
          <i class="bi bi-eye-slash" id="togglePassword"></i>
          <span>!</span>
          <p class="warning">Password cannot be empty</p>
        </div>

        <input class="submit-btn" name="login" type="submit" value="SIGN IN">
      </form>
    </section>
  </main>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      // toggle the eye / eye slash icon
      this.classList.toggle('bi-eye');
          
        
    });

</script>
</body>

</html>