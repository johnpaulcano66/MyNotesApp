<?php
session_start();

include('includes/config.php');

// Initialize error flags
$emailError = $passwordError = false;

if(isset($_POST['signin']))
{
    $email=$_POST['email'];
    $password=md5($_POST['password']);

    $sql ="SELECT * FROM register where email ='$email' AND password ='$password'";
    $query= mysqli_query($conn, $sql);
    $count = mysqli_num_rows($query);
    if($count > 0)
    {
        while ($row = mysqli_fetch_assoc($query)) {
            $_SESSION['alogin']=$row['user_ID'];
            echo "<script type='text/javascript'> document.location = 'notebook.php'; </script>";
        }

    } 
    else {
        // Check for invalid email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = true;
        }

        // Check for invalid password
        // Here, you can add additional checks if necessary, such as length requirements
        if (strlen($_POST['password']) < 6) {
            $passwordError = true;
        }

    }
}
?>



<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
  <meta charset="utf-8" />
  <title>Notebook | Web Application</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="css/animate.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/font.css" type="text/css" />

    
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<h1 style="color: #000;" class="typewriter">NOTE TAKING WEBSITE</h1>
<style>
  body {
    max-height: fit-content;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-image: url('images/try.jpg');
    background-size: cover;
    background-repeat: no-repeat;
  }

  .form {
    margin: auto; /* Center the form horizontally */
    margin-top: 50px; /* Adjust top margin as needed */
    padding: 20px;
    border-radius: 20px;
    position: relative;
    background-color: #fff;
    color: #fff;  
    border: 5px solid #333;
    max-width: 400px; /* Set maximum width for the form */
    width: 100%; /* Ensure the form takes full width */
}


  .title {
    font-size: 28px;
    font-weight: 600;
    letter-spacing: -1px;
    position: relative;
    display: flex;
    align-items: center;
    padding-left: 30px;
    color: #00bfff;
  }

  .title::before {
    width: 18px;
    height: 18px;
  }

  .title::after {
    width: 18px;
    height: 18px;
    animation: pulse 1s linear infinite;
  }

  .title::before,
  .title::after {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    border-radius: 50%;
    left: 0px;
    background-color: #00bfff;
  }

  .message,
  .signin {
    font-size: 14.5px;
    color: rgba(255, 255, 255, 0.7);
  }

  .signin-form {
    
    padding: 1;
    text-align: center;
  }

  .signin a:hover {
    text-decoration: underline royalblue;
  }

  .signin a {
    color: #00bfff;
  }

  .flex {
    display: flex;
    width: 100%;
    gap: 6px;
  }

  .form label {
    position: relative;
  }

  .form label .input {
    background-color: #333;
    color: #fff;
    width: 100%;
    padding: 20px 05px 05px 10px;
    outline: 0;
    border: 1px solid rgba(105, 105, 105, 0.397);
    border-radius: 10px;
  }

  .form label .input+span {
    color: rgba(255, 255, 255, 0.5);
    position: absolute;
    left: 10px;
    top: 0px;
    font-size: 0.9em;
    cursor: text;
    transition: 0.3s ease;
  }

  .form label .input:placeholder-shown+span {
    top: 12.5px;
    font-size: 0.9em;
  }

  .form label .input:focus+span,
  .form label .input:valid+span {
    color: #00bfff;
    top: 0px;
    font-size: 0.7em;
    font-weight: 600;
  }

  .input {
    font-size: medium;
  }

  .submit {
    border: none;
    outline: none;
    padding: 10px;
    border-radius: 10px;
    color: #fff;
    font-size: 16px;
    transform: .3s ease;
    background-color: #00bfff;
  }

  .submit:hover {
    background-color: #00bfff96;
  }

  @keyframes pulse {
    from {
      transform: scale(0.9);
      opacity: 1;
    }

    to {
      transform: scale(1.8);
      opacity: 0;
    }
  }

  button {
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bold;
    color: white;
    background-color: #171717;
    padding: 1em 2em;
    border: none;
    border-radius: .6rem;
    position: relative;
    cursor: pointer;
    overflow: hidden;
  }

  button span:not(:nth-child(6)) {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    height: 30px;
    width: 30px;
    background-color: #0c66ed;
    border-radius: 50%;
    transition: .6s ease;
  }

  button span:nth-child(6) {
    position: relative;
  }

  button span:nth-child(1) {
    transform: translate(-3.3em, -4em);
  }

  button span:nth-child(2) {
    transform: translate(-6em, 1.3em);
  }

  button span:nth-child(3) {
    transform: translate(-.2em, 1.8em);
  }

  button span:nth-child(4) {
    transform: translate(3.5em, 1.4em);
  }

  button span:nth-child(5) {
    transform: translate(3.5em, -3.8em);
  }

  button:hover span:not(:nth-child(6)) {
    transform: translate(-50%, -50%) scale(4);
    transition: 1.5s ease;
  }

  .panel-body {
   
    padding: 50px;
    margin: 0 auto;
    width: 70%;
    /* Adjust the width as needed */
  }

  body {
    
  height: 100vh;
  display: grid;
  font-family: Roboto;
  -webkit-text-size-adjust: 100%;
  -webkit-font-smoothing: antialiased;
}
  
