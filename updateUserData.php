<?php
session_start();
include('includes/config.php');

if (isset($_POST['update'])) {
    // Retrieve form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Encrypt password before storing

    // Update user data in the database
    $userId = $_SESSION['alogin'];
    $query = "UPDATE register SET fullName = '$fullName', email = '$email', password = '$password' WHERE user_id = '$userId'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "<script>alert('User data updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating user data');</script>";
    }
}
?>
