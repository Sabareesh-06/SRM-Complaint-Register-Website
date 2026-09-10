<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action_type'] === 'login') {
        header("Location: Rutu_login.php");
        exit();
    }
    else if ($_POST['action_type'] === 'register') {
        header("Location: Rutu_register.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Website</title>

    <link rel="stylesheet" href="Rutu2.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap/icons/font/bootstrap-icons.css">

    <!-- ✅ Notiflix -->
    <link rel="stylesheet" href="notiflix/notiflix-3.2.7.min.css">
    <script src="notiflix/notiflix-3.2.7.min.js"></script>
</head>
<body>

<form method="post" id="loadingForm">
    <!-- hidden field to tell PHP what was clicked -->
    <input type="hidden" name="action_type" id="action_type">

    <div class="one">
        <div class="card" style="width: 18rem;">
            <img src="images/IIITK _logo.jpg" class="card-img-top img-fluid">

            <div class="card-body text-center">
                <h5 class="card-title">Do you have an account?</h5>

                <!-- ❌ NOT submit -->
                <button type="button" class="btn btn-primary"
                        onclick="runLoading('login')">
                    <i class="bi bi-arrow-right"></i> Login
                </button>

                <!-- <button type="button" class="btn btn-primary"
                        onclick="runLoading('register')">
                    <i class="bi bi-person-check"></i> Register
                </button> -->

            </div>
        </div>
    </div>
</form>

<script>
window.addEventListener('pageshow', function(event) 
{
    if (event.persisted || window.performance.getEntriesByType("navigation")[0].type === "back_forward") 
    {
        Notiflix.Loading.remove();
    }
});

function runLoading(target) {
    // Set hidden input to know what button was clicked
    document.getElementById('action_type').value = target;

    // Custom message for loader
    let loadingMessage = '';
    if(target === 'login') {
        loadingMessage = 'Please wait...';
    } else if(target === 'register') {
        loadingMessage = 'Please wait...';
    }

    Notiflix.Loading.pulse(loadingMessage, {
        backgroundColor: 'rgba(0,0,0,0.6)',
        svgColor: '#c6e400',                          // spinner color
        messageColor: '#ffffff',                      // text color
        messageFontSize: '18px',                      // custom font size
        svgSize: '70px'
    });

     setTimeout(function () {
        document.getElementById('loadingForm').submit();
    }, 800);
}
</script>

</body>
</html>
