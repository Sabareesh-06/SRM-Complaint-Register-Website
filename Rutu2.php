<?php
session_start();
// SELECT `ID`, `Name`, `Email`, `DOB`, `Username`, `Password`, `role`, `active` FROM `register` WHERE 1
$Name1 = $_SESSION['Name'] ;
$Email1 = $_SESSION['Email'] ;
$Username1 = $_SESSION['Username'] ;

// Get messages
$msg_type = $_SESSION['msg_type'] ?? '';
$msg_text = $_SESSION['msg_text'] ?? '';

$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['msg_type'], $_SESSION['msg_text'], $_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Grievance Form</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="Rutu2.css">
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./bootstrap/js/notiflix-3.2.8.min.js"></script>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="mb-3">
            <button type="button" class="btn btn-sm btn-info" onclick="history.back()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0"><i class="bi bi-journal-text"></i> Grievance Form</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="submit_grievance.php"> <!-- ✅ Point to new file -->

                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="bi bi-person-fill"></i> Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="<?php echo htmlspecialchars($form_data['name'] ?? $Name1); ?>"
                                    required placeholder="Enter full name" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="bi bi-grip-horizontal"></i> Roll No</label>
                                <input type="text" name="rollno" class="form-control" maxlength="20"
                                    value="<?php echo htmlspecialchars($form_data['rollno'] ?? $Username1); ?>"
                                    required placeholder="Enter roll number" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="bi bi-envelope-at-fill"></i> Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="<?php echo htmlspecialchars($form_data['email'] ?? $Email1); ?>"
                                    required placeholder="example@srmist.edu.in" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="bi bi-building"></i> Department</label>
                                <select name="dept" class="form-select" required>
                                    <option value="" disabled selected hidden>-- Choose Department --</option>
                                    <option value="CSE" <?= (isset($form_data['dept']) && $form_data['dept'] === 'CSE') ? 'selected' : ''; ?>>
                                        COMPUTER SCIENCE ENGINEERING
                                    </option>
                                    <option value="ECE" <?= (isset($form_data['dept']) && $form_data['dept'] === 'ECE') ? 'selected' : ''; ?>>
                                        ELECTRONIC AND COMMUNICATION ENGINEERING
                                    </option>

                                    <option value="AIML" <?= (isset($form_data['dept']) && $form_data['dept'] === 'AIML') ? 'selected' : ''; ?>>
                                        ARTIFICIAL INTELLIGENCE & MACHINE LEARNING
                                    </option>

                                    <option value="DS" <?= (isset($form_data['dept']) && $form_data['dept'] === 'DS') ? 'selected' : ''; ?>>
                                        DATA SCIENCE
                                    </option>

                                    <option value="CY" <?= (isset($form_data['dept']) && $form_data['dept'] === 'CY') ? 'selected' : ''; ?>>
                                        CYBER SECURITY
                                    </option>

                                    <option value="AI" <?= (isset($form_data['dept']) && $form_data['dept'] === 'AI') ? 'selected' : ''; ?>>
                                        ARTIFICIAL INTELLIGENCE
                                    </option>
                                </select>

                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="bi bi-chat-left-text-fill"></i> Grievance Details</label>
                                <textarea name="address" class="form-control" rows="5" required><?php echo htmlspecialchars($form_data['address'] ?? ''); ?></textarea>
                            </div>

                            <button type="submit" name="demo" class="btn btn-primary w-100 py-2 fw-bold">
                                Submit Grievance <i class="bi bi-patch-check-fill ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($msg_type): ?>
        <script>
            Notiflix.Report.<?php echo $msg_type === 'success' ? 'success' : 'warning'; ?>(
                '<?php echo $msg_type === "success" ? "Success" : "Warning"; ?>',
                '<?php echo addslashes($msg_text); ?>',
                'OK'
            );
        </script>
    <?php endif; ?>

</body>

</html>