<?php
include('includes/session.php');
include('includes/config.php');

$name = ''; // Initialize $name variable
$email = ''; // Initialize $email variable
$current_password = ''; // Initialize $current_password variable

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    
    // Check if the email is already in use
    $check_query = "SELECT * FROM register WHERE email = '$email' AND user_ID != '$session_id'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "Email is already in use";
    } else {
        // Check if a new profile picture is uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
            $profile_picture_name = $_FILES['profile_picture']['name'];
            $profile_picture_type = $_FILES['profile_picture']['type'];
            
            // Validate file type (for example, allow only image files)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($profile_picture_type, $allowed_types)) {
                $error_message = "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
            } else {
                // Move uploaded file to the desired location
                $profile_picture_path = 'profile_pictures/' . $profile_picture_name;
                if (move_uploaded_file($profile_picture_tmp, $profile_picture_path)) {
                    // File uploaded successfully, update the database
                    if (updateUser($conn, $session_id, $name, $email, $new_password, $profile_picture_path)) {
                        // Data updated successfully
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        // Error updating data
                        $error_message = "Error updating user data";
                    }
                } else {
                    // Error moving uploaded file
                    $error_message = "Error uploading profile picture";
                }
            }
        } else {
            // No new profile picture uploaded, update other fields directly
            if (updateUser($conn, $session_id, $name, $email, $new_password)) {
                // Data updated successfully
                header("Location: dashboard.php");
                exit();
            } else {
                // Error updating data
                $error_message = "Error updating user data";
            }
        }
    }
}

// Fetch user data
$query = "SELECT * FROM register WHERE user_ID = '$session_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['fullName'];
    $email = $row['email'];
    // Fetch user image
    $profile_image_path = $row['image'];
} else {
    // Handle error if user data not found
    $error_message = "User data not found";
}

// Function to update user data
function updateUser($conn, $session_id, $name, $email, $new_password, $profile_picture_path = null) {
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    if (!empty($new_password)) {
        // If a new password is provided, hash it
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        if ($profile_picture_path) {
            // Update profile picture and other fields
            $update_query = "UPDATE register SET fullName = '$name', email = '$email', password = '$new_password_hash', image = '$profile_picture_path' WHERE user_ID = '$session_id'";
        } else {
            // Update other fields only
            $update_query = "UPDATE register SET fullName = '$name', email = '$email', password = '$new_password_hash' WHERE user_ID = '$session_id'";
        }
    } else {
        // If no new password provided, update other fields only
        if ($profile_picture_path) {
            // Update profile picture only
            $update_query = "UPDATE register SET fullName = '$name', email = '$email', image = '$profile_picture_path' WHERE user_ID = '$session_id'";
        } else {
            // No new password or profile picture provided, update other fields only
            $update_query = "UPDATE register SET fullName = '$name', email = '$email' WHERE user_ID = '$session_id'";
        }
    }
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        return true; // Updated successfully
    } else {
        return false; // Error updating data
    }
}

// Function to delete user
function deleteUser($conn, $session_id) {
    $delete_query = "DELETE FROM register WHERE user_ID = '$session_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        return true; // Deleted successfully
    } else {
        return false; // Error deleting user
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile</title>
    <!-- Include any necessary CSS stylesheets -->
    <style>
        body {
            min-height: 100vh;
            width: 100%;
            background-image: url('images/try.jpg');
            background-color: white;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            /* Make the background image fixed */
        }

        * {
            margin: 0;
            padding: 0;
        }

        .container {
            text-align: center;
            color: black;
        }

        .form {
            margin: auto;
            border-radius: 20px;
            position: relative;
            background-color: #fff;
            color: #fff;
            border: 5px solid #333;
            max-width: 400px;
            /* Set maximum width for the form */
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
            background-color: #00bfff;
            /* Adjust existing styles */
            display: block;
            /* Change display to block */
            margin: 0 auto;
            /* Center horizontally */
            width: 100%;
            /* Adjust width as needed */
        }

        .submit:hover {
            background-color: #00bfff96;
        }

        .error-message {
            color: red;
        }
    </style>
</head>

<body>
    <form name="signup" id="signup-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" novalidate>
        <div class="form">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="form-group">
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
                <?php if (isset($error_message) && $error_message === "Email is already in use"): ?>
                    <div class="error-message">Email is already in use</div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="control-label title">Current Password</label>
                <input name="current_password" id="current_password" type="password" placeholder="" class="form-control input-lg signup-input <?php echo isset($errors['current_password']) ? 'is-invalid' : ''; ?>">
                <?php if (isset($errors['current_password'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['current_password']; ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="control-label title">New Password</label>
                <input name="new_password" id="new_password" type="password" placeholder="" class="form-control input-lg signup-input <?php echo isset($errors['new_password']) ? 'is-invalid' : ''; ?>">
                <?php if (isset($errors['new_password'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['new_password']; ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="control-label title">Profile Picture</label>
                <img src="<?php echo htmlspecialchars($profile_image_path); ?>" alt="Current Profile Picture" width="100">
                <input name="profile_picture" id="profile_picture" type="file" class="form-control input-lg signup-input <?php echo isset($errors['profile_picture']) ? 'is-invalid' : ''; ?>">
                <?php if (isset($errors['profile_picture'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['profile_picture']; ?></div>
                <?php endif; ?>
            </div>
            <!-- Add other fields as needed -->
            <button type="submit">Save Changes</button>
            <a href="dashboard.php"><button type="button">Cancel</button></a>
        </div>
    </form>
</body>

</html>
