<?php
// Start the session to access stored variables
session_start();

// Initialize variables from session data, then clear the session data
// Use the null coalescing operator (??) to safely fetch session variables
$msg_type = $_SESSION['msg_type'] ?? '';
$msg_text = $_SESSION['msg_text'] ?? '';

// Clear the session variables so the notification doesn't show on refresh
unset($_SESSION['msg_type']);
unset($_SESSION['msg_text']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Try again</title>

    <link rel="stylesheet" href="dummy.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="bootstrap-icons.woff">
    <link rel="stylesheet" href="./bootstrap/css/notiflix-3.2.8.min.css">
    <script src="bootstrap/notiflix-3.2.8.min/notiflix-3.2.8.min.js"></script>
</head>
<body>

<div class="container mt-5 text-center">
    <h2>Login Failed</h2>
    <p>Please return to the login page and try again.</p>
    <a href="login.php" class="btn btn-primary">Go to Login</a>
</div>

<?php if (!empty($msg_type)) 
{ ?>
<script>
Notiflix.Report.<?php echo ($msg_type === 'success') ? 'success' : 'warning'; ?>
(
    '<?php echo ($msg_type === "success") ? "Success" : "Warning"; ?>',
    '<?php echo addslashes($msg_text); ?>',
    'OK'
);
</script>
<?php } ?>

</body>
</html>