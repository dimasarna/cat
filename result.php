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

$id_ujian = $_SESSION["kuis_data"]["id_ujian"];
$kode_ujian = $_SESSION["kuis_data"]["kode_ujian"];
$nama_ujian = $_SESSION["kuis_data"]["nama_ujian"];
$jumlah_benar = 0;

for ($counter = 0; $counter < $_SESSION["jumlah_soal"]; ++$counter) {
	if ($_SESSION["soal"][$counter]["jawaban_user"] == $_SESSION["soal"][$counter]["kunci_jawaban"]) {
		$_SESSION["user_data"]["score"] += 1;
		$jumlah_benar += 1;
	}
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
        WHERE related_id=$id_user
        AND kode_ujian='$kode_ujian'";
$query = mysqli_query($db, $sql);

if (!$query) die(mysqli_error($db));

?>

<!DOCTYPE html>
<html>
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Result</title>
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
		<?php

		for ($counter = 0; $counter < $_SESSION["jumlah_soal"]; ++$counter) {

			if (($_SESSION["soal"][$counter]["pilihan_3"] != "") && ($_SESSION["soal"][$counter]["pilihan_4"] != "")) {

				$template = "<div class='container'><div class='well lead text-center'>%d. %s</div>
							<div class='radio lead'><label><input type='radio' name='id%d' value='a' %s><span style='color: %s;'> %s</span></label></div>
							<div class='radio lead'><label><input type='radio' name='id%d' value='b' %s><span style='color: %s;'> %s</span></label></div>
							<div class='radio lead'><label><input type='radio' name='id%d' value='c' %s><span style='color: %s;'> %s</span></label></div>
							<div class='radio lead'><label><input type='radio' name='id%d' value='d' %s><span style='color: %s;'> %s</span></label></div>
							</div>";

				printf(
					$template, $counter+1, $_SESSION["soal"][$counter]["pertanyaan"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'a' ? "checked" : ""), ($_SESSION["soal"][$counter]["kunci_jawaban"] == 'a' ? "green" : "red"), $_SESSION["soal"][$counter]["pilihan_1"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'b' ? "checked" : ""), ($_SESSION["soal"][$counter]["kunci_jawaban"] == 'b' ? "green" : "red"), $_SESSION["soal"][$counter]["pilihan_2"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'c' ? "checked" : ""), ($_SESSION["soal"][$counter]["kunci_jawaban"] == 'c' ? "green" : "red"), $_SESSION["soal"][$counter]["pilihan_3"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'd' ? "checked" : ""), ($_SESSION["soal"][$counter]["kunci_jawaban"] == 'd' ? "green" : "red"), $_SESSION["soal"][$counter]["pilihan_4"]
					);

			} else {

				$template = "<div class='container'><div class='well lead text-center'>%d. %s</div>
							<div class='radio lead'><label><input type='radio' name='id%d' value='a' %s><span style='color: %s;'> %s</span></label></div>
							<div class='radio lead'><label><input type='radio' name='id%d' value='b' %s><span style='color: %s;'> %s</span></label></div>
							</div>";

				printf(
					$template, $counter+1, $_SESSION["soal"][$counter]["pertanyaan"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'a' ? "checked" : ""), ($_SESSION["soal"][$counter]["kunci_jawaban"] == 'a' ? "green" : "red"), $_SESSION["soal"][$counter]["pilihan_1"],
					$_SESSION["soal"][$counter]['id'], ($_SESSION["soal"][$counter]["jawaban_user"] == 'b' ? "checked" : ""), ($_SESSION["soal"][$counter]["kunci_jawaban"] == 'b' ? "green" : "red"), $_SESSION["soal"][$counter]["pilihan_2"]
					);
				
			}
		}

		echo "<div class='container lead text-center'>" . $jumlah_benar . " out of " . $_SESSION["jumlah_soal"] . "</div>";

		?>
		<div class="container" style="height: 30px;"></div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>

<?php unset($_SESSION["soal"]); unset($_SESSION["kuis_data"]); unset($_SESSION["jumlah_soal"]); unset($_SESSION["user_data"]["sedang_kuis"]); ?>