<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "30mm");

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
if ($id == '') {
    die("Invalid request: No ID found");
}

$sql = "SELECT * FROM application WHERE Name = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$msg_type = "";
$msg_text = "";

if (isset($_POST['update'])) {
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $rollno = mysqli_real_escape_string($conn, $_POST['rollno']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $dept   = mysqli_real_escape_string($conn, $_POST['courses']);
    $issue  = mysqli_real_escape_string($conn, $_POST['address']);

    $update_sql = "UPDATE application SET 
        Name='$name',
        Rollno='$rollno',
        Email='$email',
        Department='$dept',
        Grievances='$issue'
        WHERE Name='$id'";

    if (mysqli_query($conn, $update_sql)) {
        $msg_type = "success";
        $msg_text = "Updated successfully!";
    } else {
        $msg_type = "warning";
        $msg_text = "Update failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Application</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="Rutu2.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css">

<!-- ✅ Notiflix -->
<link rel="stylesheet" href="notiflix/notiflix-3.2.7.min.css">
<script src="notiflix/notiflix-3.2.7.min.js"></script>
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="bi bi-journal-text"></i> Grievance Form</h4>
                </div>

                <div class="card-body p-4">
                    <form method="POST" onsubmit="return submitWithLoader();">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="<?php echo htmlspecialchars($row['Name']); ?>" required  readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Roll No</label>
                            <input type="text" name="rollno" class="form-control"
                                   value="<?php echo htmlspecialchars($row['Rollno']); ?>" required readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?php echo htmlspecialchars($row['Email']); ?>" required readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Department</label>
                            <select name="courses" class="form-select" required>
                                <option value="CSE"  <?= $row['Department']=="CSE"?"selected":"" ?>>CSE</option>
                                <option value="ECE"  <?= $row['Department']=="ECE"?"selected":"" ?>>ECE</option>
                                <option value="AIML" <?= $row['Department']=="AIML"?"selected":"" ?>>AIML</option>
                                <option value="DS"   <?= $row['Department']=="DS"?"selected":"" ?>>DS</option>
                                <option value="CY"   <?= $row['Department']=="CY"?"selected":"" ?>>CY</option>
                                <option value="AI"   <?= $row['Department']=="AI"?"selected":"" ?>>AI</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Grievance Details</label>
                            <textarea name="address" class="form-control" rows="5" required><?php
                                echo htmlspecialchars($row['Grievances']);
                            ?></textarea>
                        </div>

                        <button type="submit" name="update" class="btn btn-primary w-100 fw-bold">
                            Update Grievance
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php if ($msg_type != ""): ?>
<script>
    Notiflix.Loading.remove();

    <?php if ($msg_type == "success"): ?>
        Notiflix.Report.success(
            "Success",
            "<?php echo addslashes($msg_text); ?>",
            "OK"
        );

        setTimeout(function () {
            window.location.href = "ani1.php";
        }, 1200);
    <?php else: ?>
        Notiflix.Report.warning(
            "Warning",
            "<?php echo addslashes($msg_text); ?>",
            "OK"
        );
    <?php endif; ?>
</script>
<?php endif; ?>

<script>
function submitWithLoader() {
    Notiflix.Loading.dots('Updating...', {
        backgroundColor: 'rgba(0,0,0,0.6)',
        dotsColor: '#e8dc00'
    });
    return true;
}
</script>

</body>
</html>
