<?php
include 'authentication/authentication.php';
require 'config pages/config.php';
/**
 * @effects tests if the user wants to log in or log out then proceeds accordingly
 */
if (isset($_GET['do'])) {
    if ($_GET['do'] == 'login') {
        login();
    } else if ($_GET['do'] == 'logout') {
        logout();
    }
}

$current = "new_user";
if (isset($_GET['component'])) {
    $current = $_GET['component'];
}
if (auth("new_user") && !auth("general_user") && $current != "new_user" && $current != "welcome_page") {

    $message = "Please fill out this form. Thanks :) (DEBUG: " . $current . ")";
    header("Location: index.php?component=new_user&message=".$message);
}
else if (!isset($_GET['component']) && !(auth("new_user") && !auth("general_user"))){
    $current = "home";
}
if ($current == 'last') {
    if (isset($_SESSION['last']) && false)
        $current = $_SESSION['last'];
    else
        $current = "home";
}

/**
 * @Effects sends user back to new_user page to re request information, then kills the current page
 */
function invalid_data($component,$message = null) {


    $message = $message != null ? $message : "You must fill out all of the required fields with valid information";
    $url = "index.php?component={$component}&danger=true&message=$message";
    header("Location: {$url}");
    echo '<script type="text/javascript">';
    echo 'window.location.href="'.$url.'";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
    echo '</noscript>';
    exit;

    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UPAC Sound</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="libraries/Notification_bar/rsalert.min.css">
    <link rel="stylesheet" href="css/main.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="libraries/Notification_bar/rsalert.min.js"></script>

    <script src="js/main.js"></script>
    <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=pages/deadpage.html">
    </noscript>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <?php if (!($current == "new_user")): ?>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php if ($current == "home"): ?>active<?php endif ?>">
                    <a class="nav-link" href="index.php?component=home">Home</a>
                </li>
                <li class="nav-item <?php if ($current == "services"): ?>active<?php endif ?>">
                    <a class="nav-link" href="index.php?component=services">Services</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle  <?php if ($current == "submit_request"): ?>active<?php endif ?>"
                       href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="true">
                        Submit Request
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="index.php?component=show_request">Show Request (if you wish to
                            hire technicians)</a>
                        <a class="dropdown-item" href="index.php?component=rental_request">Rental Request (if you DO NOT
                            wish to hire technicians)</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if ($current == "bylaws" || $current == "policies"): ?> active<?php endif ?>"
                       href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="true">
                        Legal
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="index.php?component=policies">Policies, Terms, and Conditions</a>
                        <a class="dropdown-item" href="index.php?component=bylaws">By Laws</a>
                    </div>
                </li>
                <?php if (auth("dev")): ?>
                    <li class="nav-item <?php if ($current == "requests"): ?>active<?php endif ?>">
                        <a class="nav-link" href="index.php?component=requests">My Requests</a>
                    </li>
                <?php endif ?>
            </ul>
        <?php endif ?>
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="index.php?component=home">UPAC Sound</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <?php if (auth("chair")): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        View Shows
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="index.php?component=manage&type=shows&status=0">Pending Requests</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=shows&status=1">Rejected</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=shows&status=2">Cancelled</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=shows&status=3">Upcoming</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=shows&status=4">Completed (Awaiting payroll)</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=shows&status=5">Archived</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        View Rentals
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="index.php?component=manage&type=rentals&status=0">Pending Requests</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=rentals&status=1">Rejected</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=rentals&status=2">Cancelled</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=rentals&status=3">Upcoming</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=rentals&status=5">Archived</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Administration
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="index.php?component=manage&type=organizations">Manage Organizations</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=users&order_r=rank">Manage Users</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=venues">Manage Venues</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=inventory">Manage Inventory</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=rigs">Manage rigs</a>
                        <a class="dropdown-item" href="index.php?component=manage&type=payroll">Payroll</a>

                    </div>
                </li>
            <?php endif ?>

            <?php if (!auth("new_user")): ?>
                <li class="nav-item">
                    <a class="nav-link" onclick="" href="index.php?do=login">Log In</a>
                </li>
            <?php endif ?>
            <?php if (auth("general_user")): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Welcome, <?php echo get_first_name(); ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="index.php?do=logout">Log Out</a>
                    </div>
                </li>
            <?php endif ?>
            <?php if (auth("new_user") && !auth("general_user")): ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?do=logout">Log Out</a>
                </li>
            <?php endif ?>
        </ul>
    </div>
</nav>

<div id="page__" >
<?php

/**
 * The current page is included from a php file in the PAGES folder
 */

if (@include 'pages/'.$current . '.php') {
    $_SESSION['last'] = $current;
} else {
    echo "current: " . $current;
//    die();
    include 'pages/404.php';
}

?>

</div>
<?php
//notification from php
if (isset($_GET['message'])) {
    $color = "success";
    if (isset($_GET['danger'])) {
        $color = 'danger';
    }
    if (isset($_GET['color'])) {
        $color = $_GET['color'];
    }
    echo "<script>RSAlert(\"{$color}\",\"{$_GET['message']}\",5)</script>";
}

?>

</body>
</html>