<?php

require_once("config.php");

if (isset($_POST["login"])) {
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

	$sql = "SELECT * FROM users
			WHERE username='$username'";
	$query = mysqli_query($db, $sql);
	$data = mysqli_fetch_assoc($query);

	if (mysqli_num_rows($query) < 1) {
		$msg_title = urlencode("Error!");
		$msg_text = urlencode("Username tidak ditemukan!");
		$msg_icon = "error";
		$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
		header("Location: login.php?" . $msg);
		die();
	} else {
		if (password_verify($password, $data["password"])) {
			session_start();
			$_SESSION["user_data"] = $data;
			header("Location: index.php");
			die();
		} else {
			$msg_title = urlencode("Error!");
			$msg_text = urlencode("Password salah!");
			$msg_icon = "error";
			$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
			header("Location: login.php?" . $msg);
			die();
		}
	}
}

?>

<!DOCTYPE html>
<html lang="id">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Halaman Login</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container" style="margin-top: 30px;">
		    <!--<img src="uploads/logo.jpeg" class="img-responsive center-block" style="margin-bottom: 30px;" alt="Logo">-->
			<form action="" method="post">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Login Area</h3>
					</div>
					<div class="panel-body">
						<div class="input-group">
						  <span class="input-group-addon" id="basic-addon1">@</span>
						  <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" name="username" id="username">
						</div>
						<br>
						<div class="input-group">
						  <span class="input-group-addon" id="basic-addon1">
						  	<span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
						  </span>
						  <input type="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1" name="password" id="password">
						</div>
						<br>
						<button type="submit" class="btn btn-primary" name="login"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</button>
					</div>
					<div class="panel-footer text-center">&copy; <?=date('Y');?> <a href="https://<?=$brand_name?>.com"><?=$brand_name?>.com</a></div>
				</div>
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
	</body>
</html>