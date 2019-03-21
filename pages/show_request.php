<?php
if (!auth("general_user")) {
    reject("You need to be logged in to submit a request.");


}
?>

<?php
$page = 1;
$total_page = 5;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
?>
<h1 style="text-align: center;margin: 3% 0 0;">Submit Show Request</h1>
<h4 style="text-align: center;margin: 3% 0 0;">This page will save on your computer as you type</h4>

    <form id="show_request"  onkeyup="save_form('show_request')" onchange="save_form('show_request')"
          onfocus="save_form('show_request')" autocomplete="off"
          action="index.php?component=show_confirmation" method="post">
        <div class="<?php if ($page!=1): ?>hide<?php endif ?>">
            <h2>Basic information about your event</h2>
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
            <label>When is soundcheck?<input required step="900" type="time" name="soundcheck"> </label><br>
            <label>What time do you plan on arriving to the event (Should be before soundcheck) <input required
                                                                                                       type="time"
                                                                                                       name="arrival_time"></label><br>
            <label>What is the start time of your event?<input required type="time" step="900"
                                                               name="start_time"></label><br>


            <label>What is the end time of your show? <input required step="900" type="time" name="end_time">
            </label><br>
            <label>How many hours is the show? <input required placeholder="ie: 1.25" type="number" step=".25"
                                                      name="show_hours">
            </label><br>
            <input name="page_2" value="next page" type="submit">
            <button type="button" class="btn btn-danger btn-lg" onclick="clearform('show_request','show_request')">Clear Form</button>
        </div>

<!--        organization confirmation-->
        <div class="<?php if ($page!=2): ?>hide<?php endif ?>">
            <?php
            $db = connect_to_database();
            $sql = $db->query("SELECT * FROM organizations WHERE org_name='{$_GET['org_name']}'");
            $row = $sql->fetch_assoc();
            if ($sql->num_rows == 0) {
                echo "<h2>We haven't seen a request from \"{$_GET['org_name']}\"<br>If you believe this to be an error, please go back and use the dropdown menu under the organization question</h2>";
            } else {
                echo "<h2>Welcome back. Please confirm the following information</h2>";
            }
            ?>
            <label>Is your organization union funded?<input type="checkbox" name="union_funded" <?php
                if ($sql->num_rows >0) {
                    if ($row['union_funded'] == 1)
                        echo 'checked';
                }
                ?>> </label><br>
            <label>What is your account number? <input type="text" name="account_number" value="<?php if ($sql->num_rows > 0) echo $row['account_num']?>"> (leave blank if n/a) </label><br>
            <input type="submit" name="page_1" value="previous page">
            <input type="submit" name="page_3" value="next page">
        </div>


        <div class="<?php if ($page!=3): ?>hide<?php endif ?>">
            <h2>Basic Equipment Requests</h2>
            <label>Which rig are you requesting?
<!--            <label>Does your show have any of the following?</label><br>-->

            <select name="rig">
                <?php
                $db = connect_to_database();
                $sql = $db->query("SELECT * FROM rigs");
                while ($row = $sql->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
                ?>
            </select>

            </label><br>
            <input type="submit" name="page_2" value="previous page">
            <input type="submit" name="page_4" value="next page">
        </div>
        <!--Note this part of the request form is temporary until I finish the rig edit system-->
        <div class="<?php if ($page!=4): ?>hide<?php endif ?>">
            <h2>Additonal Equipment Requests</h2>
            <?php

            $db = connect_to_database();
            $equipment = $db->query("SELECT * FROM inventory WHERE display_on_rental_or_show_or_either='show' OR display_on_rental_or_show_or_either='either'");
            while ($row = $equipment->fetch_assoc()) {
                echo "<label> I need <input required type='number' max='{$row['quantity']}' min='0' name='inventory_{$row['id']}' value='0'> of {$row['novice_name']} </label><br>";
            }
            ?>
            <input type="submit" name="page_3" value="previous page">
            <input type="submit" name="page_5" value="next page">
        </div>


        <div class="<?php if ($page!=5): ?>hide<?php endif ?>">
            <label>Additional comments about your show: <textarea name="additional_comments"></textarea></label><br>
            <label>Have you read and agree to the polices, terms and conditions? <a href="index.php?component=policies">Located here</a> <input name="terms" type="checkbox"> </label><br>
            <input type="submit" name="page_4" value="previous page">
            <input type="submit" name="conplete" value="Submit">
        </div>



        <?php
        $progress = $value=bcdiv($page-1, $total_page, 3) * 100;
        ?>
        <h6 style="margin-top: 4%">Progress</h6>
        <div class="progress" style="height: 30px;">

            <div class="progress-bar" role="progressbar" style="width: <?php echo $progress?>%" aria-valuenow="<?php echo $progress?>" aria-valuemin="0" aria-valuemax="100"><?php echo $progress?>%</div>

        </div>
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
    get_saved_form("show_request");

</script>


