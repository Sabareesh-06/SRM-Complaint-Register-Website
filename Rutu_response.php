<?php
session_start();

// Session variables
$role = $_SESSION['role'] ?? '';
$Email = $_SESSION['Email'] ?? '';

// Notiflix messages
$msg_type = $_SESSION['msg_type'] ?? '';
$msg_text = $_SESSION['msg_text'] ?? '';

// Clear messages after fetching
unset($_SESSION['msg_type'], $_SESSION['msg_text']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Responses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="Rutu3.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Notiflix -->
    <link rel="stylesheet" href="notiflix/notiflix-3.2.7.min.css">
    <script src="notiflix/notiflix-3.2.7.min.js"></script>
</head>

<body>
    <div class="container mt-4">

        <button class="btn btn-sm btn-info mb-3" onclick="goBack()">
            <i class="bi bi-arrow-left"></i> Back
        </button>

        <div class="card mb-3">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-9 fw-bold">Application Responses (Pending)</div>
                    <div class="col-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" placeholder="Search...">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card table-wrapper">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Roll No.</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Grievances</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="result">
                    <!-- AJAX data will load here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
    $(document).ready(function() {

        // Load all records on page load
        loadData('');

        // Search button click
        $('#searchBtn').on('click', function() {
            let search = $('#search').val().trim();
            loadData(search);
        });

        // Enter key on search input
        $('#search').on('keyup', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                loadData($(this).val().trim());
            }
        });

        // AJAX function to load table
        function loadData(search) {
            $.ajax({
                url: 'search_pending.php',
                type: 'POST',
                data: { search: search },
                success: function(data) {
                    $('#result').html(data);
                },
                error: function() {
                    $('#result').html('<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>');
                }
            });
        }

    });
    </script>
    <script>
function goBack() {
    // Show Notiflix loading animation
    Notiflix.Loading.arrows('Loading...', {
        backgroundColor: 'rgba(0,0,0,0.6)',
        svgColor: '#c6e400' // optional: change color
    });

    // Redirect after 500ms
    setTimeout(function() {
        Notiflix.Loading.remove(); // remove loader
        window.location.href = 'ani1.php'; // redirect
    }, 500);
}
</script>

    <?php if (!empty($msg_type)): ?>
    <script>
        Notiflix.Report.<?= $msg_type === 'success' ? 'success' : 'warning' ?>(
            "<?= ucfirst($msg_type) ?>",
            "<?= addslashes($msg_text) ?>",
            "OK"
        );
    </script>
    <?php endif; ?>

</body>
</html>
