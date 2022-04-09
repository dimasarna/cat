<?php

session_start();
session_unset();
session_destroy();

if (isset($_GET["msg"]) && $_GET["msg"] == "edit-success") {
	$msg_title = urlencode("Edit Berhasil!");
	$msg_text = urlencode("Silahkan login kembali!");
	$msg_icon = "success";
	$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
	header("Location: login.php?" . $msg);
	die();
} else {
	$msg_title = urlencode("Berhasil!");
	$msg_text = urlencode("Logout berhasil.");
	$msg_icon = "success";
	$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
	header("Location: login.php?" . $msg);
	die();
}

?>