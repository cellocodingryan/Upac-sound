<?php
/**
 * Created by PhpStorm.
 * User: Ryan_Waddell
 * Date: 12/27/2018
 * Time: 6:57 AM
 */
function connect_to_database() {
    return mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD,DB_NAME);
}
