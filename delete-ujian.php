<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
	die();
}

if (!isset($_GET["id"]) && !isset($_GET["q"])) {
	header("Location: view-ujian.php");
	die();
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
// $kode_ujian = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);

$sql = "DELETE FROM ujian
		WHERE id=$id";
$query = mysqli_query($db, $sql);

if (!$query) die(mysqli_error($db));

$sql = "DELETE FROM soal
		WHERE related_id=$id AND NOT copy=1";

// $sql = "DELETE FROM history
// 		WHERE kode_ujian=$kode_ujian";
// $query = mysqli_query($db, $sql);
// if (!$query) die(mysqli_error($db));

if ($query) {
	$msg_title = urlencode("Berhasil!");
	$msg_text = urlencode("Ujian berhasil dihapus!");
	$msg_icon = "success";
	$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
	header("Location: view-ujian.php?" . $msg);
	die();
} else die(mysqli_error($db));

?>