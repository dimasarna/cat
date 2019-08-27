<?php

require_once("auth.php");
require_once("config.php");

if (!isset($_SESSION["user_data"]["sedang_kuis"])) {
	header("Location: main.php");
	die();
} else if (!$_SESSION["user_data"]["sedang_kuis"]) {
	header("Location: main.php");
	die();
}

if ($_SESSION["jumlah_dikerjakan"] == $_SESSION["jumlah_soal"]) {
	$id_ujian = $_SESSION["kuis_data"]["id_ujian"];
	$kode_ujian = $_SESSION["kuis_data"]["kode_ujian"];
	$nama_ujian = $_SESSION["kuis_data"]["nama_ujian"];

	$jumlah_benar = 0;
	$benar = array();

	for ($counter = 0; $counter < $_SESSION["jumlah_soal"]; ++$counter) {
		if ($_SESSION["soal"][$counter]["jawaban_user"] == $_SESSION["soal"][$counter]["kunci_jawaban"]) {
			$_SESSION["user_data"]["score"] += 1;
			$benar[$counter] = 1;
			$jumlah_benar += 1;
		} else $benar[$counter] = 0;
	}

	$id_user = $_SESSION["user_data"]["id"];
	$score = $_SESSION["user_data"]["score"];
	$sql = "UPDATE users
			SET score=$score
			WHERE id=$id_user";
	$query = mysqli_query($db, $sql);

	if (!$query) die(mysqli_error($db));

	$sql = "UPDATE history
			SET score=$jumlah_benar
			WHERE related_id=$id_user AND kode_ujian='$kode_ujian'";
	$query = mysqli_query($db, $sql);

	if (!$query) die(mysqli_error($db));
} else {
	header("Location: main-2.php?n=1");
	die();
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Result - Insan Penjaga Al-Qur'an</title>
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
		            <li><a href="view-ujian.php">View</a></li>
		          </ul>
		        </li>
		        <?php } ?>
		      </ul>
		    </div>
		  </div>
		</nav>
		<?php

		$template = "<div class='container'><div class='well text-center'>%d. %s</div>
					<div class='radio'><label><input type='radio' name='id%d' value='a' %s><span style='color: %s;'> %s</span></label></div>
					<div class='radio'><label><input type='radio' name='id%d' value='b' %s><span style='color: %s;'> %s</span></label></div>
					<div class='radio'><label><input type='radio' name='id%d' value='c' %s><span style='color: %s;'> %s</span></label></div>
					<div class='radio'><label><input type='radio' name='id%d' value='d' %s><span style='color: %s;'> %s</span></label></div>
					</div>";

		for ($counter = 0; $counter < $_SESSION["jumlah_soal"]; ++$counter) {
			if ($benar[$counter]) {
				printf(
					$template, $counter+1, $_SESSION["soal"][$counter]["pertanyaan"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'a' ? "checked" : ""), "green", $_SESSION["soal"][$counter]["pilihan_1"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'b' ? "checked" : ""), "green", $_SESSION["soal"][$counter]["pilihan_2"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'c' ? "checked" : ""), "green", $_SESSION["soal"][$counter]["pilihan_3"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'd' ? "checked" : ""), "green", $_SESSION["soal"][$counter]["pilihan_4"]
					);
			} else {
				printf(
					$template, $counter+1, $_SESSION["soal"][$counter]["pertanyaan"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'a' ? "checked" : ""), "red", $_SESSION["soal"][$counter]["pilihan_1"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'b' ? "checked" : ""), "red", $_SESSION["soal"][$counter]["pilihan_2"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'c' ? "checked" : ""), "red", $_SESSION["soal"][$counter]["pilihan_3"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'd' ? "checked" : ""), "red", $_SESSION["soal"][$counter]["pilihan_4"]
					);
			}
		}

		echo "<div class='container text-center'><span>" . $jumlah_benar . " out of " . $_SESSION["jumlah_soal"] . "</span></div>";

		?>
		<div class="container" style="height: 30px;"></div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>

<?php

unset($_SESSION["soal"]);
unset($_SESSION["kuis_data"]);
unset($_SESSION["jumlah_soal"]);
unset($_SESSION["jumlah_dikerjakan"]);
unset($_SESSION["user_data"]["sedang_kuis"]);

?>