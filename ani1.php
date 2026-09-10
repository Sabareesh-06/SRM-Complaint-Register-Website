<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "30mm");
if (!$conn) exit("DB Error");

$msg_type = $_SESSION['msg_type'] ?? '';
$msg_text = $_SESSION['msg_text'] ?? '';

/* REGISTER HANDLER */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_action'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $DOB      = trim($_POST['DOB']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($name == "" || $email == "" || $DOB == "" || $username == "" || $password == "") {
        $msg_type = "warning";
        $msg_text = "All fields are required!";
    } else {

        /* CHECK USERNAME */
        $check = mysqli_prepare($conn, "SELECT id FROM register WHERE username = ?");
        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $msg_type = "warning";
            $msg_text = "Username already exists!";
        } else {

            /* INSERT */
            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO register (name, email, DOB, username, password)
                 VALUES (?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($insert, "sssss", $name, $email, $DOB, $username, $password);

            if (mysqli_stmt_execute($insert)) {
                $msg_type = "success";
                $msg_text = "Account created successfully!";
                $close_modal = true;
            } else {
                $msg_type = "warning";
                $msg_text = "Registration failed!";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceed</title>

    <link rel="stylesheet" href="Rutu3.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="notiflix/notiflix-3.2.7.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="notiflix/notiflix-3.2.7.min.js"></script>
</head>

<body>

    <button class="btn btn-sm btn-danger m-2" onclick="backWithLoader('Rutu1.php')">
        <i class="bi bi-arrow-left"></i> Logout
    </button>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h2>Welcome</h2>
                <p>Click register to open modal</p>
            </div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'user') { ?>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#registerModal">
            <i class="bi bi-person-plus-fill"></i> Register
        </button>
    </div>
<?php } ?>


        </div>


        <p>
            <a href="Rutu_response.php" onclick="runLoadingAndRedirect('Rutu_response.php'); return false;" class="link-info link-offset-2">
                <i class="bi bi-eye-fill"></i> View Pending Grievances
            </a>
        </p>

        <?php if ($_SESSION['role'] != 'admin') { ?>
            <p>
                <a href="Rutu2.php" onclick="runLoadingAndRedirect('Rutu2.php'); return false;" class="link-underline-info">
                    <i class="bi bi-file-arrow-up-fill"></i> File New Grievance
                </a>
            </p>
        <?php } ?>
    </div>

    <div class="container mt-4">
        <div class="card mb-3">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-9 fw-bold">Grievances Full Filled (Resolved)</div>
                    <div class="col-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" placeholder="Search...">
                            <button class="btn btn-primary" id="searchBtn" type="button"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card table-wrapper">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Grievances</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="result">
                </tbody>
            </table>
        </div>
    </div>
    <!-- REGISTER MODAL -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="register_action" value="1">

                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>DOB</label>
                            <input type="date" name="DOB" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="pass" class="form-control" required>
                                <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer">
                                    <i class="bi bi-eye-fill" id="eyeIcon"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Register
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            function loadData(search = '') {
                $.ajax({
                    url: 'search_resolved.php',
                    type: 'POST',
                    data: {
                        search: search
                    },
                    success: function(data) {
                        $('#result').html(data);
                    },
                    error: function() {
                        $('#result').html('<tr><td colspan="6" class="text-center text-danger">Error loading data</td></tr>');
                    }
                });
            }

            loadData();

            $('#searchBtn').on('click', function() {
                loadData($('#search').val().trim());
            });

            $('#search').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    loadData($(this).val().trim());
                }
            });

            // Delete action using delegation
            $('#result').on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id'); // Using data-id to match modern delete.php logic

                Notiflix.Confirm.show(
                    'Confirm Delete',
                    'Are you sure you want to delete this grievance?',
                    'Yes', 'No',
                    function() {
                        Notiflix.Loading.dots('Deleting...');
                        $.ajax({
                            url: 'delete.php',
                            type: 'POST',
                            data: {
                                id: id
                            },
                            success: function(res) {
                                Notiflix.Loading.remove();
                                // This handles if delete.php returns JSON or redirects
                                try {
                                    const response = JSON.parse(res);
                                    if (response.status === 'success') {
                                        Notiflix.Notify.success('Grievance Deleted!');
                                        loadData();
                                    } else {
                                        Notiflix.Report.failure('Error', response.message, 'OK');
                                    }
                                } catch (err) {
                                    location.reload(); // Refresh if no JSON returned
                                }
                            }
                        });
                    }
                );
            });
        });

        function runLoadingAndRedirect(url) {
            Notiflix.Loading.dots('Please wait...',{
                backgroundColor: 'rgba(0,0,0,0.6)',
                svgColor: '#c6e400'
            });
            setTimeout(function() {
                window.location.href = url;
            }, 700);
        }

        function togglePassword() {
            let pass = document.getElementById("pass");
            let icon = document.getElementById("eyeIcon");
            if (pass.type === "password") {
                pass.type = "text";
                icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
            } else {
                pass.type = "password";
                icon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
            }
        }
    </script>

    <script>
function backWithLoader(url) {
    // Show Notiflix loading animation
    Notiflix.Loading.circle('Loading...', {
        backgroundColor: 'rgba(0,0,0,0.6)',
        svgColor: '#c6e400' // optional: loader color
    });

    // Redirect after 600ms
    setTimeout(function() {
        Notiflix.Loading.remove(); // remove loader
        window.location.href = url; // redirect
    }, 600);
}
</script>

    <?php if ($msg_type != ""): ?>
        <script>
            Notiflix.Report.<?= ($msg_type === 'success') ? 'success' : 'warning' ?>(
                "<?= ucfirst($msg_type) ?>",
                "<?= addslashes($msg_text) ?>",
                "OK"
            );

            <?php if ($close_modal): ?>
                let modal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                if (modal) modal.hide();
            <?php endif; ?>
        </script>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <script>
            Notiflix.Report.success(
                "Success",
                "Status updated successfully!",
                "OK",
                function() {
                    // ✅ Redirect AFTER OK click
                    window.location.href = "ani1.php";
                }
            );
        </script>
    <?php endif; ?>


</body>

</html>