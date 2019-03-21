<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 3/9/2019
 * Time: 9:00 AM
 */


$replace = $_POST['replace'];
$db = connect_to_database();

//shows

if ($replace) {
    $sql = $db->query("DELETE FROM shows");
}
while ($row = $_POST['shows']->fetch_assoc()) {
    $name = $row['event_name'];
    
    $sql = $db->query("INSERT INTO shows ()");
}