*, *:before, *:after {
  box-sizing: border-box;
}

.form-group {
  position: relative;
  margin: auto;
  width: 100%;
  max-width: 280px;
}

.signup-input {
  -webkit-appearance: none;
  appearance: none;
  width: 100%;
  border: 0;
  font-family: inherit;
  padding: 16px 12px 0 12px;
  height: 56px;
  font-size: 16px;
  font-weight: 400;
  background: rgba(0,0,0,.02);
  box-shadow: inset 0 -1px 0 rgba(0,0,0,.3);
  color: #000;
  transition: all .15s ease;
}

.signup-input:hover {
  background: rgba(0,0,0,.04);
  box-shadow: inset 0 -1px 0 rgba(0,0,0,.5);
}

.signup-input:not(:placeholder-shown) + label .title {
  color: rgba(0,0,0,.5);
  font-size: 12px; /* Adjust as needed */
}

.signup-input:focus {
  background: rgba(0,0,0,.05);
  outline: none;
  box-shadow: inset 0 -2px 0 #0077FF;
}

.signup-input:focus + label .title {
  color: #0077FF;
  font-size: 12px; /* Adjust for focused label, similar to the transform effect */
}
.typewriter {
  color: #fff;
  padding-left: 80px;
  font-size: 50px;
  overflow: hidden; /* Ensures the content is not revealed until the animation */
  border-right: .15em solid orange; /* The typewriter cursor */
  white-space: nowrap; /* Keeps the content on a single line */
  margin: 0 auto; /* Gives that scrolling effect as the typing happens */
  letter-spacing: .15em; /* Adjust as needed */
  animation: 
    typing 3.5s steps(40, end),
    blink-caret .75s step-end infinite;
}

/* The typing effect */
@keyframes typing {
  from { width: 0 }
  to { width: 100% }
}

/* The typewriter cursor effect */
@keyframes blink-caret {
  from, to { border-color: transparent }
  50% { border-color: orange; }
}

h1 {
  color: #fff;
  font-size: 50px;
  -webkit-text-stroke: 2px black; /* For webkit browsers */
  
}

/* Add a CSS class for the fade-in animation */
.fade-in {
    opacity: 0; /* Start with opacity 0 */
    animation: fadeInAnimation 2s ease forwards; /* Apply animation */
}

@keyframes fadeInAnimation {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Add this CSS code to ensure consistent sizing */

.form-control.input-lg {
  height: 56px; /* Set the height of the input fields */
}

.submit {
  padding: 16px 0; /* Set padding for the buttons */
}

.btn-block {
  width: 100%; /* Ensure buttons take full width */
}


  
</style>
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
      <div class="container aside-xxl">
        <header class="panel-heading text-center">
        </header>
        <form class="signin-form" name="signin" method="post"novalidate>
          <div style="justify-content: center;" class="panel-body wrapper-lg form">
          <div class="form-group">
    <label style="background-color: #78A083;" class="control-label">
        <span style="color:#78A083" class="title">Email</span>
    </label>
    <input name="email" type="email" placeholder="email" class="signup-input <?php if($emailError) echo 'is-invalid'; ?>" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
    <?php if($emailError): ?>
        <div class="invalid-feedback" style="color: red;">
            Error Email
        </div>
    <?php endif; ?>
</div>
<div  class="form-group">
    <label class="control-label">
        <span style="color: #78A083;" class="title">Password</span>
    </label>
    <input name="password" type="password" id="inputPassword" placeholder="Password" class="signup-input <?php if($passwordError) echo 'is-invalid'; ?>" required>
    <?php if($passwordError): ?>
        <div class="invalid-feedback" style="color: red;">
            Error Password
        </div>
    <?php endif; ?>
</div>

            <div class="line line-dashed"></div>
            <button name="signin" type="submit" class="btn btn-primary btn-block" style="background-color:#78A083;" style="font-size: 100px;">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              <span></span>
              <span>Login</span>
            </button>
            <div class="line line-dashed"></div>
            <p style="color: #000;" class="text-muted text-center"><small>Do not have an account?</small></p>
            <a href="signup.php" class="btn btn-default btn-block">Create an account</a>
          </div>
        </form>
      </div>
    </section>
    <footer id="footer">
      <div class="text-center padder" style="display: flex; justify-content: center;">
        <p>
          <small style="color: black;">CANO()NOtes | Web Application by Cano and Canono<br>&copy; 2024</small>
        </p>
      </div>
    </footer>
  </div>
  
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/app.js"></script>
  <script src="js/app.plugin.js"></script>
  <script src="js/slimscroll/jquery.slimscroll.min.js"></script>
</body>
</html>