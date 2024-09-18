<?php
include('includes/session.php');
include('includes/config.php');

if (isset($_POST['note_id']) && isset($_POST['is_favorite'])) {
    $note_id = $_POST['note_id'];
    $is_favorite = $_POST['is_favorite'];

    // Update the is_favorite column in the notes table
    $query = "UPDATE notes SET is_favorite = '$is_favorite' WHERE note_id = '$note_id' AND user_id = '$session_id'";
    
    if(mysqli_query($conn, $query)){
        echo "success";
    } else {
        echo "error";
    }
}
?>
