<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
}

if (!isset($_GET["id"])) {
	header("Location: view.php");
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

$sql = "DELETE FROM soal
		WHERE id=$id";
$query = mysqli_query($db, $sql);

if ($query) {
	$msg_title = urlencode("Berhasil!");
	$msg_text = urlencode("Soal berhasil dihapus!");
	$msg_icon = "success";
	$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
	header("Location: view.php?" . $msg);
} else die(mysqli_error($db));

?>