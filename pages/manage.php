<?php
if (!auth("chair") || !isset($_GET['type']) )
    reject("Looks like you got lost. I put you back on the home page!");
//redirect for custom edit menus
if (isset($_GET['redirect'])) {
//    echo "index.php?component={$_GET['redirect']}&id={$_GET['id']}";
//    die();
    redirect("index.php?component={$_GET['redirect']}&id={$_GET['id']}");
}
$type = $_GET['type'];
$status = isset($_GET['status']) ? (" WHERE status=".$_GET['status']) : "";
$order_r = isset($_GET['order_r']) ? (" ORDER BY {$_GET['order_r']} DESC") : "";
$sql_content = "SELECT * FROM {$type}".$status.$order_r;
$sql_table_name = "SHOW COLUMNS FROM {$type}";
$db = connect_to_database();

$result_content = $db->query($sql_content);
if ($result_content->num_rows ==0) {
//    echo "No rows here";
}
//echo $sql_content;
$result_columns = $db->query($sql_table_name);

//determine show
$sql = "SELECT * FROM display_constants WHERE table_name='{$type}'";
$result  = $db->query($sql);

$show = 9999;//nothing will have this many columns
if ($result->num_rows > 0) {
    $show = $result->fetch_assoc()['display'];
    $show = intval($show);
}
if (!isset($_GET['edit'])) {
    $_SESSION['manage_redirect'] = $_SERVER['QUERY_STRING'];
}




?>

<div style="overflow: auto !important;height: 100%;">
    <table class="table table-hover table-sm">
        <thead>
        <tr>
            <?php
            $i = 0;
            while ($col = $result_columns->fetch_assoc()) {
                if ($i > $show)
                    break;
                $tmp = str_replace("_"," ",$col['Field']);
                echo "<th scope='col'>{$tmp}</th>";
                ++$i;
            }
            if ($show == 9999 || auth("dev")) {
                echo "<th scope='col'>Edit Link</th>";
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        if (isset($_GET['edit'])) {
                echo "<form action='index.php?component=update_database' method='post'>
                    <input hidden name='type' value='{$type}'>
                    <input hidden name='id' value='{$_GET['edit']}'>
                    ";
        } else {
            echo "<form action='index.php?component=update_database' method='post'><input hidden name='type' value='{$type}'>";
        }
        while ($row = $result_content->fetch_assoc()) {
            echo '<tr>';
            $result_columns = $db->query($sql_table_name);
            echo "<th id='{$row['id']}' scope='row'>{$count}</th>";
            $skip_first = 0;
            $i = 0;
            while (($col = $result_columns->fetch_assoc()) && $i < $show) {
                if ($skip_first < 1) {
                    ++$skip_first;
                    continue;
                }
                $value = $row[$col['Field']];
                if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) {
                    echo "<td><input required name='{$col['Field']}' value='{$value}'></td>";
                } else
                echo "<td>{$value}</td>";
                ++$i;
            }
            ++$count;
            if (isset($_GET['edit']) && $_GET['edit'] == $row['id']) {
                echo '<td><input type="submit" value="Update"></td>';
            } else
            if ($show == 9999 || auth("dev")) {
                $status_e = $status == "" ? "" : "&status=".$_GET['status'];
                $order_r_e = $order_r == "" ? "" : "&order_r=".$_GET['order_r'];
                echo "<td><a href='index.php?component=manage&edit={$row['id']}&type={$type}{$status_e}{$order_r_e}#{$row['id']}'>[Edit]</a> 
                <a href='index.php?component=update_database&delete=true&type={$type}&id={$row['id']}' style='color: red;'>[DELETE]</a></td>";
            }
            echo '</tr>';
        }
        //add element here
        if (!isset($_GET['edit']) && $show == 9999) {
            echo '<tr class="table-success">';
            echo '<input hidden type="text" name="add" value="true">';//row for adding stuff
            $result_columns = $db->query($sql_table_name);
            $skip = 0;
            while (($col = $result_columns->fetch_assoc()) && $count <= $show) {
                if ($skip == 0) {
                    ++$skip;
                    echo "<th id='{$row['id']}' scope='row'>{$count}</th>";
                    continue;
                }
                echo "<td><input required name='{$col['Field']}'></td>";
            }
            echo '<td><input type="submit" style="background: green" value="Add"></td>';

                echo "</tr></form>";

        }

        ?>
        </tbody>
    </table>
</div>

