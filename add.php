<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
}

if (isset($_POST["tambah"])) {
	$pertanyaan = filter_input(INPUT_POST, 'pertanyaan', FILTER_SANITIZE_STRING);
	$pilihan_1 = filter_input(INPUT_POST, 'pilihan_1', FILTER_SANITIZE_STRING);
	$pilihan_2 = filter_input(INPUT_POST, 'pilihan_2', FILTER_SANITIZE_STRING);
	$pilihan_3 = filter_input(INPUT_POST, 'pilihan_3', FILTER_SANITIZE_STRING);
	$pilihan_4 = filter_input(INPUT_POST, 'pilihan_4', FILTER_SANITIZE_STRING);
	$kunci_jawaban = strtolower(filter_input(INPUT_POST, 'kunci_jawaban', FILTER_SANITIZE_STRING));

	$sql = "INSERT INTO soal (pertanyaan, pilihan_1, pilihan_2, pilihan_3, pilihan_4, kunci_jawaban)
			VALUES ('$pertanyaan', '$pilihan_1', '$pilihan_2', '$pilihan_3', '$pilihan_4', '$kunci_jawaban')";
	$query = mysqli_query($db, $sql);

	if ($query) {
		$msg_title = urlencode("Berhasil!");
		$msg_text = urlencode("Input soal berhasil!");
		$msg_icon = "success";
		$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
		header("Location: view.php?" . $msg);
	} else die(mysqli_error($db));
}

?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Input Soal - Insan Penjaga Al-Qur'an</title>
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
		        <li><a href='main.php'>Halaman Kuis</a></li>
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
		            <li class="active"><a href="add.php">Add <span class="sr-only">(current)</span></a></li>
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
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Form Soal</h3>
			  </div>
			  <div class="panel-body">
			  	<form action="" method="post">
			  		<div class="form-group">
			  			<label for="pertanyaan">Pertanyaan</label>
			  			<input type="text" class="form-control" name="pertanyaan" id="pertanyaan">
			  		</div>
			  		<div class="form-group">
			  			<label for="pilihan_1">Pilihan A</label>
			  			<input type="text" class="form-control" name="pilihan_1" id="pilihan_1">
			  		</div>
			  		<div class="form-group">
			  			<label for="pilihan_2">Pilihan B</label>
			  			<input type="text" class="form-control" name="pilihan_2" id="pilihan_2">
			  		</div>
			  		<div class="form-group">
			  			<label for="pilihan_3">Pilihan C</label>
			  			<input type="text" class="form-control" name="pilihan_3" id="pilihan_3">
			  		</div>
			  		<div class="form-group">
			  			<label for="pilihan_4">Pilihan D</label>
			  			<input type="text" class="form-control" name="pilihan_4" id="pilihan_4">
			  		</div>
			  		<div class="form-group">
			  			<label for="kunci_jawaban">Kunci Jawaban</label>
			  			<input type="text" class="form-control" name="kunci_jawaban" id="kunci_jawaban" placeholder="e.g., a, b, c, d" maxlength="1">
			  		</div>
					<input type="submit" class="btn btn-primary" name="tambah" value="Tambah">
				</form>
			  </div>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>