
<?php
session_start();

// Define the hard-coded usernames and passwords
$processor_username = 'processor';
$processor_password = '123';

$admin_username = 'admin';
$admin_password = '123';

$programmer_username = 'programmer';
$programmer_password = '123';

// Check if the user is attempting to log in
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verify the user's credentials
    if (($username === $processor_username && $password === $processor_password) ||
        ($username === $admin_username && $password === $admin_password) || 
        ($username === $programmer_username && $password === $programmer_password)) {
        $_SESSION['user'] = $username;

    // Store the user's role in the session
    if ($username === $processor_username) {
        $_SESSION['role'] = 'Processor';
    } elseif ($username === $admin_username) {
        $_SESSION['role'] = 'admin';
    } elseif ($username === $programmer_username) {
        $_SESSION['role'] = 'programmer';
    }
  }
}

// Redirect logged-in users to the appropriate page
if (isset($_SESSION['user'])) {
  if ($_SESSION['user'] === 'admin' || $_SESSION['user'] === 'processor') {
      header('Location: search.php');
  } elseif ($_SESSION['user'] === 'programmer') {
      header('Location: frontpage.php'); // Change this to the appropriate Programmer page
  }
  exit();
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="./img/DFA.png">
    <title>login</title>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
      }

      section {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(
          -25deg,
          #03a9f4 0%,
          #3a78b7 50%,
          #262626 50%,
          #607d8d 100%
        );
        backdrop-filter: hue-rotate(120deg);
        animation: animate 10s ease-in infinite;
      }

      @keyframes animate {
        0% {
          filter: hue-rotate(0deg);
        }
        100% {
          filter: hue-rotate(360deg);
        }
      }

      .box {
        position: relative;
        padding: 50px;
        padding-top: 0px;
        width: 360px;
        height: 480px;
        display: flex;
        transform: translateY(-800px);
        justify-content: center;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        box-shadow: 0 5px 35px rgba(0, 0, 0, 0.2);
        animation: appare 1.5s linear;
        animation-iteration-count: 1;
        animation-direction: normal;
        animation-fill-mode: forwards;
      }

      @keyframes appare {
        0% {
          transform: translateY(-800px);
        }
        40% {
          transform: translateY(0px);
        }
        55% {
          transform: translateY(-80px);
        }
        70% {
          transform: translateY(0px);
        }
        85% {
          transform: translateY(-40px);
        }
        100% {
          transform: translateY(0px);
        }
      }

      .box::after {
        content: "";
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border-radius: 5px;
        pointer-events: none;
        background: linear-gradient(
          to bottom,
          rgba(255, 255, 255, 0.3) 0%,
          rgba(255, 255, 255, 0.1) 15%,
          transparent 50%,
          transparent 85%,
          rgba(255, 255, 255, 0.3) 100%
        );
      }

      .box .form {
        position: relative;
        width: 100%;
      }

      .box .form h2 {
        color: white;
        font-weight: 600;
        letter-spacing: 2px;
        margin-bottom: 30px;
        text-align: center;
        position: relative;
        top: -20px;
      }

      .box .form .inputBx {
        position: relative;
        width: 100%;
        margin-bottom: 20px;
      }

      .box .form .inputBx input {
        width: 100%;
        outline: none;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: transparent;
        padding: 8px 10px;
        padding-left: 35px;
        border-radius: 6px;
        color: white;
        font-size: 16px;
        font-weight: 300;
        box-shadow: inset 0 0 25px rgba(0, 0, 0, 0.2);
      }

      .box .form .inputBx input ::placeholder {
        color: white;
      }

      .box .form .inputBx input[type="submit"] {
        background: white;
        color: black;
        max-width: 150px;
        padding: 8px 24px;
        box-shadow: none;
        font-weight: 700;
        letter-spacing: 1px;
        cursor: pointer;
        font-size: 16px;
      }

      .box .form .inputBx input[type="submit"]:disabled {
        background: rgb(175, 175, 175);
        color: rgb(116, 116, 116);
        font-weight: 300;
        letter-spacing: 1px;
        cursor: not-allowed;
        font-size: 14px;
      }

      .box .form .inputBx img {
        position: absolute;
        top: 10px;
        left: 10px;
        transition: scale(0.7);
        filter: invert(1);
      }

      .remeber {
        position: relative;
        display: inline-block;
        color: white;
        font-weight: 300;
        margin-bottom: 10px;
        cursor: pointer;
      }

      .box .form p {
        color: white;
        font-weight: 300;
        font-size: 15px;
        margin-top: 5px;
      }

      .box .form a {
        color: white;
      }

      .box .form a:hover {
        color: purple;
      }

      @media screen and (max-width: 400px) {
        .box {
          padding: 20px;
          width: 310px;
          height: 420px;
        }
      }

      .user {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: block;
        position: relative;
        top: -70px;
        filter: grayscale(1);
        margin: auto;
        box-shadow: 0 5px 35px rgba(0, 0, 0, 0.2);
        border: 5px solid rgba(255, 255, 255, 0.1);
      }
    </style>
  </head>
  <body>
    <section>
      <div class="box">
        <div class="form">
          <img src="img/DFA.png" class="user" alt="broken-image" />
          <h2>Hello DFA!</h2>
          <form class="" action="index.php" method="post" >
           
          <!-- Log in Page -->
            <div class="inputBx">
              <input type="text" name="username" placeholder="Username" id="username" oninput="validation()" required autofocus autocomplete="off"/>
              <img src="img/user.png" alt="broken-image" />
            </div>

            <div class="inputBx">
              <input type="password" name="password" id="password" placeholder="Password" oninput="validation()" required />
              <img src="img/lock.png" alt="broken-image" />
            </div>


            <div class="inputBx">
              <input type="submit" name="submit" value="Login" id="submit" disabled />
            </div>

          </form>
        </div>
      </div>
    </section>

    <script>
      function validation() {
        let username = document.getElementById("username").value;
        let pass = document.getElementById("password").value;
        if (username != "" && pass != "") {
          document.getElementById("submit").disabled = false;
        } else {
          document.getElementById("submit").disabled = true;
        }
      }
    </script>
  </body>
</html>