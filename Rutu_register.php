<?php
$cn30 = mysqli_connect("localhost", "root", "", "30mm");
if (!$cn30) {
    die("Connection failed: " . mysqli_connect_error());
}

$msg_type = "";
$msg_text = "";
$redirect = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_action'])) {

    $name     = trim($_POST['name'] ?? "");
    $email    = trim($_POST['email'] ?? "");
    $DOB      = trim($_POST['DOB'] ?? "");   
    $username = trim($_POST['username'] ?? "");
    $password = trim($_POST['password'] ?? "");

    if (empty($name) || empty($email) || empty($DOB) || empty($username) || empty($password)) {
        $msg_type = "warning";
        $msg_text = "All fields are required!";
    } else {
        // Check if username exists
        $check = mysqli_prepare($cn30, "SELECT username FROM register WHERE username = ?");
        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $msg_type = "warning";
            $msg_text = "Username already exists!";
        } else {
            // INSERT (Ensure column names match your DB exactly)
            $insert = mysqli_prepare($cn30, "INSERT INTO register (name, email, DOB, username, password) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "sssss", $name, $email, $DOB, $username, $password);

            if (mysqli_stmt_execute($insert)) {
                $msg_type = "success";
                $msg_text = "Account created successfully!";
                $redirect = true;
            } else {
                $msg_type = "warning";
                $msg_text = "Registration failed: " . mysqli_error($cn30);
            }
            mysqli_stmt_close($insert);
        }
        mysqli_stmt_close($check);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="Rutu2.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="notiflix/notiflix-3.2.7.min.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="notiflix/notiflix-3.2.7.min.js"></script>
</head>
<body>

<div class="image-wrapper2">
    <div class="container">
        <form method="post" class="center-box" id="regForm">
            <input type="hidden" name="register_action" value="1">
            
            <div class="card p-4">
                <h3 class="text-center mb-4">Register <i class="bi bi-person-plus-fill"></i></h3>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-envelope-at-fill"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    <input type="date" class="form-control" name="DOB" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="password" class="form-control" name="password" id="pass" placeholder="Password" required>
                    <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer">
                        <i class="bi bi-eye-fill" id="eyeIcon"></i>
                    </span>
                </div>

                <button type="button" class="btn btn-primary w-100" onclick="handleRegister()">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("pass");
    const icon = document.getElementById("eyeIcon");
    if (pass.type === "password") {
        pass.type = "text";
        icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
    } else {
        pass.type = "password";
        icon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
    }
}

function handleRegister() {
    const form = document.getElementById("regForm");
    if(!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // TRIGGER NOTIFLIX LOADING
    Notiflix.Loading.standard('Saving Data...', {
        backgroundColor: 'rgba(0,0,0,0.8)',
    });

    setTimeout(() => {
        form.submit();
    }, 1000);
}
</script>

<?php if ($msg_type != ""): ?>
<script>
    Notiflix.Loading.remove();
    Notiflix.Report.<?php echo $msg_type; ?>(
        "<?php echo ucfirst($msg_type); ?>",
        "<?php echo $msg_text; ?>",
        "OK",
        function() {
            <?php if ($redirect): ?> window.location.href = "Rutu_login.php"; <?php endif; ?>
        }
    );
</script>
<?php endif; ?>

</body>
</html>