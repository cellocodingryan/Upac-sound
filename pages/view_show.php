<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 1/8/2019
 * Time: 11:31 AM
 */
include 'libraries/cost_calculation.php';
$db = connect_to_database();
$show = $db->query("SELECT * FROM shows WHERE id={$_GET['id']}")->fetch_assoc();
$show['name'] = explode("<a href",$show['name'])[0];
$cost = $show['custom_cost'] == 0 ? " automatic cost calculation coming soon" : $show['custom_cost'];
$status = "unknown";
$ischief = false;//for staff table
switch ($show['status']) {
    case (0):
        $status = "Being reviewed by an admin";
        break;
    case (1):
        $status = "Denied";
        break;
    case (2):
        $status = "Cancelled";
        break;
    case (3):
        $status = "Approved (upcoming show)";
        break;
    case (4):
        $status = "Completed (awaiting payroll for technicians)";
        break;
    case (5):
        $status = "Archived";
        break;

}
$contact_sql = $db->query("SELECT * FROM users WHERE rcs='{$show['contact_id']}'")->fetch_assoc();
if (!auth("trainee") && get_rcs() != $contact_sql['rcs']) {
    reject("Looks like you got lost .. taking you back");
}
$adminedit = auth("dev");
$edit = auth("chair");
$_SESSION['manage_redirect'] = $_SERVER['QUERY_STRING'];


try {
    $load_in = calcuate_load_start($show['soundcheck_time'],calculate_load_hours($show['rig'],$show['venue'],$show['equipment'],true));
    $load_out = calculate_load_end($show['show_end_time'],calculate_load_hours($show['rig'],$show['venue'],$show['equipment'],false));
    $request_date = new DateTime($show['request_date']);
    $show_date = new DateTime($show['show_date']);
    $contact_arrival = new DateTime($show['contact_arrival_time']);
    $soundcheck = new DateTime($show['soundcheck_time']);
    $start_time = new DateTime($show['show_start_time']);
    $end_time = new DateTime($show['show_end_time']);

} catch (Exception $e) {
    die("There was an error :( (CODE 100)");
}
?>


<p id="show_name">Show Name: <?php echo $show['name']?> </p>
<p id="status">Status: <?php echo $status?> <?php if ($edit): ?>
        <a href="#" onclick="test('status (0 = under review, 1 = rejected, 2 = cancelled, 3 = upcoming, 4=completed, 5=archived)','status','<?php echo $show['status']?>')">[EDIT]</a>
    <?php endif ?></p>

<p id="venue">Venue: <?php echo $show['venue']?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('venue','venue','<?php echo $show['venue']?>')">[EDIT]</a>
    <?php endif ?> </p>
<p id="attendance">Attendance <?php echo $show['attendance']?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('Expected Attendance','attendance','<?php echo $show['attendance']?>')">[EDIT]</a>
    <?php endif ?></p>
<p id="rig">Rig <?php echo $show['rig']?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('Rig','rig','<?php echo $show['rig']?>')">[EDIT]</a>
    <?php endif ?></p>
<p id="request_date">Request Date <?php echo $request_date->format("m-d-Y")?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('request date','request_date','<?php echo $show['request_date']?>')">[EDIT]</a>
    <?php endif ?></p>
<p id="contact_id">Contact name <?php echo $contact_sql['first_name'] . " " . $contact_sql['last_name']?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('Contact rcs','contact_id','<?php echo $contact_sql['rcs']?>')">[EDIT]</a>
    <?php endif ?></p>
<p id="contact_email">Contact email <?php echo $contact_sql['email']?></p>
<p id="contact_phone">Contact phone <?php echo $contact_sql['phone']?></p>
<p id="contact_arrival_time">Contact arrival time <?php echo $contact_arrival->format("h:i a") . " (" . $contact_arrival->format("H:i") .")"?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('Contact arrival time','contact_arrival_time','<?php echo $contact_arrival->format("H:i")?>','time')">[EDIT]</a>
    <?php endif ?></p>
<p id="org_name">Organization name <?php echo $show['org_name']?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('organization name','org_name','<?php echo $show['org_name']?>')">[EDIT]</a>
    <?php endif ?></p>
