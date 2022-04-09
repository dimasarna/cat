<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
	die();
}

if (!isset($_GET["id"])) {
	header("Location: index.php");
	die();
}

if (isset($_POST["edit"])) {
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$score = filter_input(INPUT_POST, 'score', FILTER_SANITIZE_STRING);

	$sql = "UPDATE users
			SET nama='$name', username='$username', score=$score
			WHERE id=$id";
	$query = mysqli_query($db, $sql);

	if ($query) {
		$msg_title = urlencode("Berhasil!");
		$msg_text = urlencode("Informasi user berhasil di ubah!");
		$msg_icon = "success";
		$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
		header("Location: view-user.php?id=" . $id . "&" . $msg);
		die();
	} else die(mysqli_error($db));
} else {
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

	$sql = "SELECT * FROM users WHERE id=$id";
	$query = mysqli_query($db, $sql);
	$data = mysqli_fetch_assoc($query);

	if (mysqli_num_rows($query) < 1) die(mysqli_error($db));
}

?>

<!DOCTYPE html>
<html lang="id">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Edit Akun</title>
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
		        <li><a href='main.php'>Halaman Ujian</a></li>
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
			    <h3 class="panel-title">Form Info</h3>
			  </div>
			  <div class="panel-body">
			  	<form action="" method="post">
			  		<div class="form-group">
			  			<label for="name">Nama</label>
			  			<input type="text" class="form-control" name="name" id="name" value="<?php echo $data['nama']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="username">Username</label>
			  			<input type="text" class="form-control" name="username" id="username" value="<?php echo $data['username']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="score">Total Score</label>
			  			<input type="text" class="form-control" name="score" id="score" value="<?php echo $data['score']; ?>">
			  		</div>
					<button type="submit" class="btn btn-primary" name="edit"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Edit</button>
				</form>
			  </div>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	</body>
</html>