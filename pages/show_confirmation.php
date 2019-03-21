<?php
/**
 * Created by PhpStorm.
 * User: cellocodingryan
 * Date: 12/30/2018
 * Time: 9:44 AM
 */




$page1 = (
    isset($_POST['show_name']) &&
    isset($_POST['org']) &&
    isset($_POST['location']) &&
    isset($_POST['attendance']) &&
    isset($_POST['event_date']) &&
    isset($_POST['start_time']) &&
    isset($_POST['arrival_time']) &&
    isset($_POST['soundcheck']) &&
    isset($_POST['end_time']) &&
    isset($_POST['show_hours']) &&
    !(
        strlen($_POST['show_name']) == 0 ||
        strlen($_POST['org']) == 0 ||
        strlen($_POST['location']) == 0 ||
        strlen($_POST['attendance']) == 0 ||
        strlen($_POST['event_date']) ==0 ||
        strlen($_POST['start_time']) == 0 ||
        strlen($_POST['arrival_time']) == 0 ||
        strlen($_POST['soundcheck']) == 0 ||
        strlen($_POST['end_time']) ==0 ||
        strlen($_POST['show_hours']==0)
    )
);
$page2 = (
    isset($_POST['account_number'])&&
    strlen($_POST['account_number']) != 0
);

$union_funded = isset($_POST['union_funded']) ? 1 : 0;

if ($union_funded && $page2 && !(filter_var($_POST['account_number'],FILTER_SANITIZE_NUMBER_FLOAT))) {
    invalid_data("show_request&page=2","account number missing (required for union funded clubs)");
}
$account_number = isset($_POST['account_number']) ? $_POST['account_number'] : " ";


if (!$page1) {
    invalid_data("show_request","you must fill all the fields out");
}


