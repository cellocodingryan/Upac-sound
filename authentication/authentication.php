<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 12/25/2018
 * Time: 3:51 AM
 */
require 'cas/CAS.php';
require 'auth.php';
//login methods

function login() {
    phpCAS::forceAuthentication();
}
function logout() {
    phpCAS::logout();
}
function is_logged_in() {
    return phpCAS::isAuthenticated();
}
function get_rcs() {
    return phpCAS::getUser();
}