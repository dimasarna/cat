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

    if ($n > $_SESSION["jumlah_soal"]) {
	    header("Location: result.php");
	    die();
    }
    
	if (isset($_GET['ans'])) {
	    $_SESSION["soal"][$n-1]["jawaban_user"] = $_GET['ans'];

		$n = $n + 1;
		header("Location: main-2.php?n=" . $n);
		die();
	}
	
	//if there is nothing to process

	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
		$newUrl = "https";
	} else $newUrl = "http";

	$newUrl .= "://";
	$newUrl .= $_SERVER["HTTP_HOST"];
	$newUrl .= $_SERVER["PHP_SELF"];
	$newUrl .= "?n=";
	$newUrl .= ($n+1);
} else {
	header("Location: main-2.php?n=1");
	die();
}

?>

<!DOCTYPE html>
<html lang="id">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Ujian</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript">
	        window.history.forward();
	        function noBack() { window.history.forward(); }
        </script>
	</head>
	<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
		<div class="container" style="margin-top: 15px">
			<div class="row">
				<div class="col-xs-12">
					<?php 

					$template = "<div class='well lead text-center'>%d dari %d<br>%s</div>";
					printf($template, $n, $_SESSION["jumlah_soal"], $_SESSION["soal"][$n-1]["pertanyaan"]);

					?>
				</div>
			</div>
			<div class="row">
				<a class="col-xs-6" href="main-2.php?n=<?php echo $n; ?>&#38;ans=a">
					<?php

					$template = "<div class='bg-success lead text-center' style='padding: 14px'>A. %s</div>";
					printf($template, $_SESSION["soal"][$n-1]["pilihan_1"]);

					?>
				</a>
				<a id="col1" class="col-xs-6" href="main-2.php?n=<?php echo $n; ?>&#38;ans=b">
					<?php

					$template = "<div class='bg-success lead text-center' style='padding: 14px'>B. %s</div>";
					printf($template, $_SESSION["soal"][$n-1]["pilihan_2"]);

					?>
				</a>
			</div>
			<br>
			<?php if (($_SESSION["soal"][$n-1]["pilihan_3"] != "") && ($_SESSION["soal"][$n-1]["pilihan_4"] != "")) { ?>
			<div class="row">
				<a class="col-xs-6" href="main-2.php?n=<?php echo $n; ?>&#38;ans=c">
					<?php

					$template = "<div class='bg-success lead text-center' style='padding: 14px;'>C. %s</div>";
					printf($template, $_SESSION["soal"][$n-1]["pilihan_3"]);

					?>
				</a>
				<a class="col-xs-6" href="main-2.php?n=<?php echo $n; ?>&#38;ans=d">
					<?php

					$template = "<div class='bg-success lead text-center' style='padding: 14px'>D. %s</div>";
					printf($template, $_SESSION["soal"][$n-1]["pilihan_4"]);

					?>
				</a>
			</div>
		<?php } ?>
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