<?php
session_start();
$cn30 = mysqli_connect("localhost", "root", "", "30mm");

if (!$cn30) { die("DB Error"); }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['demo'])) {
    $name       = trim($_POST['name'] ?? "");
    $rollno     = trim($_POST['rollno'] ?? ""); 
    $email      = trim($_POST['email'] ?? "");
    $department = trim($_POST['dept'] ?? "");
    $grievances = trim($_POST['address'] ?? "");

    if ($name === "" || $rollno === "" || $email === "") {
        $_SESSION['msg_type'] = "warning";
        $_SESSION['msg_text'] = "Required fields are missing!";
        header("Location: Rutu2.php");
        exit();
    }

    $insert = mysqli_prepare($cn30, "INSERT INTO application(name, rollno, email, department, grievances, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    mysqli_stmt_bind_param($insert, "sssss", $name, $rollno, $email, $department, $grievances);
    
    // Use a try-catch to stop the "Fatal Error" crash
    try {
        if (mysqli_stmt_execute($insert)) {
            $_SESSION['msg_type'] = "success";
            $_SESSION['msg_text'] = "Grievance submitted successfully!";
            header("Location: Rutu_response.php");
        }
    } catch (mysqli_sql_exception $e) {
        // If the error code is 1062 (Duplicate entry)
        if ($e->getCode() == 1062) {
            $_SESSION['msg_type'] = "warning";
            $_SESSION['msg_text'] = "Error: This Roll Number has already submitted a grievance.";
        } else {
            $_SESSION['msg_type'] = "warning";
            $_SESSION['msg_text'] = "Database Error: " . $e->getMessage();
        }
        header("Location: Rutu2.php");
    }
    
    mysqli_stmt_close($insert);
    exit();
}
?>