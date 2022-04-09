<?php

require_once("auth.php");
require_once("config.php");

if (!isset($_GET["id"])) {
	header("Location: index.php");
	die();
}

if (isset($_POST["edit"])) {
	$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

	if (isset($_POST["old_password"])) {
		$sql = "SELECT * FROM users
				WHERE id=$id";
		$query = mysqli_query($db, $sql);
		$data = mysqli_fetch_assoc($query);

		$old_password = filter_input(INPUT_POST, 'old_password', FILTER_SANITIZE_STRING);

		if (password_verify($old_password, $data["password"])) {
			if (($_POST["new_password"] != "") && ($_POST["verif_password"] != "")) {
				if ($_POST["new_password"] == $_POST["verif_password"]) {
					$new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

					$sql = "UPDATE users
							SET nama='$name', username='$username', password='$new_password'
							WHERE id=$id";
				} else die("Password baru dan password verifikasi tidak sama.");
			} else {
				$sql = "UPDATE users
						SET nama='$name', username='$username'
						WHERE id=$id";
			}

			$query = mysqli_query($db, $sql);

			if ($query) {
			    header("Location: logout.php?msg=edit-success");
			    die();
			} else die(mysqli_error($db));

		} else {
			$msg_title = urlencode("Error!");
			$msg_text = urlencode("Password lama yang dimasukkan tidak sesuai!");
			$msg_icon = "error";
			$msg = "msg_title=" . $msg_title . "&msg_text=" . $msg_text . "&msg_icon=" . $msg_icon;
			header("Location: edit-user.php?id=" . $id . "&" . $msg);
			die();
		}
	} else die("Silahkan kembali dan masukkan password lama.");
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
			    <h3 class="panel-title">Form User</h3>
			  </div>
			  <div class="panel-body">
			  	<form action="" method="post">
			  		<div class="form-group">
						<div class="alert alert-info" role="alert">Kosongkan field yang tidak diperlukan.</div>
						<div class="alert alert-danger hide" id="notif-alert" role="alert"><span id='notif-text'></span><button type="button" class="close" onclick="dismissAlert()" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
			  			<label for="name">Nama</label>
			  			<input type="text" class="form-control" name="name" id="name" value="<?php echo $data['nama']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="username">Username</label>
			  			<input type="text" class="form-control" name="username" id="username" value="<?php echo $data['username']; ?>">
			  		</div>
			  		<div class="form-group">
			  			<label for="old_password">Password Lama (Wajib Untuk Verifikasi)</label>
			  			<input type="password" class="form-control" name="old_password" id="old_password">
			  		</div>
			  		<div class="form-group">
			  			<label for="new_password">Password Baru</label>
			  			<input type="password" class="form-control" name="new_password" id="new_password" onchange="verif2();">
			  		</div>
			  		<div class="form-group">
			  			<label for="verif_password">Ulangi Password Baru</label>
			  			<input type="password" class="form-control" name="verif_password" id="verif_password" onchange="verif2();">
			  		</div>
					<button type="submit" class="btn btn-primary" name="edit" onclick="return check();"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Edit</button>
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
		<script>
			function dismissAlert() {
				document.getElementById("notif-alert").classList.remove("show");
				document.getElementById("notif-alert").classList.add("hide");
			}
			
			function verif1() {
				var element = document.getElementById('old_password');

				if (element.value == "") {
					document.getElementById("notif-text").innerHTML = "Masukkan Password.";
					document.getElementById("notif-alert").classList.remove("hide");
					document.getElementById("notif-alert").classList.add("show");
					document.getElementById("notif-alert").scrollIntoView();
					return false;
				} else return true;
			}

			function verif2() {
				var element_1 = document.getElementById('new_password');
				var element_2 = document.getElementById('verif_password');

				if (element_1.value != element_2.value) {
					document.getElementById("notif-text").innerHTML = "Password tidak sama.";
					document.getElementById("notif-alert").classList.remove("hide");
					document.getElementById("notif-alert").classList.add("show");
					document.getElementById("notif-alert").scrollIntoView();
					return false;
				} else {
					dismissAlert();
					return true;
				}
			}

			function check() {
				return verif1() && verif2();
			}
		</script>
	</body>
</html>