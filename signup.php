<?php
session_start();
include('includes/config.php');

$errors = []; // Array to store validation errors

$name = isset($_POST['name']) ? $_POST['name'] : ''; // Retain user input if available
$email = isset($_POST['email']) ? $_POST['email'] : ''; // Retain user input if available

if(isset($_POST['signup'])) {
    // Validate Name
    if(empty($_POST['name'])) {
        $errors['name'] = "Name is required";
    } else if(strlen($_POST['name']) < 6) {
        $errors['name'] = "Name must be at least 6 characters long";
    } else {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
    }

    // Validate Email
    if(empty($_POST['email'])) {
        $errors['email'] = "Email is required";
    } else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    }

    // Validate Password
    if(empty($_POST['password'])) {
        $errors['password'] = "Password is required";
    } else if(strlen($_POST['password']) < 6) {
        $errors['password'] = "Password must be at least 6 characters long";
    } else {
        $password = md5($_POST['password']);
    }

    // Check if there are no validation errors
    if(empty($errors)) {
        $query = mysqli_query($conn, "SELECT * FROM register WHERE email = '$email'") or die(mysqli_error($conn));
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            $errors['email'] = "Email already exists";
        } else {
            $query = mysqli_query($conn, "INSERT INTO register(fullName, email, password) VALUES('$name', '$email', '$password')") or die(mysqli_error($conn));
            if($query) {
                $_SESSION['success_msg'] = "User registered successfully";
                header("location: index.php");
                exit();
            } else {
                $errors[] = "Error while registering user";
            }
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
  <link rel="stylesheet" href="css/app.css" type="text/css" />
  <h1 style="color:  #78A083;" class="typewriter">NOTE TAKING WEBSITE</h1>
    
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>

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
    margin-top: -85px;
    
    border-radius: 20px;
    position: relative;
    background-color: #fff;
    color: #fff;  
    border: 5px solid #0E46A3;
    max-width: 400px; /* Set maximum width for the form */
  
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
      /* Adjust existing styles */
      
  display: block; /* Change display to block */
  margin: 0 auto; /* Center horizontally */
  width: 100%; /* Adjust width as needed */
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
.title::before {
    background-color: #78A083;
    
    width: 18px;
    height: 18px;
  }

  .title::after {
    background-color: #78A083;
    color: #78A083;
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
    background-color: #78A083;
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
.btn-block {
  /* Other styles */
  margin: 0 auto; /* Center horizontally */
}

.login{
      /* Adjust existing styles */
  display: block; /* Change display to block */
  margin: 0 auto; /* Center horizontally */
  width: 50%; /* Adjust width as needed */

}

.message,
.signin {
  /* Remove conflicting styles */
  /* text-align: center; */

  /* Adjust existing styles */
  margin: 0 auto; /* Center horizontally */
  width: 50%; /* Adjust width as needed */
}

.btn-block {
  width: 100%; /* Ensure buttons take full width */
}
.form-group label.title {
    color: #78A083;
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
    background-color: #78A083;
  }

  
.signup-input {
  margin-right: 200px;
  -webkit-appearance: none;
  appearance: none;
  width: 150%;
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

.login:hover span:not(:nth-child(6)) {
  transform: translate(-50%, -50%) scale(4);
  transition: 1.5s ease;
}


  
</style>
<section id="content" class="m-t-lg wrapper-md animated fadeInDown">

<div class="form">
        <header class="panel-heading text-center">
          <strong>Sign up</strong>
        </header>
        <?php if (!empty($errors)): ?>
          <div style="color: #000;" class="alert alert-danger">
            <ul>
              <?php foreach ($errors as $field => $error): ?>
                <li><?php echo $error; ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
         <?php endif; ?>
         <form name="signup" id="signup-form" method="POST" novalidate>
          <div style="margin:  0 10000px 0 20px;" class="panel-body wrapper-lg">
            <div style="margin:  0px 40px 0 0;" class="form-group">
              <label class="control-label title">Name</label>
              <input name="name" id="name" type="text" placeholder=" " class="form-control input-lg signup-input <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($name); ?>">
              <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label class="control-label title">Email</label>
              <input name="email" id="email" type="email" placeholder="" class="form-control input-lg signup-input <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>">
              <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label class="control-label title">Password</label>
              <input style="margin-bottom: 20px;" name="password" id="password" type="password" placeholder="" class="form-control input-lg signup-input <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>">
              <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
              <?php endif; ?>
            </div>
         
            <button style="background-color:  #78A083; width: 300px; margin: 0 0px 0 -25px" name="signup" type="submit" class="btn btn-primary btn-block submit">
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span></span>
  <span>Sign up</span>
</button>

         
<p style="color: #000; opacity: 0.9; margin: 20px -20px 20px 55px; display: inline-block;">Already have an account?</p>



              
<a href="index.php">
  <button style="background-color: #78A083; width: 300px; margin: 0 0px 0 -25px" name="signup" type="button" class="btn btn-primary btn-block submit">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span></span>
    <span>Log In</span>
  </button>
</a>


            
          </div>
        </form>
      </div>
    </section>

    <footer id="footer">
      
    

  
 
  
  <!-- App -->
  <script src="js/app.js"></script>
  <script src="js/app.plugin.js"></script>
  <script src="js/slimscroll/jquery.slimscroll.min.js"></script>
  
  <script>
    function validateForm() {
        // Reset previous error messages
        document.getElementById('error-message').innerHTML = '';

        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;

        var errors = [];

        if(name.trim() === '') {
            errors.push('Name is required');
        } else if(name.trim().length < 6) {
            errors.push('Name must be at least 6 characters long');
        }

        if(email.trim() === '') {
            errors.push('Email is required');
        } else if(!validateEmail(email.trim())) {
            errors.push('Invalid email format');
        }

        if(password.trim() === '') {
            errors.push('Password is required');
        } else if(password.trim().length < 6) {
            errors.push('Password must be at least 6 characters long');
        }

        if(errors.length > 0) {
            var errorMessage = '<ul>';
            errors.forEach(function(error) {
                errorMessage += '<li>' + error + '</li>';
            });
            errorMessage += '</ul>';
            document.getElementById('error-message').innerHTML = errorMessage;
            return false;
        }

        return true;
    }

    function validateEmail(email) {
        var re = /\S+@\S+\.\S+/;
        return re.test(email);
    }
</script>
</body>
</html>

