<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 12/25/2018
 * Time: 3:51 AM
 */
require 'cas/CAS.php';
require 'auth.php';
require 'database/database_operations.php';
//login methods

function login() {
    phpCAS::forceAuthentication();
    if (!is_logged_in()) {
        reject("There was an error with CAS");
    }
    $_SESSION['rank'] = 0;

    $_SESSION['rcs'] = phpCAS::getUser();
    $_SESSION['first_name'] = $_SESSION['rcs'];
    set_session_info();
    //will set this session variable
    //some int to represent the level of authenticaion
    /*
     * 0 - new user
     * 1 - general user
     * 2 - trainee
     * 3 - tech
     * 4 - crew cheif
     * 5 - chair
     * 6 - dev (has access to dangerous stuff)
     */
}

function set_session_info()
{
    $db = connect_to_database();
    $sql = "SELECT * FROM users WHERE rcs='{$_SESSION['rcs']}'";
    $result = $db->query($sql);
    if (mysqli_num_rows($result) == 0) {
        $_SESSION['new_user'] = true;
        header("Location: index.php?component=new_user");
    }
    $row = $result->fetch_assoc();
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['last_name'] = $row['last_name'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['phone'] = $row['phone'];
    $_SESSION['rin'] = $row['rin'];
    $_SESSION['rank'] = $row['rank'];
}

function get_first_name() {
    if (isset($_SESSION['first_name'])) {
        return $_SESSION['first_name'];
    }
    return "New User";
}
function get_last_name() {
    return $_SESSION['last_name'];
}
function get_rin() {
    return $_SESSION['rin'];
}
function get_phone() {
    return $_SESSION['phone'];
}
function get_email() {
    return $_SESSION['email'];
}
function logout() {
    phpCAS::logout();
}
function is_logged_in() {
    return phpCAS::isAuthenticated();
}

function get_rcs() {
    return $_SESSION['rcs'];
}
function auth($level) {
    if (!is_logged_in())
        return false;
    $rank = intval($_SESSION['rank']);

    switch ($level) {
        case "new_user":
            return true;
        case "general_user":
            return 1 <= $rank;
        case "trainee":
            return 2 <= $rank;
        case "tech":
            return 3 <= $rank;
        case "crew_cheif":
            return 4 <= $rank;
        case "chair":
            return 5 <= $rank;
        case "dev":
            return 6 == $rank;
        default:
            die("There was a error calling this method. Please contact your systems administrator");
    }
}

/**
 * @param $message
 * this function sends the user to the last valid page that they were at with a message
 */
function reject($message,$success = false,$location = "component=last") {
    $danger = "&danger=true";
    if ($success) {
        $danger = "";
    }
    $url = "index.php?{$location}{$danger}&message=".$message;
    redirect($url);

}

/**
 * @param $url
 * redirect the user to another url
 */
function redirect($url) {
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