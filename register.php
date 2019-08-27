<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
}

if (isset($_POST["register"])) {
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

	if ($_POST["password"] != $_POST["verif_password"]) {
		$msg_title = urlencode("Error!");
		$msg_text = urlencode("Password verifikasi tidak sama!");
		$msg_icon = "error";
		$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
		header("Location: register.php?" . $msg);
	}
	
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

	$sql = "INSERT INTO users (nama, username, password)
			VALUES ('$name', '$username', '$password')";
	$query = mysqli_query($db, $sql);

	if ($query) {
		$msg_title = urlencode("Berhasil!");
		$msg_text = urlencode("Pendaftaran berhasil!");
		$msg_icon = "success";
		$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
		header("Location: view-user.php?" . $msg);
	} else die(mysqli_error($db));
}

?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Halaman Pendaftaran - Insan Penjaga Al-Qur'an</title>
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
		            <li class="active"><a href="register.php">Register <span class="sr-only">(current)</span></a></li>
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
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title">Form Registrasi</h3>
			  </div>
			  <div class="panel-body">
			  	<form action="" method="post">
			  		<div class="form-group">
			  			<label for="name">Nama</label>
			  			<input type="text" class="form-control" name="name" id="name">
			  		</div>
			  		<div class="form-group">
			  			<label for="username">Username</label>
			  			<input type="text" class="form-control" name="username" id="username">
			  		</div>
			  		<div class="form-group">
			  			<label for="password">Password</label>
			  			<input type="password" class="form-control" name="password" id="password">
			  		</div>
			  		<div class="form-group">
			  			<label for="verif_password">Verifikasi Password</label>
			  			<input type="password" class="form-control" name="verif_password" id="verif_password">
			  		</div>
					<input type="submit" class="btn btn-primary" name="register" value="Register">
				</form>
			  </div>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<?php

		if (isset($_GET["msg_title"]) && isset($_GET["msg_text"]) && isset($_GET["msg_icon"])) {
			echo "<script>swal('" . $_GET["msg_title"] . "', '" . $_GET["msg_text"] . "', '" . $_GET["msg_icon"] . "');</script>";
		}

		?>
	</body>
</html>