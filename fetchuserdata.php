<?php
include('includes/session.php');
include('includes/config.php');

header('Content-Type: application/json');

// Ensure there is a session or user id to look up
if (!isset($session_id)) {
    echo json_encode(['error' => 'No session found']);
    exit;
}

// Prepare the query to fetch user data
$query = "SELECT fullName, email, other_details FROM register WHERE user_ID = ?";
if ($stmt = $conn->prepare($query)) {
    // Bind the user ID to the prepared statement
    $stmt->bind_param("i", $session_id);

    // Execute the query
    $stmt->execute();

    // Bind the results to variables
    $stmt->bind_result($fullName, $email, $otherDetails);

    // Fetch the results
    if ($stmt->fetch()) {
        // Output the data in JSON format
        echo json_encode([
            'fullName' => $fullName,
            'email' => $email,
            'otherDetails' => $otherDetails
        ]);
    } else {
        // If no data was found, return an error message
        echo json_encode(['error' => 'No user data found']);
    }

    // Close statement
    $stmt->close();
} else {
    // If the query failed to prepare, return an error message
    echo json_encode(['error' => 'Failed to prepare the query']);
}

// Close connection
$conn->close();
?>
