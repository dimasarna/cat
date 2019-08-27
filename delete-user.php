<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
}

if (!isset($_GET["id"])) {
	header("Location: view-user.php");
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$sql = "DELETE FROM users
		WHERE id=$id";
$query = mysqli_query($db, $sql);

if (!$query) die(mysqli_error($db));

$sql = "DELETE FROM history
		WHERE related_id=$id";
$query = mysqli_query($db, $sql);

if ($query) {
	$msg_title = urlencode("Berhasil!");
	$msg_text = urlencode("Akun berhasil dihapus!");
	$msg_icon = "success";
	$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
	header("Location: view-user.php?" . $msg);
} else die(mysqli_error($db));

?>