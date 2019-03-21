<?php
/**
 * Created by PhpStorm.
 * User: cellocodingryan
 * Date: 2/5/2019
 * Time: 7:46 AM
 */


//verify everything is not empty
function verify_filled_out(){
    if (isset($_POST['show_name']) &&
        isset($_POST['org']) &&
        isset($_POST['location']) &&
        isset($_POST['attendance']) &&
        isset($_POST['event_date']) &&
        isset($_POST['pick_up']) &&
        isset($_POST['start_time']) &&
        isset($_POST['end_time']) &&
        isset($_POST['drop_off']) &&
        isset($_POST['show_hours'])) {
        return true;
    }
    return false;
}
if (!verify_filled_out()) {
    invalid_data("rental_request","You must fill out all the fields");
}