<?php

$conn = mysqli_connect(
    getenv('DB_HOST'),       // Database host
    getenv('DB_USER'),       // Database username (changed from DB_USERNAME)
    getenv('DB_PASSWORD'),   // Database password
    getenv('DB_NAME'),       // Database name (changed from DB_DATABASE)
    getenv('DB_PORT') ?: '3306' // Database port, default to 3306 if not set
);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
