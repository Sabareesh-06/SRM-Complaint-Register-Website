<?php
session_start();

$cn30 = mysqli_connect("localhost", "root", "", "30mm");
if (!$cn30) {
    die("Connection failed: " . mysqli_connect_error());
}

$msg_type = "";
$msg_text = "";
$login_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_action'])) {

    $username = trim($_POST['uname'] ?? "");
    $password = trim($_POST['password'] ?? ""); // ✅ FIXED

    if ($username !== "" && $password !== "") {

        $query = "SELECT `ID`, `Name`, `Email`, `DOB`, `Username`, `Password`, `role`, `active` FROM register WHERE Username = ? AND Password = ?";
        $stmt = mysqli_prepare($cn30, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                $_SESSION['Username'] = $row['Username'];
                $_SESSION['Name'] = $row['Name'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['DOB'] = $row['DOB'];
                $_SESSION['role'] = $row['role'] ?? 'user';

                $login_success = true;
            } else {
                $msg_type = "warning";
                $msg_text = "Invalid Username or Password!";
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        $msg_type = "warning";
        $msg_text = "Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Page</title>

    <link rel="stylesheet" href="Rutu2.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="notiflix/notiflix-3.2.7.min.css">

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="notiflix/notiflix-3.2.7.min.js"></script>
</head>

<body>

    <div class="image-wrapper">
        <div class="container">

            <form method="post" class="center-box" id="loginForm">
                <input type="hidden" name="login_action" value="1">

                <div class="card">
                    <div class="card-body">

                        <div class="card text-center mb-3">
                            <div class="two">
                                <div class="card-body fw-bold">
                                    LogIn <i class="bi bi-arrow-right-circle-fill"></i>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="bi bi-person-circle"></i>
                            </span>
                            <input type="text" class="form-control" name="uname" placeholder="Roll Number" required>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" class="form-control" name="password" id="pass" placeholder="Password" required>
                            <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer">
                                <i class="bi bi-eye-fill" id="eyeIcon"></i>
                            </span>
                        </div>

                        <button type="button" class="btn btn-primary w-100" onclick="startLogin()">
                            Log In
                        </button>

                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
        function startLogin() {
            const form = document.getElementById('loginForm');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            Notiflix.Loading.hourglass('Verifying Account...', {
                backgroundColor: 'rgba(0,0,0,0.8)',
                svgColor: '#c6e400'
            });

            setTimeout(() => {
                form.submit();
            }, 800);
        }

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
    </script>

    <?php if ($login_success): ?>
        <script>
            Notiflix.Loading.remove();
            Notiflix.Report.success(
                "Login Successful",
                "Welcome back! Redirecting...",
                "OK",
                function() {
                    window.location.replace("ani1.php");
                }
            );
        </script>
    <?php endif; ?>

    <?php if ($msg_type !== ""): ?>
        <script>
            Notiflix.Loading.remove();
            Notiflix.Report.failure(
                "Incorrect",
                "<?php echo addslashes($msg_text); ?>",
                "OK"
            );
        </script>
    <?php endif; ?>

</body>

</html>