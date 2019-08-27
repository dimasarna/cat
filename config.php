<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "oAKA4ZX0C2pPWNKb";
$db_name = "kuis2";

$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$db) {
	die(mysqli_connect_error());
}

?>