<p id="org_union_funded">Union Funded? <?php if ($show['org_union_funded']) echo "Yes"; else echo "No";?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('Union funded?','org_union_funded','<?php echo $show['org_union_funded']?>','checkbox')">[EDIT]</a>
    <?php endif ?></p>
<p id="org_account_number">Account Number <?php echo $show['org_account_number']?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('account number','org_account_number','<?php echo $show['org_account_number']?>')">[EDIT]</a>
    <?php endif ?></p>
<p id="show_date">Show date <?php echo $show_date->format("m-d-Y")?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('show date','show_date','<?php echo $show_date->format("Y-m-d")?>','date')">[EDIT]</a>
    <?php endif ?></p>
<p id="load_in">Load In Time <?php echo $load_in->format("h:i a") . " (" . $load_in->format("H:i") .")"?></p>
<p id="soundcheck_time">Soundcheck time <?php echo $soundcheck->format("h:i a") . " (" . $soundcheck->format("H:i") .")"?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('soundcheck time','soundcheck_time','<?php echo $soundcheck->format("H:i")?>','time')">[EDIT]</a>
    <?php endif ?></p>
<p id="start_time">Start Time <?php echo $start_time->format("h:i a") . " (" . $start_time->format("H:i") .")"?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('start time','start_time','<?php echo $start_time->format("H:i")?>','time')">[EDIT]</a>
    <?php endif ?></p>
<p id="end_time">End Time <?php echo $end_time->format("h:i a") . " (" . $end_time->format("H:i") .")"?> <?php if ($adminedit): ?>
        <a href="#" onclick="test('end time','end_time','<?php echo $end_time->format("H:i")?>','time step=\'.01\'')">[EDIT]</a>
    <?php endif ?></p>
<p id="load_out">Load Out End Time <?php echo $load_out->format("h:i a") . " (" . $load_out->format("H:i") .")"?></p>
<p>View price with open contract button</p>


<?php if (auth("chair")): ?>
    <script>
        function test(name, id, value, type) {
            if (type == null)
                type = "text";
            $("#" + id).replaceWith("<form method='post' action='index.php?component=update_database&type=shows&id=<?php echo $show['id']?>'><label>" + name + ": <input name='" + id + "' type='" + type + "' value='" + value + "'></label><input type='submit' value='save'> </form>");
        }

    </script>
