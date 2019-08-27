<?php

session_start();
if (!isset($_SESSION["user_data"])) {
	header("Location: login.php");
	die();
}

?>