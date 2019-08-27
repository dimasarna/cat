<?php 

require_once("auth.php");
require_once("config.php");

if (isset($_POST["mulai"])) {
	$id_user = $_SESSION["user_data"]["id"];
	$kode_ujian = filter_input(INPUT_POST, 'kode', FILTER_SANITIZE_STRING);
	$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);

	$sql = "SELECT id, nama_ujian FROM ujian WHERE kode_ujian='$kode_ujian' AND token='$token'";
	$query = mysqli_query($db, $sql);

	if (mysqli_num_rows($query) > 0) {
		$data = mysqli_fetch_assoc($query);
		$id_ujian = $data["id"];
		$nama_ujian = $data["nama_ujian"];

		$sql = "SELECT * FROM history WHERE related_id=$id_user AND kode_ujian='$kode_ujian'";
		$query = mysqli_query($db, $sql);

		if (mysqli_num_rows($query) > 0) {
			$msg_title = urlencode("Peringatan!");
			$msg_text = urlencode("Anda sudah pernah mengikuti ujian ini sebelumnya!");
			$msg_icon = "warning";
			$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
			header("Location: main.php?" . $msg);
			die();
		}

		$sql = "INSERT INTO history (related_id, kode_ujian, nama_ujian, score)
				VALUES ($id_user, '$kode_ujian', '$nama_ujian', 0)";
		$query = mysqli_query($db, $sql);

		if (!$query) die(mysqli_error($db));

		$sql = "SELECT * FROM soal WHERE related_id=$id_ujian";
		$query = mysqli_query($db, $sql);

		if ($query) {
			$counter = 0;
			$_SESSION["soal"] = array();

			while ($data = mysqli_fetch_assoc($query)) {
				$_SESSION["soal"][$counter] = $data;
				$_SESSION["soal"][$counter]["sudah_dikerjakan"] = 0;
				$_SESSION["soal"][$counter]["sudah_dilihat"] = 0;

				$counter = $counter + 1;
			}

			$_SESSION["jumlah_soal"] = mysqli_num_rows($query);
			$_SESSION["jumlah_dikerjakan"] = 0;
			$_SESSION["user_data"]["sedang_kuis"] = 1;
			$_SESSION["kuis_data"]["id_ujian"] = $id_ujian;
			$_SESSION["kuis_data"]["kode_ujian"] = $kode_ujian;
			$_SESSION["kuis_data"]["nama_ujian"] = $nama_ujian;

			header("Location: main-2.php?n=1");
		} else die(mysqli_error($db));
	} else {
		$msg_title = urlencode("Error!");
		$msg_text = urlencode("Token atau kode ujian yang anda masukkan salah!");
		$msg_icon = "error";
		$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
		header("Location: main.php?" . $msg);
		die();
	}
}

?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Halaman Kuis - Insan Penjaga Al-Qur'an</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-default">
		  <div class="container">
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="#">Insan Penjaga Al-Qur'an</a>
		    </div>
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		        <li><a href="index.php">Home</a></li>
		        <?php if ($_SESSION["user_data"]["is_admin"] == 0) { ?>
		        <li class="active"><a href='main.php'>Halaman Kuis <span class="sr-only">(current)</span></a></li>
		        <li><a href='view-history.php'>Riwayat</a></li>
		        <?php } ?>
				<?php if ($_SESSION["user_data"]["is_admin"] == 1) { ?>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="register.php">Register</a></li>
		            <li><a href="view-user.php">Scoreboard</a></li>
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Soal <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="add.php">Add</a></li>
		            <li><a href="view.php">View</a></li>
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ujian <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="add-ujian.php">Add</a></li>
		            <li><a href="view-ujian.php">View</a></li>
		          </ul>
		        </li>
		        <?php } ?>
		      </ul>
		    </div>
		  </div>
		</nav>
		<div class="container">
			<form action="" method="post">
				<div class="form-group">
					<label for="kode">Masukkan Kode Ujian</label>
					<input type="text" class="form-control" name="kode" id="kode" maxlength="5">
				</div>
				<div class="form-group">
					<label for="token">Masukkan Token</label>
					<input type="text" class="form-control" name="token" id="token" maxlength="5">
				</div>
				<input class="btn btn-primary" type="submit" name="mulai" value="Mulai" onclick="return validation();">
			</form>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<?php

		if (isset($_GET["msg_title"]) && isset($_GET["msg_text"]) && isset($_GET["msg_icon"])) {
			echo "<script>swal('" . $_GET["msg_title"] . "', '" . $_GET["msg_text"] . "', '" . $_GET["msg_icon"] . "');</script>";
		}

		?>
		<script>
		function validation() {
			var kode_ujian = document.getElementById('kode').value;
			var token = document.getElementById('token').value;

			if ((kode_ujian == "" || kode_ujian == undefined) || (token == "" || token == undefined)) {
				swal("Peringatan!", "Harap isikan kode dan token ujian!", "warning");
				return false;
			} else return true;
		}
		</script>
	</body>
</html>