if ($page1){
    $show_name = filter_var($_POST['show_name'], FILTER_SANITIZE_STRING);
    $org = filter_var($_POST['org'], FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    if (!filter_var($_POST['attendance'], FILTER_SANITIZE_NUMBER_INT)) {
        invalid_data("show_request", "attendance value must be a number");
    }
    $attendance = $_POST['attendance'];

//determine if the date of the event is before the current date
    try {
        $date = new DateTime(filter_var($_POST['event_date'],FILTER_SANITIZE_STRING));
        $date_verify = new DateTime(filter_var($_POST['event_date'],FILTER_SANITIZE_STRING));#used to verify with show hours given
        $time_array = explode(":", filter_var($_POST['start_time'],FILTER_SANITIZE_STRING));
        if (count($time_array) != 2)
            throw new Exception("invalid request");
        $today = new DateTime('now');
        $formatted_days_until_show = $date->diff($today)->format('%R%a');
        $date->setTime($time_array[0], $time_array[1]);
        $date_verify->setTime($time_array[0], $time_array[1]);
        $days = intval($formatted_days_until_show);
        if ($days > 0) {
            invalid_data("show_request", "That date already happened");
        }

        $end_time = new DateTime(filter_var($_POST['end_time'],FILTER_SANITIZE_STRING));
        $soundcheck = new DateTime(filter_var($_POST['soundcheck'],FILTER_SANITIZE_STRING));
        $arrival_time = new DateTime(filter_var($_POST['arrival_time'],FILTER_SANITIZE_STRING));
        $start_time = new DateTime(filter_var($_POST['start_time'],FILTER_SANITIZE_STRING));

        if (fmod($_POST['show_hours'], .25) != 0) {
            invalid_data("show_request", "show hours must be in increments of .25:");
        }
        $minutes = doubleval(filter_var($_POST['show_hours'],FILTER_SANITIZE_STRING)) * 60;

        $date_verify->add(new DateInterval('PT' . $minutes . 'M'));

        if ($end_time->format('H:i') != $date_verify->format('H:i')) {
            invalid_data("show_request", "Invalid start/end times");
        }
        //verify times to make sure they make SENSE
        $soundcheck_hour = intval($soundcheck->format('h'));
        $show_start_hour = intval($start_time->format('h'));
        if ($soundcheck_hour > $show_start_hour)
//            invalid_data("show_request", "Soundcheck can not be after the show start time");

            echo $soundcheck_hour . '<br>' . $show_start_hour;

    } catch (Exception $e) {
        //echo count($time_array);
        invalid_data("show_request", "There was a problem with your date input, Please try again.");
    }
}
if ($attendance > 100000) {
invalid_data("show_request","You're full of s**t (you're not that popular and our spaces aren't that big)");
}
//rigs


function go_to_page($page) {
    redirect("index.php?component=show_request&page=".$page);
}
if (isset($_POST['page_1'])) {
    go_to_page("1");
} else if (isset($_POST['page_2'])) {
    go_to_page("2&org_name={$org}");
} else if (isset($_POST['page_3'])) {
    go_to_page(3);
} else if (isset($_POST['page_4'])) {
    go_to_page(4);
} else if (isset($_POST['page_5'])) {
    go_to_page("5&org_name={$org}");
}

//rigs
if (!isset($_POST['rig']) && filter_var($_POST['rig'],FILTER_SANITIZE_NUMBER_INT)) {
    invalid_data("show_request","You must fill out all forms");
}
$rig_id = $_POST['rig'];
$db = connect_to_database();

$sql = $db->query("SELECT * FROM rigs WHERE id='{$rig_id}'");
if ($sql->num_rows != 1) {
    invalid_data("show_request","There was an error :(");
}
//TODO more sanitation on data from pages 2 to 5
$rig_name = $sql->fetch_assoc()['name'];
$contact_rcs = get_rcs();
//extra equipment query
$equipment = $db->query("SELECT * FROM inventory WHERE display_on_rental_or_show_or_either='show' OR display_on_rental_or_show_or_either='either'");
$equipment_needs_string = "";
while ($row = $equipment->fetch_assoc()) {
    $quantity = 0;
    if (isset($_POST['equipment_'.$row['id']])) {
        if (!filter_var($_POST['equipment_'.$row['id']],FILTER_SANITIZE_NUMBER_INT)) {
            invalid_data("show_request","Error (EQUIPMENT QUANTITY)");
        }
        $quantity = $_POST['equipment_'.$row['id']];
    }
    $equipment_needs_string = $equipment_needs_string . $row['id'] . "-" . $quantity . "_";

}
//test if aggredd to terms
if (!isset($_POST['terms'])) {
    invalid_data("show_request&page=5","You must agree to the terms and conditions");
}

$additonal_request = isset($_POST['additional_comments']) ? filter_var($_POST['additional_comments'],FILTER_SANITIZE_STRING) : "";
$next_id = intval($db->query("SELECT * FROM shows ORDER BY id DESC")->fetch_assoc()['id']) + 1;
$show_name = $show_name . ' <a href="index.php?component=manage&type=none&redirect=view_show&id='.$next_id.'">[View Show]</a>';
$sql = "INSERT INTO shows (id,name, venue,attendance, rig, equipment, request_date, show_start_time,contact_id,org_name,org_union_funded,org_account_number,contact_arrival_time,show_date,show_end_date,soundcheck_time,show_end_time,additional_request) 
        VALUES 
      ('{$next_id}','{$show_name}','{$location}','{$attendance}','{$rig_name}','{$equipment_needs_string}','{$today->format('Y-m-d')}','{$start_time->format('H:i')}',
      '{$contact_rcs}','{$org}','{$union_funded}','{$account_number}','{$arrival_time->format("H:i")}','{$date->format('Y-m-d')}','{$date_verify->format('Y-m-d')}','{$soundcheck->format("H:i")}',
      '{$end_time->format("H:i")}','{$additonal_request}')";
$result = $db->query($sql);
//echo $soundcheck->format("h:m");
//echo $sql . '<br>';
echo mysqli_error($db);

echo "TEST".$sql;


?>

