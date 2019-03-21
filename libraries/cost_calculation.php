<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 1/9/2019
 * Time: 9:40 AM
 */




function calculate_staff($rig,$venue,$extra_equipment,$which,$load_in_hours,$show_hours=0,$load_out_hours=0) {
    $ot_amount = 8;
    $res = array(0,0.0,0.0);
    $res[0] += get_venue_array($extra_equipment)[4];
    $extra_equipment_a = get_equipment_array($extra_equipment);
    for ($j = 0;$j < count($extra_equipment_a)-1;++$j) {
        $res[0] += $extra_equipment_a[$j][5];
    }
    switch ($which) {
        case 0:
            $res[0] += get_rig_array($rig)[4];
            $res[1] += $load_in_hours <= $ot_amount ? $load_in_hours : $ot_amount;
            $res[2] += $load_in_hours<=$ot_amount ? 0 : $load_in_hours-$ot_amount;
            break;
        case 1:
            $res[0] += get_rig_array($rig)[5];
            //test if ot started in load in
            if ($load_in_hours > $ot_amount) {
                $res[1] += 0;
                $res[2] += $show_hours;
                break;
            }
            if ($load_in_hours + $show_hours > $ot_amount) {
                $res[1] = $ot_amount-$load_in_hours;
                $show_hours-=$res[1];
            }
            $res[2] += $show_hours;
            break;
        case 2:
            $res[0] += get_rig_array($rig)[5];
            //test if ot started in load in
            if ($show_hours+$load_in_hours > $ot_amount) {
                $res[1] += 0;
                $res[2] += $load_out_hours;
                break;
            }
            if ($show_hours + $load_out_hours+$load_in_hours > $ot_amount) {
                $res[1] = $ot_amount-$show_hours-$load_in_hours;
                $load_out_hours-=$res[1];
            }
            $res[2] += $load_out_hours;
            break;
    }
    return $res;
}
/**
 * @param $soundcheck_time
 * @param $load_in_hours
 * @return DateTime the load in start time
 */
function calcuate_load_start($soundcheck_time,$load_in_hours) {
    try {
        $soundcheck_datetime = new DateTime($soundcheck_time);
        $load_in_time = new DateInterval("PT".($load_in_hours*60)."M");
        return $soundcheck_datetime->sub($load_in_time);
    } catch (Exception $e) {
        die("There was a datetime failure");
    }
}
function calculate_load_end($end_time,$load_out_hours,$end_date = "") {
    try {
        $end_time_datetime = new DateTime($end_date.''.$end_time);
        $load_in_time = new DateInterval("PT".($load_out_hours*60)."M");
        return $end_time_datetime->add($load_in_time);
    } catch (Exception $e) {
        die("There was a datetime failure");
    }
}
function calculate_show_hours($soundcheck,$show_end) {
    return (doubleval($show_end->diff($soundcheck)->format("%h")) * 60.0 + doubleval($show_end->diff($soundcheck)->format("%i")))/(60.0);
}

function calculate_load_hours($rig,$venue,$extra_equipment,$is_load_in) {
    $i = 2;
    if (!$is_load_in) {
        $i = 3;
    }
    $load = 0.0;
    $load += get_rig_array($rig)[$i];

    $load += get_venue_array($venue)[$i];

    $extra_equipment_a = get_equipment_array($extra_equipment);
    for ($j = 0;$j < count($extra_equipment_a)-1;++$j) {
        $load += $extra_equipment_a[$j][$i+1];
    }
    $load *= 4;
    $load = ceil($load);
    $load /= 4;

    return $load;
}

function get_equipment_array($string) {
    $db = connect_to_database();
    $equipment = explode('_', $string);
    foreach($equipment as $k=>$v){
        $equipment[$k] = explode('-', $v);
    }
    for ($i = 0;$i < count($equipment)-1;++$i) {
        $equipment_sql = $db->query("SELECT * FROM inventory WHERE id='{$equipment[$i][0]}'")->fetch_assoc();

        $equipment[$i] = array(
            $equipment_sql['name'],intval($equipment[$i][1]),$equipment_sql['cost'],$equipment_sql['load_in_hours'],$equipment_sql['load_out_hours'],$equipment_sql['extra_staff']);
    }
    return $equipment;
}
function get_venue_array($venue_) {
    $db = connect_to_database();
    $venue_sql = $db->query("SELECT * FROM venues WHERE title='{$venue_}'")->fetch_assoc();
    $venue = array($venue_sql['title'],doubleval($venue_sql['cost']),doubleval($venue_sql['load_in_hours']),doubleval($venue_sql['load_out_hours']),doubleval($venue_sql['extra_staff']));
    return $venue;
}
function get_rig_array($rig_) {
    $db = connect_to_database();
    $rig_sql = $db->query("SELECT * FROM rigs WHERE name='{$rig_}'")->fetch_assoc();
    $rig = array($rig_sql['name'],doubleval($rig_sql['cost']),doubleval($rig_sql['load_in_hours']),doubleval($rig_sql['load_out_hours']),doubleval($rig_sql['load_in_staff']),doubleval($rig_sql['show_staff']),doubleval($rig_sql['load_out_staff']));
    return $rig;
}