<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
	die();
}

if (!isset($_GET["id"]) && !isset($_GET["q"])) {
	header("Location: view-user.php");
	die();
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
$kode_ujian = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);

$sql = "SELECT score
	FROM history
	WHERE related_id=$id AND kode_ujian='$kode_ujian'";
$query = mysqli_query($db, $sql);

if ($query) {
	$row = mysqli_fetch_assoc($query);
	$last_score = $row["score"];
} else die(mysqli_error($db));

$sql = "UPDATE users
        SET score=(score-$last_score)
        WHERE id = $id";
$query = mysqli_query($db, $sql);

if (!$query) die(mysqli_error($db));

$sql = "DELETE FROM history
	WHERE related_id=$id AND kode_ujian='$kode_ujian'";
$query = mysqli_query($db, $sql);

if ($query) {
	$msg_title = urlencode("Berhasil!");
	$msg_text = urlencode("Riwayat berhasil dihapus!");
	$msg_icon = "success";
	$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
	header("Location: view-history-all.php?" . $msg);
	die();
} else die(mysqli_error($db));

?>