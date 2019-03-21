<?php
//custom auth
if (!auth(0)) {
    reject("Oops, something went wrong, Please try again (DEBUG INFORMATION (new_user)");
}

?>

<form action="index.php?component=welcome_page" method="post">
    <h1>Looks like you are new here!</h1>
    <h3>We just have a few questions about you.</h3>
    <label>What is your first name?<input required type="text" name="first_name"> </label><br>
    <label>What is your last name?<input required type="text" name="last_name"> </label><br>
    <label>What is your rin?<input required type="text" name="rin"> </label><br>
    <label>What is your phone number<input required type="tel" name="phone"> </label><br>
    <label>What is your preferred email address <input required type="email" name="email"> </label><br>
    <input type="submit">
</form>
