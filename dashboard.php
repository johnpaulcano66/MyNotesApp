<?php
include('includes/session.php');
include('includes/config.php');

function getNotes($conn, $query)
{
    if (mysqli_query($conn, $query)) {
        $result = mysqli_query($conn, $query);
        $notesArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $notesArray;
    } else {
        echo 'Query error: ' . mysqli_error($conn);
    }
}

$query = "SELECT note_id, title, note FROM notes";
$notes = getNotes($conn, $query);

function getUserFullName($conn, $userId)
{
    $query = "SELECT fullName FROM register WHERE user_ID = '$userId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['fullName'];
    } else {
        return "User Not Found";
    }
}

$userFullName = getUserFullName($conn, $session_id);

$overallNotesQuery = "SELECT COUNT(*) AS total_notes FROM notes WHERE user_id = '$session_id'";
$totalNotesResult = mysqli_query($conn, $overallNotesQuery);
$totalNotesRow = mysqli_fetch_assoc($totalNotesResult);
$totalNotes = $totalNotesRow['total_notes'];

$query = "SELECT image FROM register WHERE user_id = '$session_id'";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $profile_image_path = $row['image'];
} else {
    $profile_image_path = 'default_profile_image.jpg';
}

function createDirectory($directory)
{
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
}

$destination_directory = 'uploads/profile_images/';

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['profile_image']['tmp_name'];
    $file_name = basename($_FILES['profile_image']['name']);
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = uniqid('profile_') . '.' . $file_extension;
    $destination = $destination_directory . $new_file_name;

    createDirectory($destination_directory);

    if (move_uploaded_file($tmp_name, $destination)) {
        $profile_image_path = $destination;
        $update_query = "UPDATE register SET image = '$profile_image_path' WHERE user_id = '$session_id'";
        if (mysqli_query($conn, $update_query)) {
            // Success
        } else {
            echo "Error updating profile image path in the database: " . mysqli_error($conn);
        }
    } else {
        echo "Error uploading profile image";
    }
}

if (isset($_GET['delete_profile'])) {
    $update_query = "UPDATE register SET image = NULL WHERE user_id = '$session_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting profile picture from the database: " . mysqli_error($conn);
    }
}

if (isset($_GET['delete'])) {
    $delete = $_GET['delete'];
    $sql = "UPDATE notes SET archive = 1 WHERE note_id = $delete";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $delete_favorite_sql = "UPDATE notes SET favorite = 0 WHERE note_id = $delete";
        $result_favorite = mysqli_query($conn, $delete_favorite_sql);
        if ($result_favorite) {
            header("Location: notebook.php");
            exit();
        } else {
            echo "Error deleting note from favorites: " . mysqli_error($conn);
        }
    } else {
        echo "Error archiving note: " . mysqli_error($conn);
    }
}

if (isset($_GET['archive'])) {
    $archive = $_GET['archive'];
    $sql = "UPDATE notes SET archive = 0 WHERE note_id = $archive";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("Location: notebook.php");
        exit();
    }
}

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
            header("Location: notebook.php");
            exit();
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
    }
}

$searchTerm = mysqli_real_escape_string($conn, isset($_GET['search']) ? $_GET['search'] : '');
$isFavorites = isset($_GET['favorites']);
$isArchive = isset($_GET['archive']);
$query = "SELECT note_id, title, note, time_in, favorite FROM notes WHERE user_id = \"$session_id\"";
if ($isFavorites) {
    $query .= " AND favorite = 1";
} elseif (!empty($searchTerm)) {
    $query .= " AND (title LIKE '%$searchTerm%' OR note LIKE '%$searchTerm%')";
} elseif ($isArchive) {
    $query .= " AND archive = 1";
} else {
    $query .= " AND archive = 0";
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
    background-attachment: fixed; /* Make the background image fixed */
}
*{
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    min-height: 100vh;
    position: relative;
    float: right;
    overflow-y: hidden;
}
.container {
    display: grid;
    grid-template-columns: repeat(4, 2fr); /* Four columns */
    gap: 20px; /* Adjust the gap between columns */
    justify-content: space-between; /* Align columns with space between */
    padding: 0 20px; /* Equal left and right margins */
   
    
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
*{
    margin: 0;
    padding: 0;
}

        


       .container{
text-align: center;
        color: black;
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

    

    <!-- Display the profile image -->
    
</div><?php if(empty($profile_image_path)): ?>
    <div style="display: flex; flex-direction: column; align-items: center; margin: 8px 740px 0 0; float:right; cursor: pointer;" class="thumb-sm avatar pull-left left-header" id="profileImageContainer">
        <p>Profile Picture not set up</p>
        <form id="profileImageForm" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="file" name="profile_image" accept="image/*" onchange="uploadProfileImage(this)">
            
        </form>
    </div>
<?php else: ?>
    <div style="margin: 8px 5px 0 0; float:right; cursor: pointer;" class="thumb-sm avatar pull-left left-header" id="profileImageContainer">
    <a href="changeprofile.php"> <!-- Add link to changeprofile.php -->
        <img style="float: right; max-width: 100px; max-height: 100px; margin: 100px 700px 0 0" src="<?php echo $profile_image_path; ?>" id="profileImage" onclick="toggleOptions()">
    </a>
    <div id="profileOptions" style="display: none; text-align: center;">
        <button style="margin: 8px 700px 0 0; float:right; cursor: pointer;" type="button" onclick="changeProfile()">Change</button>
        <button style="margin: 8px 700px 0 0; float:right; cursor: pointer;" type="button" onclick="deleteProfile()">Delete</button>
    </div>
</div>


<!-- Your HTML content -->
<div id="profilePopup" style="display: none;">
    <form id="editProfileForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" novalidate>
        <!-- Display current profile details -->
        <label for="fullName">Full Name:</label>
        <input type="text" id="fullName" name="fullName" value="<?php echo $userFullName; ?>"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $userEmail; ?>"><br><br>
        <!-- Add more fields as needed -->

        <!-- Submit button to update profile -->
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
    <button onclick="closeProfilePopup()">Close</button>
</div>
<!-- Your existing HTML content continues here -->




<!-- JavaScript to control the pop-up form -->
<script>
    // Function to open the pop-up form
    function openProfilePopup() {
        document.getElementById('profilePopup').style.display = 'block';
    }

    // Function to close the pop-up form
    function closeProfilePopup() {
        document.getElementById('profilePopup').style.display = 'none';
    }
</script>


<?php endif; ?>
<script>
    function uploadProfileImage(input) {
        var file = input.files[0];
        if (file) {
            var formData = new FormData();
            formData.append('profile_image', file);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Profile picture uploaded successfully, reload the page
                        location.reload();
                    } else {
                        // Error occurred while uploading profile picture
                        alert('Error occurred while uploading profile picture.');
                    }
                }
            };
            xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>');
            xhr.send(formData);
        }
    }

    function cancelUpload() {
        // Implement cancel upload logic here if needed
    }
