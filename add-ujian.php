<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
	die();
}

if (isset($_POST["tambah"])) {
	$nama_ujian = filter_input(INPUT_POST, 'nama_ujian', FILTER_SANITIZE_STRING);
	$kode_ujian = filter_input(INPUT_POST, 'kode_ujian', FILTER_SANITIZE_STRING);
	$token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
	$total = filter_input(INPUT_POST, 'total', FILTER_SANITIZE_STRING);

	$sql = "INSERT INTO ujian (kode_ujian, nama_ujian, token)
			VALUES ('$kode_ujian', '$nama_ujian', '$token')";
	$query = mysqli_query($db, $sql);

	if ($query) {
		$sql = "SELECT LAST_INSERT_ID()";
		$query = mysqli_query($db, $sql);
		$data = mysqli_fetch_array($query);

		$id_ujian =  $data[0];
	} else die(mysqli_error($db));

	for ($i = 0; $i < $total; ++$i) {
		$input_id = "input-" . $i;

		if (isset($_POST[$input_id])) {
			$id_soal = $_POST[$input_id];

			$sql = "SELECT * FROM soal WHERE id=$id_soal";
			$query = mysqli_query($db, $sql);

			if (!$query) die(mysqli_error($db));
			else $data = mysqli_fetch_assoc($query);

			$pertanyaan = $data["pertanyaan"];
			$pilihan_1 = $data["pilihan_1"];
			$pilihan_2 = $data["pilihan_2"];
			$pilihan_3 = $data["pilihan_3"];
			$pilihan_4 = $data["pilihan_4"];
			$kunci_jawaban = $data["kunci_jawaban"];

			$sql = "SELECT copy FROM soal WHERE pertanyaan='$pertanyaan'";
			$query = mysqli_query($db, $sql);
			$total_copy = mysqli_num_rows($query);
			$copy = $total_copy + 1;

			$sql = "INSERT INTO soal (related_id, pertanyaan, pilihan_1, pilihan_2, pilihan_3, pilihan_4, kunci_jawaban, copy)
					VALUES ($id_ujian, '$pertanyaan', '$pilihan_1', '$pilihan_2', '$pilihan_3', '$pilihan_4', '$kunci_jawaban', $copy)";
			$query = mysqli_query($db, $sql);

			if (!$query) die(mysqli_error($db));
		}
	}

	$msg_title = urlencode("Berhasil!");
	$msg_text = urlencode("Berhasil menambahkan ujian!");
	$msg_icon = "success";
	$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
	header("Location: view-ujian.php?" . $msg);
	die();
}

$sql = "SELECT * FROM soal WHERE copy=1 ORDER BY id DESC";
$query = mysqli_query($db, $sql);

?>

<!DOCTYPE html>
<html lang="id">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Membuat Ujian</title>
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
		      <a class="navbar-brand" href="#"><?=$brand_name?></a>
		    </div>
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		        <li><a href="index.php">Home</a></li>
		        <?php if ($_SESSION["user_data"]["is_admin"] == 0) { ?>
		        <li><a href='main.php'>Halaman Kuis</a></li>
		        <li><a href='view-history.php'>Riwayat</a></li>
		        <?php } ?>
				<?php if ($_SESSION["user_data"]["is_admin"] == 1) { ?>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="register.php">Register</a></li>
		            <li><a href="view-user.php">Scoreboard</a></li>
		            <li><a href="view-history-all.php">Riwayat</a></li>
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Soal <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="add-soal.php">Add</a></li>
		            <li><a href="view-soal.php">View</a></li>
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ujian <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li class="active"><a href="add-ujian.php">Add <span class="sr-only">(current)</span></a></li>
		            <li><a href="view-ujian.php">View</a></li>
		          </ul>
		        </li>
		        <?php } ?>
		      </ul>
		    </div>
		  </div>
		</nav>
		<div class="container">
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Form Ujian</h3>
			  </div>
			  <div class="panel-body">
			  	<form action="" method="post">
			  		<div class="form-group">
						<div class="alert alert-danger hide" id="notif-alert" role="alert"><span id='notif-text'></span><button type="button" class="close" onclick="dismissAlert()" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
			  			<label for="nama_ujian">Nama Ujian</label>
			  			<input type="text" class="form-control" name="nama_ujian" id="nama_ujian">
			  		</div>
			  		<div class="form-group">
			  			<label for="kode_ujian">Kode Ujian</label>
			  			<input type="text" class="form-control" name="kode_ujian" id="kode_ujian" maxlength="5">
			  		</div>
			  		<div class="form-group">
			  			<label for="token">Token</label>
			  			<input type="text" class="form-control" name="token" id="token" maxlength="5">
			  			<button class="btn btn-default" id="make_token" onclick="return generate_token(5);">Generate</button>
			  		</div>
			  		<div class="form-group"><label>Pilih Soal</label><small id="notif"></small></div>
					<?php

					$template = "<div class='checkbox'><label><input type='checkbox' name='input-%d' value='%d'> %s</label></div>";
					$i = 0;

					while ($data = mysqli_fetch_array($query)) {
						printf($template, $i, $data["id"], $data["pertanyaan"]);

						$i = $i + 1;
					}

					echo "<div class='form-group'><input type='hidden' name='total' value='" . $i . "'></div>";

					?>
					<button type="submit" class="btn btn-primary" name="tambah" onclick="return validation();"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Tambah</button>
				</form>
			  </div>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
			function dismissAlert() {
				document.getElementById("notif-alert").classList.remove("show");
				document.getElementById("notif-alert").classList.add("hide");
			}
			
			function generate_token(length) {
				var result = "";
				var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
				var characters_length = characters.length;

				for (var i = 0; i < length; ++i) {
					result += characters.charAt(Math.floor(Math.random()*characters_length));
				}

				document.getElementById("token").value = result.toUpperCase();
				return false;
			}

			function validation() {
				var total = document.querySelectorAll('input[type="checkbox"]:checked').length;
				if (total > 0) return true;
				else {
					document.getElementById("notif-text").innerHTML = "&nbsp;Pilih minimal 1 soal.";
					document.getElementById("notif-alert").classList.remove("hide");
					document.getElementById("notif-alert").classList.add("show");
					document.getElementById("notif-alert").scrollIntoView();
					return false;
				}
			}
		</script>
	</body>
</html>