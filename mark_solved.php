<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "30mm");

// Security Check: Only admins should access this logic
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    // Validate allowed status transitions
    $allowed_statuses = ['resolved', 'denied'];
    
    if (in_array($status, $allowed_statuses)) {
        // Update the status. It stays in the table but changes state.
       $sql = "UPDATE application SET status = '$status' WHERE Name = '$id'";
        
        if (mysqli_query($conn, $sql)) {
            // Redirect back to the dashboard with a success message 
            header("Location: ani1.php?msg=updated");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
}
?>