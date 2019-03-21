<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 1/3/2019
 * Time: 6:21 AM
 */

/**
 * This php can add or delete or edit a database row in any table
 * $_POST['add'] to add (all columns are required)
 * $_GET['delete'] to delete row (give id (post or get) and table name type (post or get))
 * edit by giving $_POST[col name] as well as id and table name ($_POST['type']) (only give what you want to update)
 */
//include_once "../authentication/authentication.php";
if (!auth("chair") && !$_SESSION['override'])
    reject("Looks like you got lost ... taking you back");

$db = connect_to_database();
$type = isset($_POST['type']) ? $_POST['type'] : $_GET['type'];
$id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];

$sqlresult = $db->query("SHOW COLUMNS FROM {$type}");
//security for chiefs on edit techs only. That is all it does
if ($_SESSION['override']) {
    $_SESSION['override'] = false;
    $override = "override_" .$_POST['type']."_show_id".$_POST['show_id'];
    if (!$_SESSION[$override])
        reject("Looks like you got lost ... taking you back");
}


//end additional tests
if ($_POST['add']) {

    $count = 0;
    $col_names = "";
    $col_values = "";
    while ($col = $sqlresult->fetch_assoc()) {
        if ($count > 1) {
            $col_names = $col_names . ",";
            $col_values = $col_values . ",";
        } else if ($count == 0) {
            ++$count;
            continue;
        }
        ++$count;
        $col_values = $col_values . "'{$_POST[$col['Field']]}'";
        $col_names = $col_names . $col['Field'];
    }
    $db->query("INSERT INTO {$type} ({$col_names}) VALUES ({$col_values})");
    echo "INSERT INTO {$type} ({$col_names}) VALUES ({$col_values})";
    echo mysqli_error($db);
    $message = "Added!";
} else if (!isset($_GET['delete']) && !isset($_POST['delete'])) {
    $update_string = "";


    $count = 0;
    while ($col = $sqlresult->fetch_assoc()) {
        if ($count > 1 && (isset($_POST[$col['Field']]) || isset($_POST['force_all'])))
            $update_string = $update_string . ",";
        else if ($count == 0) {
            ++$count;
            continue;
        }
        if (isset($_POST[$col['Field']])) {
            $update_string = $update_string . " {$col['Field']} = '{$_POST[$col['Field']]}'";
        } else if (isset($_POST['force_all'])) {
            $update_string = $update_string . " {$col['Field']} = '0'";
        }
        else {
            $count = 0;//to prevent the comma
        }
        ++$count;
    }
    $db->query("UPDATE {$type} SET{$update_string} WHERE id='{$id}'");
    echo "UPDATE {$type} SET{$update_string} WHERE id={$id}";
    echo mysqli_error($db);
    $message = "Updated!";

}
else
 {
    $message = "Gone!";
    $db->query("DELETE FROM {$type} WHERE id='{$id}'");
}

reject($message,true,$_SESSION['manage_redirect']);