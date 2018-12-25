<?php
include 'authentication/authentication.php';
if (isset($_GET['do'])) {
    if ($_GET['do'] == 'login') {
        login();
    }
}
if (is_logged_in()) {
    echo '<h1>'. get_rcs() .'</h1>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="css/bootstrap-reboot.min.css">

</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">By Laws</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Submit Request</a>
            </li>
        </ul>
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#">UPAC Sound</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" onclick="" href="index.php?do=login">Log In</a>
            </li>
        </ul>
    </div>
</nav>
<article style="width: 90%;margin: 4% auto;">
    <p>UPAC Sound is a Rensselaer Union-funded, student-run organization, providing live sound reinforcement for the RPI community.</p>
    <p>If you are interested in becoming a member of UPAC Sound, please join us for one of our meetings. We meet biweekly on Mondays at 9PM in Union room 3202. Contact us to find out when the next meeting is.</p>
    <p>If you have any questions, send us an e-mail at the address below!</p>
</article>

<script src="js/bootstrap.bundle.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>