<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 12/28/2018
 * Time: 3:35 AM
 */

function calculate_cost() {

}

/**
 * @effects calls invalid data, if there are fields that do not have infomrmation OR if the user is not logged into CAS
 */
function authenticate_this_page()
{
    if (!auth("new_user")) {
        reject("Oops, something went wrong");
    }
    if (!isset($_POST['first_name']) ||
        !isset($_POST['last_name']) ||
        !isset($_POST['rin']) ||
        !isset($_POST['phone']) ||
        !isset($_POST['email'])){
        invalid_data("new_user");
    }
    if (
            strlen($_POST['first_name']) == 0 ||
            strlen($_POST['last_name']) == 0 ||
            strlen($_POST['rin']) == 0 ||
            strlen($_POST['phone']) == 0 ||
            strlen($_POST['email']) == 0


    ) invalid_data("new_user","You must fill out all the fields");
}
authenticate_this_page();

$first_name = filter_var($_POST['first_name'],FILTER_SANITIZE_STRING);
$last_name = filter_var($_POST['last_name'],FILTER_SANITIZE_STRING);
if (!filter_var($_POST['rin'],FILTER_SANITIZE_NUMBER_INT)) {
    invalid_data("new_user");
}
$rin = $_POST['rin'];

//validate rin
$rin_string = (string)$rin;
if (strlen($rin_string) != 9 || substr($rin_string,0,2) != "66") {
    invalid_data("new_user");
}
//end validate rin
if (!filter_var($_POST['phone'],FILTER_SANITIZE_EMAIL)) {
    invalid_data("new_user");
}
$phone = $_POST['phone'];
$email = "error";
if (isset($_POST['email']) && filter_var($_POST['email'],FILTER_SANITIZE_EMAIL)) {
    $email = $_POST['email'];
}
if ($email == "error")
    invalid_data("new_user","invalid email address");
$rcs = get_rcs();
$db = connect_to_database();
$sql = "INSERT INTO users (rcs,email,rin,phone,first_name,last_name) values 
        ('{$rcs}','{$email}','{$rin}','{$phone}','{$first_name}','{$last_name}')";
$db->query($sql);
set_session_info();
//begin page here
    $message = "Thank you for creating an account with UPAC Sound";
    redirect("index.php?component=home&message=".$message);

?>

