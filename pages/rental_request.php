<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 1/7/2019
 * Time: 2:01 PM
 */
if (!auth("general_user")) {

}
?>
<form id="rental_request"  onkeyup="save_form('rental_request')" onchange="save_form('rental_request')"
      onfocus="save_form('rental_request')" onclick="save_form('rental_request')" autocomplete="off"
      action="index.php?component=show_confirmation" method="post">
    <h2>We just need a bit of information</h2>
    <label>What is the name of your show? <input required type="text" name="show_name"></label><br>
    <label>What organization are you representing?
        <div class="autocomplete"><input id="org_input" required type="text"
                                         name="org"></div>
    </label>
    <br>
    <label>Where is your event taking place?
        <div class="autocomplete"><input id="venue_input" class="autocomplete" required type="text"
                                         name="location">
        </div>
    </label><br>
    <label>How many people do you expect to be in attendance?<input required type="number" name="attendance"></label><br>
    <label>What is the date of your event?<input required type="date" name="event_date"></label><br>
    <label>What is your preferred time to pick up the equipment? <input type="time" name="pick_up"> (NOTE: you might not get the preferred time due to our availability) </label><br>

    <label>What is the start time of your event?<input required type="time"
                                                       name="start_time"></label><br>


    <label>What is the end time of your show? <input required type="time" name="end_time">
    </label><br>
    <label>What is your preferred time to drop off the equipment? <input type="time" name="drop_off"> (NOTE: you might not get the preferred time due to our availability) </label><br>

    </label><br>
    <label>Anything else we need to know? <textarea name="additional_info"></textarea> </label><br>
    <label>NOTE: we only rent out 2 speakers, 2 microphones, and 1 analog mixing board. (NO EXCEPTIONS)</label><br>

    <input name="submit" type="submit">
    <button type="button" class="btn btn-danger btn-lg" onclick="clearform('rental_request','rental_request')">Clear Form</button>
</form>

<script>

    var orgs = [
        <?php
        $db = connect_to_database();
        $sql = "SELECT * FROM organizations WHERE display_in_search=1";
        $result = $db->query($sql);
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            if ($count != 0) {
                echo ",";
            }
            ++$count;
            echo "\"{$row['org_name']}\"";
        }
        ?>
    ];
    var venues = [
        <?php
        $db = connect_to_database();
        $sql = "SELECT * FROM venues WHERE display_in_search=1";
        $result = $db->query($sql);
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            if ($count != 0) {
                echo ",";
            }
            ++$count;
            echo "\"{$row['title']}\"";
        }
        ?>
    ];
    // myalert(document.getElementById("org_input"),orgs);
    autocomplete(document.getElementById("org_input"),orgs);
    autocomplete(document.getElementById("venue_input"),venues);
    get_saved_form("rental_request");


</script>