<?php endif ?>
<?php
function print_out_field($name,$value,$type,$allow = true,$placeholder = "",$custom = "",$show = true,$label = "") {
    global $ischief;
    if (auth("chair") || $ischief) {
        $disabled = "";
        if (!$allow) {
            $disabled = "disabled";
        }
        $checked = "";
        if ($value >0 ) {
            $checked = "checked";

        }

        return "<label>{$label} <input name='{$name}' {$checked} value='{$value}' type='{$type}' {$disabled} placeholder='{$placeholder}' {$custom}> </label><br>";
    }
    if (!$show)
        return "";
    return $value;
}
?>
<a target="_blank" href="pages/contract.php?show=<?php echo implode("0987340295870912835365213524110247489564320234",$show);?>">Open Contract </a>
    <h5 style="text-align: center">Staff</h5>
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Status</th>
            <th scope="col">RCS ID</th>
            <th scope="col">Load In (hours)</th>
            <th scope="col">Show (hours)</th>
            <th scope="col">Loud Out (hours)</th>
            <th scope="col"> </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = $db->query("SELECT * FROM techs WHERE show_id='{$show['id']}' ORDER BY status ASC");
        $techs = [0,0,0];
        $hours_type = $show['status'] > 3 ? "number" : "checkbox";
        if ($sql->num_rows > 0) {
            while ($row = $sql->fetch_assoc()) {
                $tech_name = $db->query("SELECT * FROM users WHERE rcs='{$row['tech_id']}'")->fetch_assoc();

                echo '<tr>';
                echo '<form action="index.php?component=update_database" method="post">';
                echo '<th scope="row">';
                if ($tech_name['rank'] >= 4) {
                    $chief = $row['status'] == "chief";
                    $checked = $chief ? "checked" : "";
                    if ($tech_name['rcs'] == $_SESSION['rcs']) {
                        $ischief = true;
                        $override = "override_techs_show_id{$show['id']}";
                        $_SESSION['override'] = !auth("chair");
                        $_SESSION[$override] = true;//allow this user to edit techs table in database
                    }

                    echo print_out_field("status", "chief", "checkbox", true, "", $checked, false, "Crew Chief:");
                    if ($chief) {
                            echo 'Crew Chief<br>';
                    } else {
                        echo 'Technician<br>';
                    }
                } else if ($tech_name['rank'] >= 3) {
                    echo 'Technician<br>';
                } else {
                    echo 'Trainee<br>';
                }
                echo print_out_field("delete","","checkbox",true,"","",false,"Remove:");
                echo '</th>';
                echo '<td><div class="autocomplete">';
                echo print_out_field("tech_id",$tech_name['rcs'],"text",true,"RCS Id","class='edit_tech' class='autocomplete'");
                echo '</div></td>';

                echo '<td>';
                echo print_out_field("load_in_hours",$row['load_in_hours'],$hours_type);
                echo '</td>';

                echo '<td>';
                echo print_out_field("show_hours",$row['show_hours'],$hours_type);
                echo '</td>';

                echo '<td>';
                echo print_out_field("load_out_hours",$row['load_out_hours'],$hours_type);
                echo '</td>';
                echo '<td>';
                echo print_out_field("submit","Update","submit",true,"","",false);
                echo '</td>';
                echo '<td>
<input type="hidden" name="force_all" value="true">
<input type="hidden" name="type" value="techs">
 <input type="hidden" name="show_id" value="' .$show['id']. '">
  <input type="hidden" name="date" value="' .$show['show_date']. '">
   <input type="hidden" name="id" value="'.$row['id'].'"> </td>';
                echo '</form>';
                echo '</tr>';

            }
        }

        if (auth("chair") || $ischief) {
            echo '<tr>';
            echo '<form action="index.php?component=update_database" method="post">';
            echo '<th scope="row">';
            echo '</th>';
            echo '<td><div class="autocomplete">';
            echo print_out_field("tech_id", "", "text", true, "RCS Id", "id='add_tech' class='autocomplete'");
            echo '</div></td>';
            echo '<td>';
            echo print_out_field("load_in_hours", calculate_load_hours($show['rig'], $show['venue'], $show['equipment'], true), "checkbox");
            echo '</td>';
            echo '<td>';
            echo print_out_field("show_hours", calculate_show_hours($soundcheck, $end_time), "checkbox");
            echo '</td>';
            echo '<td>';
            echo print_out_field("load_out_hours", calculate_load_hours($show['rig'], $show['venue'], $show['equipment'], false), "checkbox");
            echo '</td>';
            echo '<td>';
            echo '<input type="submit" style="background: green;" value="Add">';
            echo '</td>';
            echo '<td>
    <input type="hidden" name="add" value="add">
    <input type="hidden" name="type" value="techs">
     <input type="hidden" name="show_id" value="' . $show['id'] . '">
      <input type="hidden" name="date" value="' . $show['show_date'] . '">
       <input type="hidden" name="status" value="none"> </td>';
            echo '</form>';
            echo '</tr>';
        }


        ?>

        </tbody>
    </table>

<script>
    var users = [
        <?php
            $result = $db->query("SELECT * FROM users");
        while ($row = $result->fetch_assoc()) {
            if ($row['rank'] < 2)
                continue;
            if ($count != 0) {
                echo ",";
            }
            ++$count;
            echo "\"{$row['rcs']}\"";
        }

        ?>

    ];
    var rcs = {};
    <?php
    $result = $db->query("SELECT * FROM users");
    while ($row = $result->fetch_assoc()) {
        if ($row['rank'] < 2)
            continue;

        $name = $row['first_name']." ".$row['last_name'];
        ++$count;
        echo "rcs['{$name}'] = '{$row['rcs']}';";
    }
    ?>


    // autocomplete(document.getElementsByClassName("edit_tech"),users);
    autocomplete(document.getElementById("add_tech"),users);
</script>
<?php

get_equipment_array($show['equipment']);