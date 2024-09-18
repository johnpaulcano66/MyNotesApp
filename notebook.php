<?php
// Include session and configuration files
include('includes/session.php');
include('includes/config.php');

// Function to retrieve notes based on query
function getNotes($conn, $query)
{
    if (mysqli_query($conn, $query)) {
        // Get the query result
        $result = mysqli_query($conn, $query);

        // Fetch result in array format
        $notesArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $notesArray;
    } else {
        // Failure
        echo 'Query error: ' . mysqli_error($conn);
    }
}

// Your database connection and other necessary code goes here

// Sample query to retrieve notes from the database
$query = "SELECT note_id, title, note FROM notes";

// Fetch notes from the database
$notes = getNotes($conn, $query);


// Handle note deletion
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $sql = "UPDATE notes SET archive = 1 WHERE note_id = $delete"; // Archive instead of deleting
    $result = mysqli_query($conn, $sql);
    if ($result) {
        // Delete the note from favorites as well
        $delete_favorite_sql = "UPDATE notes SET favorite = 0 WHERE note_id = $delete";
        $result_favorite = mysqli_query($conn, $delete_favorite_sql);
        if ($result_favorite) {
            echo "<script type='text/javascript'> document.location = 'notebook.php'; </script>";
        } else {
            echo "Error deleting note from favorites: " . mysqli_error($conn);
        }
    } else {
        echo "Error archiving note: " . mysqli_error($conn);
    }
}

// Handle note archiving (restoring from archive)
if (isset($_GET['archive'])) {
    $archive = $_GET['archive'];
    $sql = "UPDATE notes SET archive = 0 WHERE note_id = $archive"; // Unarchive
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Note restored from Archive');</script>";
        echo "<script type='text/javascript'> document.location = 'notebook.php'; </script>";
    }
}

// Handle note submission
if (isset($_POST['submit'])) {
    $title = trim(mysqli_real_escape_string($conn, $_POST['title'])); // Trim to remove empty characters
    $note = trim(mysqli_real_escape_string($conn, $_POST['note'])); // Trim to remove empty characters

    // Check if the title and note are not empty
    if (!empty($title) && !empty($note)) {
        // Set the timezone
        date_default_timezone_set("Asia/Manila"); // Replace "Your_Timezone_Here" with a valid timezone identifier, e.g., "America/New_York"

        // Get the current time in 12-hour format
        $time_now = date("Y-m-d g:i:s a");

        // Make SQL query to insert the note
        $query = "INSERT INTO notes(user_id,title,note,time_in) VALUES('$session_id','$title','$note','$time_now')";

        if (mysqli_query($conn, $query)) {
            
        } else {
            // Failure
            echo 'Query error: ' . mysqli_error($conn);
        }
    } else {
        // Empty title or note, display error message
        echo "<script>document.getElementById('titleError').textContent = 'Title and Note cannot be empty';</script>";
    }
}

// Update note as favorite or remove from favorites
if (isset($_GET['favorite'])) {
    $note_id = $_GET['favorite'];
    $select_query = "SELECT favorite FROM notes WHERE note_id = $note_id";
    $result = mysqli_query($conn, $select_query);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $favorite_status = $row['favorite'];
        if ($favorite_status == 1) {
            $update_query = "UPDATE notes SET favorite = 0 WHERE note_id = $note_id";
        } else {
            $update_query = "UPDATE notes SET favorite = 1 WHERE note_id = $note_id";
        }
        if (mysqli_query($conn, $update_query)) {
            // No alert message
            header("Location: notebook.php");
            exit();
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
    }
}

// Selection with optional search
$searchTerm = mysqli_real_escape_string($conn, isset($_GET['search']) ? $_GET['search'] : '');
$isFavorites = isset($_GET['favorites']);
$isArchive = isset($_GET['archive']); // Check if viewing archive
$query = "SELECT note_id, title, note, time_in, favorite FROM notes WHERE user_id = \"$session_id\"";
if ($isFavorites) {
    $query .= " AND favorite = 1";
} elseif (!empty($searchTerm)) {
    // Modify the query to include the search term
    $query .= " AND (title LIKE '%$searchTerm%' OR note LIKE '%$searchTerm%')";
} elseif ($isArchive) {
    $query .= " AND archive = 1"; // Show archived notes
} else {
    $query .= " AND archive = 0"; // Show non-archived notes
}

