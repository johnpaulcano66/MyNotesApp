<?php
include('includes/session.php');
include('includes/config.php');

$get_id = $_GET['edit'];

// Handle note update
if(isset($_POST['update'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    // Make SQL query to update the note
    $query = "UPDATE notes SET title='$title', note='$note', last_updated_at=CURRENT_TIMESTAMP WHERE note_id='$get_id'";

    if(mysqli_query($conn, $query)){
        echo "<script>alert('Note Updated Successfully');</script>";
        echo "<script type='text/javascript'> document.location = 'notebook.php'; </script>";
    } else {
        // Failure
        echo 'Query error: '. mysqli_error($conn);
    }
}

// Fetch note details
$query = "SELECT note_id, title, note, time_in FROM notes WHERE note_id='$get_id'";
if(mysqli_query($conn, $query)){
    // Get the query result
    $result = mysqli_query($conn, $query);

    // Fetch result in array format
    $noteDetails = mysqli_fetch_assoc($result);
} else {
    // Failure
    echo 'Query error: '. mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en" class="app">
<head>
  <meta charset="utf-8" />
  <title>Notebook | Edit Note</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/app.css" type="text/css" />
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/stylezz.css" type="text/css">
  <style>
    body {
        background-image: url('images/try.jpg');
        min-height: 100vh;
        background-color: #f5f5f5;
        font-family: 'Montserrat', sans-serif;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed; /* Make the background image fixed */
    }

    .container {
        display: flex; /* Display horizontally */
        justify-content: center; /* Center the content horizontally */
        align-items: center; /* Center the content vertically */
        /* Your background styles */
        background-image: url('images/try.jpg');
        background-color: white;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed; /* Make the background image fixed */
        transition: background-color 0.3s ease; /* Smooth transition for background color change */
    }

    .form-container {
        width: 100%;
        max-width: 500px;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    textarea.form-control {
        resize: none;
        height: 150px; /* Fixed height */
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
    }

    .btn-container .btn {
        flex: 1;
        margin: 0 5px;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-container .btn.update {
        background-color: #78A083;
        color: #fff;
        border: none;
    }

    .btn-container .btn.cancel {
        background-color: #ccc;
        color: #333;
        border: none;
    }

    .btn-container .btn.update:hover {
        background-color: #5e8b68;
    }

    .btn-container .btn.cancel:hover {
        background-color: #b3b3b3;
    }
    .form{
        padding:  50px 50px 50px 50px;
    }
  </style>
</head>
<body>
<div class="container">
    <form style=" padding:  50px 50px 50px 50px; height:500px   " method="POST" class="form-container">
        <div class="form-group">
            <label>Title</label>
            <input name="title" type="text" placeholder="Title" class="input-sm form-control" value="<?php echo $noteDetails['title']; ?>">
        </div>
        <div class="form-group">
            <label>Note</label>
            <!-- Add an ID to the textarea for easier access -->
            <textarea id="noteTextarea" style="background-color: #93B1A6; padding: 50px 50px 50px 50px; height:250px" name="note" class="form-control" placeholder="Take a Note ......"><?php echo $noteDetails['note']; ?></textarea>
        </div>
        
        <div class="btn-container">
            <button class="btn btn-sm btn-default update" name="update" type="submit">Update</button>
            <a style="text-align: center;" href="notebook.php" class="btn btn-sm btn-default cancel">Cancel</a>
        </div>
    </form>
</div>

<script src="js/jquery.min.js"></script>
<script>
    // Wait for the document to be ready
    $(document).ready(function() {
        // Get the textarea element
        const textarea = $('#noteTextarea');

        // Add event listener for input event
        textarea.on('input', function() {
            // Calculate the number of characters in the textarea
            const characterCount = $(this).val().length;

            // Determine the height of the textarea based on character count
            // You can adjust these values based on your preference
            let rows = 3; // Default number of rows
            if (characterCount > 50) {
                rows = 5; // Increase rows if more than 50 characters
            }
            if (characterCount > 100) {
                rows = 8; // Increase rows if more than 100 characters
            }

            // Set the number of rows dynamically
            $(this).attr('rows', rows);
        });
    });
</script>

<script src="js/bootstrap.js"></script>
<script src="js/app.js"></script>
</body>
</html>
