<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "30mm");

if (isset($_GET['name']) && $_SESSION['role'] == 'admin') {
    $name = mysqli_real_escape_string($conn, $_GET['name']);
    $sql = "DELETE FROM application WHERE Name = '$name'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg_type'] = 'success';
        $_SESSION['msg_text'] = "Record erased successfully.";
    } else {
        $_SESSION['msg_type'] = 'error';
        $_SESSION['msg_text'] = "Error erasing record.";
    }
}
header("Location: ani1.php");
exit();
?>