</script>



<script>
    function toggleOptions() {
        var profileOptions = document.getElementById('profileOptions');
        if (profileOptions.style.display === 'none') {
            profileOptions.style.display = 'block';
        } else {
            profileOptions.style.display = 'none';
        }
    }
    function changeProfile() {
    var fileInput = document.querySelector('input[type="file"]');
    var formData = new FormData();
    formData.append('profile_image', fileInput.files[0]);

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Profile picture changed successfully, update the displayed image
                document.getElementById('profileImage').src = xhr.responseText;
                alert('Profile picture changed successfully.');
            } else {
                // Error occurred while changing profile picture
                alert('Error occurred while changing profile picture.');
            }
        }
    };
    xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>');
    xhr.send(formData);
}


    function deleteProfile() {
        // Implement the logic to delete the profile picture
        var confirmation = confirm("Are you sure you want to delete your profile picture?");
        if (confirmation) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Profile picture deleted successfully, update the displayed image
                        document.getElementById('profileImage').src = xhr.responseText;
                        alert('Profile picture deleted successfully.');
                    } else {
                        // Error occurred while deleting profile picture
                        alert('Error occurred while deleting profile picture.');
                    }
                }
            };
            xhr.open('POST', '<?php echo $_SERVER['PHP_SELF']; ?>?delete_profile=1');
            xhr.send();
        }
    }

    function cancelUpload() {
        var uploadForm = document.getElementById('profileImageForm');
        var profileImage = document.getElementById('profileImage');

        // Toggle display of the upload form
        if (uploadForm.style.display === 'block') {
            uploadForm.style.display = 'none';
            profileImage.style.display = 'block'; // Show profile image when form is hidden
        }
    }
</script>




<script>
    function cancelUpload() {
        var uploadForm = document.getElementById('profileImageForm');
        var profileImage = document.getElementById('profileImage');

        // Toggle display of the upload form
        if (uploadForm.style.display === 'block') {
            uploadForm.style.display = 'none';
            profileImage.style.display = 'block'; // Show profile image when form is hidden
        }
    }
</script>


    <div class="container greeting-container card notification"  style="display: block; color:#333; margin: 50px 0 0 0; font-size:larger">
 <div class="greeting-wrapper" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; ">
        <h2>Hello <?php echo $userFullName; ?>!</h2>
        <p>You have <?php echo $totalNotes; ?> notes in your notebook.</p>
    </div>
    <?php if ($totalNotes == 0): ?>
        <div class="greeting-wrapper" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <p>It seems your notebook is empty. Why not add some notes?</p>
        </div>
    <?php elseif ($totalNotes < 5): ?>
        <div class="greeting-wrapper" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <p>Your notebook is looking a bit sparse. Keep adding more notes!</p>
        </div>
    <?php else: ?>
        <div class="greeting-wrapper" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <p>Your notebook is full of ideas and memories. Keep up the good work!</p>
        </div>
    <?php endif; ?>
</div>

    <!-- Your PHP and HTML content -->
    
<!-- JavaScript to toggle the display of the profile image upload form -->
<script>
        document.getElementById('profileImageContainer').addEventListener('click', function() {
            var uploadForm = document.getElementById('profileImageForm');
            var profileImage = document.getElementById('profileImage');

            // Toggle display of the upload form
            if (uploadForm.style.display === 'none') {
                uploadForm.style.display = 'block';
                profileImage.style.display = 'none'; // Hide profile image when form is shown
            } else {
                uploadForm.style.display = 'none';
                profileImage.style.display = 'block'; // Show profile image when form is hidden
            }
        });
    </script>







    
   
   

    

</body>

</html>
