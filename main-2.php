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

if (isset($_GET["n"])) {
	$n = $_GET["n"];

	if ($_SESSION["soal"][$n-1]["sudah_dilihat"] && $_SESSION["soal"][$n-1]["sudah_dikerjakan"]) {
		if ($n == $_SESSION["jumlah_soal"]) {
			header("Location: main-2.php?n=1");
		} else {
			$n = $n + 1;
			header("Location: main-2.php?n=" . $n);
		}
	} else if ($_SESSION["soal"][$n-1]["sudah_dilihat"] && !$_SESSION["soal"][$n-1]["sudah_dikerjakan"]) {
		if (isset($_GET['ans'])) {
			$_SESSION["soal"][$n-1]["jawaban_user"] = $_GET['ans'];
			$_SESSION["soal"][$n-1]["sudah_dikerjakan"] = 1;
			$_SESSION["jumlah_dikerjakan"] += 1;

			if ($n == $_SESSION["jumlah_soal"]) {
				header("Location: main-2.php?n=1");
			} else {
				$n = $n + 1;
				header("Location: main-2.php?n=" . $n);
			}
		} else {
			$_SESSION["soal"][$n-1]["jawaban_user"] = 'x';
			$_SESSION["soal"][$n-1]["sudah_dikerjakan"] = 1;
			$_SESSION["jumlah_dikerjakan"] += 1;

			if ($n == $_SESSION["jumlah_soal"]) {
				header("Location: main-2.php?n=1");
			} else {
				$n = $n + 1;
				header("Location: main-2.php?n=" . $n);
			}
		}
	}
	else if (!$_SESSION["soal"][$n-1]["sudah_dilihat"]) {
		$_SESSION["soal"][$n-1]["sudah_dilihat"] = 1;
	}

	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
		$newUrl = "https";
	} else $newUrl = "http";

	$newUrl .= "://";
	$newUrl .= $_SERVER["HTTP_HOST"];
	$newUrl .= $_SERVER["PHP_SELF"];
	$newUrl .= "?n=";
	$newUrl .= $n;
} else {
	header("Location: main-2.php?n=1");
}

if ($_SESSION["jumlah_dikerjakan"] == $_SESSION["jumlah_soal"]) {
	header("Location: result.php");
}

?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Kuis - Insan Penjaga Al-Qur'an</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container" style="margin-top: 15px">
			<div class="row">
				<div class="col-xs-12">
					<?php 

					$template = "<div class='well text-center'>%d. %s</div>";
					printf($template, $n, $_SESSION["soal"][$n-1]["pertanyaan"]);

					?>
				</div>
			</div>
			<div class="row">
				<a class="col-xs-6" href="main-2.php?n=<?php echo $n; ?>&#38;ans=a">
					<?php

					$template = "<div class='bg-danger text-center' style='padding: 14px'>A. %s</div>";
					printf($template, $_SESSION["soal"][$n-1]["pilihan_1"]);

					?>
				</a>
				<a id="col1" class="col-xs-6" href="main-2.php?n=<?php echo $n; ?>&#38;ans=b">
					<?php

					$template = "<div class='bg-danger text-center' style='padding: 14px'>B. %s</div>";
					printf($template, $_SESSION["soal"][$n-1]["pilihan_2"]);

					?>
				</a>
			</div>
			<br>
			<div class="row">
				<a class="col-xs-6" href="main-2.php?n=<?php echo $n; ?>&#38;ans=c">
					<?php

					echo "<div class='bg-danger text-center' style='padding: 14px;'>";
					$template = "C. %s</div>";
					printf($template, $_SESSION["soal"][$n-1]["pilihan_3"]);

					?>
				</a>
				<a class="col-xs-6" href="main-2.php?n=<?php echo $n; ?>&#38;ans=d">
					<?php

					$template = "<div class='bg-danger text-center' style='padding: 14px'>D. %s</div>";
					printf($template, $_SESSION["soal"][$n-1]["pilihan_4"]);

					?>
				</a>
			</div>
		</div>
		<br>
		<div class="container">
			<div class="progress">
			  <div class="progress-bar" id="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="60" style="min-width: 2em; width: 100%;">
			    60
			  </div>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
		window.onload = function() {
			var timeLeft = 60;
			var countdownTimer = setInterval(function() {
				timeLeft--;

				document.querySelector('#progress-bar').setAttribute("aria-valuenow", timeLeft);
				document.querySelector('#progress-bar').setAttribute("style", "min-width: 2em; width: " + ((100*timeLeft)/60) + "%;");
				document.querySelector('#progress-bar').innerHTML = timeLeft;

				if (timeLeft == 0) {
					clearInterval(countdownTimer);
					window.location.href = "<?php Print($newUrl); ?>";
				};
			}, 1000);
		}
		</script>
	</body>
</html>