$notesArray = getNotes($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Notebook | Web Application</title>
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="css/animate.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/font.css" type="text/css">
    <link rel="stylesheet" href="css/app.css" type="text/css">
    <link rel="stylesheet" href="css/stylezz.css" type="text/css">
    
    <style>
        
        body {
    min-height: 100vh; /* Ensure body takes up at least full viewport height */
    width: 100%;
    background-image: url('images/try.jpg');
    background-color: white;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
     /* Make the background image fixed */
}
*{
    margin: 0;
    padding: 0;
}
.container {
    min-height: 100vh; /* Ensure body takes up at least full viewport height */
    width: 100%;
    background-image: url('images/try.jpg');
    background-color: white;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; /* Make the background image fixed */
    transition: background-color 0.3s ease;
     /* Smooth transition for background color change */
}

.container:hover {
    background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white on hover */
}

*{
    margin: 0;
    padding: 0;
}

        .star-glow {
            color: #f8ce0b; /* Yellow color for glowing star */
        }

        /* Styles for the search bar container */
        .searching {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 400px; /* Adjust as needed */
            margin: 0 auto; /* Center the search bar */
        }

        /* Styles for the search input */
        .searchInput {
            background-color: #fff;
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Styles for the search button */
        .search {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
        }

        .search:hover {
            background-color: #0056b3;
        }

        /* Styles for the clear button */
        .clear {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
        }

        .clear:hover {
            background-color: #c82333;
        }

        .form {
            background-color: transparent; /* Set background color to transparent */
            padding: 20px; /* Add padding for better readability */
        }

        .tab-content {
            display: grid;
        }

        .list-group-item {
            margin-top: 20px;
    margin-bottom: 100px;
    position: relative;
    width: 300px;
    height: 250px;
    border-radius: 14px;
    z-index: 1111;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
    background-size: cover;
    padding: 15px; /* Padding inside the card */
    border: 5px solid #78A083;
    transition: transform 0.3s ease; /* Smooth transition for the transform property */
}

.list-group-item:hover {
    transform: scale(1.05); /* Scale up the card by 5% on hover */
}

        
        #noNoteFound {
            display: none;
            color: red;
            position: absolute; /* Ensure message stays in place */
            top: 50%; /* Position at vertical center */
            left: 50%; /* Position at horizontal center */
            transform: translate(-50%, -50%); /* Center the message */
        }

       
        .topnavs a.active:after {
            content: "";
            display: block;
            width: 100%;
            height: 2px;
            background-color: #fff; /* Adjust as needed */
            position: absolute;
            bottom: 0;
            left: 0;
        }
        
    </style>

</head>

<body>
<header class="topnavs" style="background-color: #78A083;">
    <a href="dashboard.php"><span class="navbar-brand left-header">HOME</span></a>
        <a href="notebook.php"><span class="navbar-brand left-header">Notes</span></a>
        <a href="notebook.php?favorites"><span class="navbar-brand left-header">Favorites</span></a>
        <a href="archive.php"><span class="navbar-brand left-header">Archive</span></a>
       <span style="margin: 8px 0 0 5px;" class="thumb-sm avatar pull-left left-header">
            <img src="images/profile.jpg">
        </span>

        <!-- Inside the form -->


       
        <a href="logout.php"><span style="float: right;" class="navbar-brand left-header">Logout</span></a>
        <!-- Search form -->
    </header>

    <form style="margin-bottom: -200px;" class="GET" action="">
        <div class="searching">
            <input type="text" id="searchInput" class="form-control searchInput" placeholder="Search for notes..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" onkeyup="filterNotes()">
            
            <button class="clear" type="button" onclick="clearSearch()">Clear</button>
        </div>
    </form>
    <script>
        function clearSearch() {
            document.location.href = 'notebook.php';
        }
    </script>

    <section style="padding:50px" id="content">
        <section class="hbox stretch">
            <div class="wrapper">
                <form method="POST" id="addNoteForm" onsubmit="validateForm();">
                    <style>
                        .input-sm.form-control,
                        textarea.form-control {
                            resize: none;
                            border: 3px solid #333;
                            padding: 20px;
                            margin-left: 17px;
                            margin-top: 20px;
                        }
                    </style>
                    <h4 class="m-t-none">Add Note</h4>
                    <div class="form-group">
                        <label>Title</label>
                        <input style="margin-bottom: 10px;" name="title" type="text" placeholder="Title" class="input-sm form-control">
                    </div>
                    <div class="form-group">
                        <label style="padding-right: 20px;">Note</label>
                        <textarea style="margin-left: -5px;" name="note" id="note" class="form-control" rows="8" data-minwords="8" data-required="true"></textarea>
                        <p id="noteError" style="color: red; display: none;">Note cannot be empty</p>
                    </div>
                    <div style="margin-left: 70px;" class="m-t-lg">
    <button style="color: #fff; border: none; border-radius: 4px; padding: 8px 16px; cursor: pointer;" class="btn-submit" name="submit" type="submit">Add an event</button>
</div>
   </form>
            </div>
        </section>
    </section>
    <div id="noNoteFound" style="display: none; color: red;">NO NOTE FOUND</div>

    <!-- Adjusted margin for the Favorites container -->
    <div class="container <?php echo $isFavorites ? 'favorites' : ''; ?>">
        <?php foreach ($notesArray as $note) { ?>
            <div class="list-group-item" style="background-color: rgba(255, 255, 255, 0.7); box-shadow: none; color: #333;">           
                <div class="btn-group pull-right">
                    <a href="edit_note.php?edit=<?php echo $note['note_id']; ?>"><button type="button" class="btn btn-sm btn-default" title="Show"><i class="fa fa-eye"></i></button></a>
                    <a href="notebook.php?delete=<?php echo $note['note_id']; ?>"><button type="button" class="btn btn-sm btn-default" title="Remove"><i class="fa fa-trash-o bg-danger"></i></button></a>
                    <a href="notebook.php?favorite=<?php echo $note['note_id']; ?>"><button type="button" class="btn btn-sm btn-default" title="<?php echo ($note['favorite'] == 1) ? 'Remove from Favorites' : 'Mark as Favorite'; ?>"><i class="fa fa-star<?php echo ($note['favorite'] == 1) ? ' star-glow' : ''; ?>"></i></button></a>
                </div>
                <h3 style="text-transform:uppercase;"><b><?php echo $note['title'] ?></b></h3>
                <p><?php echo substr($note['note'], 0, 200) ?></p>
                <small style="color: #333;" class="block text-muted text-info"><i class="fa fa-clock-o text-info"></i> <?php echo $note['time_in'] ?></small>
            </div>
        <?php } ?>
    </div>

    <script>
        function validateForm() {
            console.log("Validation triggered"); // Debugging statement
            var note = document.getElementById("note").value;
            if (note.trim() === "") {
                console.log("Note is empty"); // Debugging statement
                document.getElementById("noteError").style.display = "block";
            } else {
                console.log("Note is not empty"); // Debugging statement
                document.getElementById("noteError").style.display = "none";
                document.getElementById("addNoteForm").submit();
            }
        }

        function filterNotes() {
            var searchTerm = document.getElementById("searchInput").value.toLowerCase();
            var notes = document.querySelectorAll(".list-group-item");
            var found = false;
            notes.forEach(function(note) {
                var title = note.querySelector("h3").textContent.toLowerCase();
                var content = note.querySelector("p").textContent.toLowerCase();
                if (title.includes(searchTerm) || content.includes(searchTerm)) {
                    note.style.display = "block";
                    found = true;
                } else {
                    note.style.display = "none";
                }
            });
            if (!found) {
                document.getElementById("noNoteFound").style.display = "block";
            } else {
                document.getElementById("noNoteFound").style.display = "none";
            }
        }
    </script>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/app.js"></script>
    <script src="js/app.plugin.js"></script>
    <script src="js/libs/underscore-min.js"></script>
    <script src="js/libs/backbone-min.js"></script>
    <script src="js/libs/backbone.localStorage-min.js"></script>
    <script src="js/libs/moment.min.js"></script>
    <script src="js/apps/notes.js"></script>
</body>

</html>
