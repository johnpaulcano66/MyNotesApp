<?php
include('includes/session.php');
include('includes/config.php');

if(isset($_GET['note_id'])) {
    $note_id = $_GET['note_id'];

    // Update the note's archive status to unarchive it
    $sql = "UPDATE notes SET archive = 0 WHERE note_id = $note_id";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Redirect back to the archive page after unarchiving
        header("Location: archive.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
