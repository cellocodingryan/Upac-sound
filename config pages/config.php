<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 3/9/2019
 * Time: 7:38 AM
 */

//there must exist
$ini = parse_ini_file($_SERVER['HTTP_HOST'] . ".ini");
if (!$ini) {
    //the page will not load if a ini file with the name of the http host is not found
    die ("Database not defined Error code: (" . $_SERVER['HTTP_HOST'] . ") Try with a different url to this page");
}

/**all of the following must exist in the ini file or you will get undefined effects**/
define("DB_NAME",$ini['db_name']);
define("DB_PASSWORD",$ini['db_password']);
define("DB_USER",$ini['db_user']);
define("DB_HOST",$ini['db_host']);


