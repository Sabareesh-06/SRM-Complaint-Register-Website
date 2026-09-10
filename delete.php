<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['role'])) {
    exit("Unauthorized access"); 
}

$conn = mysqli_connect("localhost", "root", "", "30mm");
if (!$conn) { exit("DB Error"); }

// Logged-in user info (same variables as you have)
$role = $_SESSION['role'];
$Email1 = $_SESSION['Email'];  // user email stored at login

// 2. Capture ID (Name in your code)
$id = "";
if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
} elseif (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
}

// 3. Execute Delete if ID exists
if (!empty($id)) {
    if ($role === 'admin') {
        // Admin can delete any record
        $sql = "DELETE FROM application WHERE Name='$id'";
    } else {
        // Normal user can delete ONLY their own record
        $sql = "DELETE FROM application WHERE Name='$id' AND Email='$Email1'";
    }
    mysqli_query($conn, $sql);

    // Notiflix message
    $_SESSION['msg_type'] = 'success';
    $_SESSION['msg_text'] = 'Record deleted successfully!';
}

// 4. Redirect back
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER["HTTP_REFERER"]);
} else {
    header("Location: ani1.php"); 
}
exit;
?>
