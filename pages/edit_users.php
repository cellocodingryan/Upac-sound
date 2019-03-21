<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 1/3/2019
 * Time: 5:58 AM
 */
if (!auth("chair"))
    reject("looks like you got lost ... taking you back");

$row = connect_to_database()->query("SELECT * FROM users WHERE id={$_GET['id']}")->fetch_assoc();
?>
<h1>Edit User</h1>
<form action="" method="post">
    <label>ID: <input disabled name="id" value="<?php echo $row['id']?>" </label>
    <label>First Name: <input name="first_name" value="<?php echo $row['first_name']?>" </label>
    <label>Last Name: <input name="last_name" value="<?php echo $row['last_name']?>" </label>
    <label>RCS ID: <input name="rcs" disabled value="<?php echo $row['rcs']?>" </label>
    <label>Email: <input name="email" value="<?php echo $row['email']?>" </label>
    <label>Rin: <input name="rin" disabled value="<?php echo $row['rin']?>" </label>
    <label>Phone: <input name="phone" value="<?php echo $row['phone']?>" </label>
    <label>Rank: <input name="rank" value="<?php echo $row['rank']?>" </label>
    <input value="Update" type="submit">
</form>
