<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
	die();
}

if (!isset($_GET["id"]) || !isset($_GET["cmd"])) {
	header("Location: view-ujian.php");
	die();
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_GET, 'cmd', FILTER_SANITIZE_STRING);

$sql = "UPDATE ujian
		SET is_active=$status
		WHERE id=$id";
$query = mysqli_query($db, $sql);

if ($query) {
	$msg_title = urlencode("Berhasil!");
	$msg_text = urlencode("Status ujian berhasil diubah!");
	$msg_icon = "success";
	$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
	header("Location: view-ujian.php?" . $msg);
	die();
} else die(mysqli_error($db));

?>