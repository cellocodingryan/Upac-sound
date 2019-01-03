<?php
if (!auth("chair") || !isset($_GET['type']))
    reject("Looks like you got lost. I put you back on the home page!");

$type = $_GET['type'];

$sql_content = "SELECT * FROM {$type}";
$sql_table_name = "SHOW COLUMNS FROM {$type}";
$db = connect_to_database();

$result_content = $db->query($sql_content);
$result_columns = $db->query($sql_table_name);


?>

<table class="table table-hover">
    <thead>
    <tr>
        <?php
        while ($col = $result_columns->fetch_assoc()) {
            echo "<th scope='col'>{$col['Field']}</th>";
        }
        echo "<th scope='col'>Edit Link</th>"
        ?>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;
    while ($row = $result_content->fetch_assoc()) {
        echo '<tr>';
        $result_columns = $db->query($sql_table_name);
        echo "<th scope='row'>{$count}</th>";
        $skip_first = 0;

        while ($col = $result_columns->fetch_assoc()) {
            if ($skip_first < 1) {
                ++$skip_first;
                continue;
            }
            echo "<td>{$row[$col['Field']]}</td>";
        }
        ++$count;
        echo "<td><a href='index.php?component=edit_{$type}&id={$row['id']}'>[Edit]</a></td>";
        echo '</tr>';
    }

    ?>
    </tbody>
</table>

