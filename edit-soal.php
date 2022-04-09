<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
	die();
}

if (!isset($_GET["id"])) {
	header("Location: view-soal.php");
	die();
}

if (isset($_POST["edit"])) {
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$pertanyaan = filter_input(INPUT_POST, 'pertanyaan', FILTER_SANITIZE_STRING);
	$pilihan_1 = filter_input(INPUT_POST, 'pilihan_1', FILTER_SANITIZE_STRING);
	$pilihan_2 = filter_input(INPUT_POST, 'pilihan_2', FILTER_SANITIZE_STRING);
	$pilihan_3 = filter_input(INPUT_POST, 'pilihan_3', FILTER_SANITIZE_STRING);
	$pilihan_4 = filter_input(INPUT_POST, 'pilihan_4', FILTER_SANITIZE_STRING);
	$kunci_jawaban = strtolower(filter_input(INPUT_POST, 'kunci_jawaban', FILTER_SANITIZE_STRING));

	$sql = "UPDATE soal
			SET pertanyaan='$pertanyaan', pilihan_1='$pilihan_1', pilihan_2='$pilihan_2', pilihan_3='$pilihan_3', pilihan_4='$pilihan_4', kunci_jawaban='$kunci_jawaban'
			WHERE id=$id";
	$query = mysqli_query($db, $sql);

	if ($query) {
		$msg_title = urlencode("Berhasil!");
		$msg_text = urlencode("Edit soal berhasil!");
		$msg_icon = "success";
		$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
		header("Location: view-soal.php?" . $msg);
		die();
	} else die(mysqli_error($db));
} else {
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

	$sql = "SELECT * FROM soal WHERE id=$id";
	$query = mysqli_query($db, $sql);
	$data = mysqli_fetch_assoc($query);

	if (mysqli_num_rows($query) < 1) die(mysqli_error($db));
}

?>

<!DOCTYPE html>
<html lang="id">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Input Soal</title>
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
						<div class="alert alert-danger hide" id="notif-alert" role="alert"><span id='notif-text'></span><button type="button" class="close" onclick="dismissAlert()" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
			  			<label for="pertanyaan">Pertanyaan</label>
			  			<input type="text" class="form-control" name="pertanyaan" id="pertanyaan" value="<?php echo $data['pertanyaan']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="pilihan_1">Pilihan A</label>
			  			<input type="text" class="form-control" name="pilihan_1" id="pilihan_1" value="<?php echo $data['pilihan_1']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="pilihan_2">Pilihan B</label>
			  			<input type="text" class="form-control" name="pilihan_2" id="pilihan_2" value="<?php echo $data['pilihan_2']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="pilihan_3">Pilihan C</label>
			  			<input type="text" class="form-control" name="pilihan_3" id="pilihan_3" value="<?php echo $data['pilihan_3']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="pilihan_4">Pilihan D</label>
			  			<input type="text" class="form-control" name="pilihan_4" id="pilihan_4" value="<?php echo $data['pilihan_4']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="kunci_jawaban">Kunci Jawaban</label>
			  			<input type="text" class="form-control" name="kunci_jawaban" id="kunci_jawaban" placeholder="e.g., a, b, c, d" value="<?php echo $data['kunci_jawaban']; ?>" maxlength="1">
			  		</div>
					<button type="submit" class="btn btn-primary" name="edit" onclick="return validate();"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Edit</button>
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
			
			function validate() {
				var element1 = document.getElementById('pilihan_1');
				var element2 = document.getElementById('pilihan_2');
				var element3 = document.getElementById('pilihan_3');
				var element4 = document.getElementById('pilihan_4');
				var checklist1;
				var checklist2;

				if (element1.value != "" && element2.value != "") checklist1 = true;
				else if (element1.value == "" && element2.value == "") checklist1 = true;
				else checklist1 = false;
				
				if (element3.value != "" && element4.value != "") checklist2 = true;
				else if (element3.value == "" && element4.value == "") checklist2 = true;
				else checklist2 = false;
				
				if (checklist1 && checklist2) return true;
				else {
					if (!checklist1) {
						document.getElementById("notif-text").innerHTML = "*Pilihan a dan b dapat dikosongkan atau diisi keduanya.";
						document.getElementById("notif-alert").classList.remove("hide");
						document.getElementById("notif-alert").classList.add("show");
						document.getElementById("notif-alert").scrollIntoView();
					}
					else if (!checklist2) {
						document.getElementById("notif-text").innerHTML = "*Pilihan c dan d dapat dikosongkan atau diisi keduanya.";
						document.getElementById("notif-alert").classList.remove("hide");
						document.getElementById("notif-alert").classList.add("show");
						document.getElementById("notif-alert").scrollIntoView();
					} else dismissAlert();
					return false;
				}
			}
		</script>
	</body>
</html>