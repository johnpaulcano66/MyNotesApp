<?php
// Include session and configuration files
include('includes/session.php');
include('includes/config.php');

// Handle note deletion
if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $sql = "DELETE FROM notes WHERE note_id = $delete"; // Permanently delete the note
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script type='text/javascript'> document.location = 'archive.php'; </script>";
    }
}

// Function to retrieve archived notes based on query
function getArchivedNotes($conn, $query)
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

// Selection with optional search
$searchTerm = mysqli_real_escape_string($conn, isset($_GET['search']) ? $_GET['search'] : '');
$query = "SELECT note_id, title, note, time_in FROM notes WHERE user_id = \"$session_id\" AND archive = 1";
if (!empty($searchTerm)) {
    // Modify the query to include the search term
    $query .= " AND (title LIKE '%$searchTerm%' OR note LIKE '%$searchTerm%')";
}

$archivedNotes = getArchivedNotes($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Archive | Web Application</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
  <link rel="stylesheet" href="css/animate.css" type="text/css">
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="css/font.css" type="text/css">
  <link rel="stylesheet" href="css/app.css" type="text/css">
  <link rel="stylesheet" href="css/styles.css" type="text/css">
  <style>
        
        body {
    min-height: 100vh; /* Ensure body takes up at least full viewport height */
    width: 100%;
    background-image: url('images/try.jpg');
    background-color: white;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; /* Make the background image fixed */
}
.list-group-item {
    margin-top: 60px;
    margin-left: 30px;
    margin-bottom: -10px;
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
    box-shadow: 10px 10px 30px #bebebe, -20px -20px 60px #ffffff;
    background-size: cover;
    padding: 15px; /* Padding inside the card */
    border: 5px solid #78A083;
    transition: transform 0.3s ease;
}
.list-group-item:hover {
    transform: scale(1.05); /* Scale up the card by 5% on hover */
}
.container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Four columns */
    gap: 20px; /* Adjust the gap between columns */
    padding: 0 20px; /* Equal left and right margins */
    justify-content: space-between; /* Align columns with space between */
    width: calc(100% - 40px); /* Adjust width to account for padding */
    margin: 0 auto; /* Center the container horizontally */
    box-sizing: border-box; /* Include padding in the width calculation */
    min-height: 100vh;
    position: relative;
    overflow-y: hidden;
    
}



*{
    margin: 0;
    padding: 0;
}
.container{
  
    min-height: 100vh; /* Ensure body takes up at least full viewport height */
    width: 100%;
    background-image: url('images/try.jpg');
    background-color: white;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed; /* Make the background image fixed */
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
        <a href="logout.php"><span style="float: right;" class="navbar-brand left-header">Logout</span></a>
        <!-- Search form -->
    </header>

    <form class="GET" action="">
        <div class="searching">
            <input type="text" id="searchInput" class="form-control searchInput" placeholder="Search for notes..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" onkeyup="filterNotes()">
            <button class="clear" type="button" onclick="clearSearch()">Clear</button>
        </div>
    </form>

    <div class="container">
        <?php foreach ($archivedNotes as $note) : ?>
            <div class="list-group-item" style="background-color: rgba(255, 255, 255, 0.9);">
                <!-- Display note content -->
                <h3 style="text-transform: uppercase;"><b><?php echo $note['title'] ?></b></h3>
                <p><?php echo substr($note['note'], 0, 200) ?></p>
                <small class="block text-muted text-info"><i class="fa fa-clock-o text-info"></i> <?php echo $note['time_in'] ?></small>

                <div class="btn-group pull-right">
                    <!-- Unarchive Button -->
                    <a href="unarchive.php?note_id=<?php echo $note['note_id']; ?>">
                        <button type="button" class="btn btn-sm btn-default" title="Unarchive">
                            <i class="fa fa-archive bg-info"></i> Unarchive
                        </button>
                    </a>

                    <!-- Delete Permanently Button -->
                    <a href="archive.php?delete=<?php echo $note['note_id']; ?>">
                        <button type="button" class="btn btn-sm btn-default" title="Permanently Delete">
                            <i class="fa fa-trash-o bg-danger"></i> Delete Permanently
                        </button>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        function clearSearch() {
            document.location.href = 'archive.php';
        }
    </script>
</body>

</html>
