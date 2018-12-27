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
$current = "home";
if (isset($_GET['component'])) {
    $current = $_GET['component'];
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
    <link rel="stylesheet" href="css/main.css">

</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php?component=home">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?component=services">Services</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?component=bylaws">By Laws</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?component=submit_request">Submit Request</a>
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
<div class="page__">
<?php

//include

if (@include 'pages/'.$current . '.php') {

} else {
    include 'pages/home.php';
}

?>
</div>

<script src="js/bootstrap.bundle.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>