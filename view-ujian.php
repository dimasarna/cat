<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
}

$sql = "SELECT * FROM ujian";
$query = mysqli_query($db, $sql);

?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Daftar Ujian - Insan Penjaga Al-Qur'an</title>
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
		            <li><a href="add.php">Add</a></li>
		            <li><a href="view.php">View</a></li>
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ujian <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="add-ujian.php">Add</a></li>
		            <li class="active"><a href="view-ujian.php">View <span class="sr-only">(current)</span></a></li>
		          </ul>
		        </li>
		        <?php } ?>
		      </ul>
		    </div>
		  </div>
		</nav>
		<div class="container">
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th>Kode ujian</th>
						<th>Nama Ujian</th>
						<th>Token</th>
						<th>Action</th>
					</tr>
					<?php

					while ($data = mysqli_fetch_array($query)) {
						echo "<tr>";
						echo "<td>" . $data["kode_ujian"] . "</td>";
						echo "<td>" . $data["nama_ujian"] . "</td>";
						echo "<td>" . $data["token"] . "</td>";
						echo "<td><a href='delete-ujian.php?id=" . $data["id"] . "'>Delete</a></td>";
						echo "</tr>";
					}
					
					?>
				</table>
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