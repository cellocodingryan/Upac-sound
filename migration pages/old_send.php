<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 3/9/2019
 * Time: 9:00 AM
 */

//migrate shows
$db = connect_to_database();

$a = $db->query("SELECT * FROM admin_comments");
$b = $db->query("SELECT * FROM approval_statuses");
$c = $db->query("SELECT * FROM gm_week_times");
$d = $db->query("SELECT * FROM organizations");
$e = $db->query("SELECT * FROM payment_types");
$f = $db->query("SELECT * FROM rentals");
$g = $db->query("SELECT * FROM shows");
$h = $db->query("SELECT * FROM shows_statuses");
$i = $db->query("SELECT * FROM tech_payroll_entries");
$j = $db->query("SELECT * FROM users");
$k = $db->query("SELECT * FROM van_schedules");
$l = $db->query("SELECT * FROM venues");
$url = "http://sound-test.union.rpi.edu/";
$replace =
$data = array('admin_comments' => $a,'approval_statuses' => );
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { die("");}




