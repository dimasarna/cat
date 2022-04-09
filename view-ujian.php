<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
	die();
}

$sql = "SELECT * FROM ujian ORDER BY id DESC";
$query = mysqli_query($db, $sql);

if (!$query) die(mysqli_error($db));

if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {

	$newUrl = "https";

} else $newUrl = "http";

$newUrl .= "://";
$newUrl .= $_SERVER["HTTP_HOST"];
$newUrl .= "/delete-ujian.php?id=";

?>

<!DOCTYPE html>
<html lang="id">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Daftar Ujian</title>
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
						if ($data["is_active"]) echo "<td><a class='btn btn-warning btn-sm' href='activate-ujian.php?id=" . $data["id"] . "&cmd=0' role='button'><span class='glyphicon glyphicon-lock' aria-hidden='true'></span> Lock</a> <a class='btn btn-danger btn-sm' href='javascript:void(0)' role='button' onclick='confirm(\"".$newUrl.$data["id"]."\");'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span> Delete</a></td>";
						else echo "<td><a class='btn btn-info btn-sm' href='activate-ujian.php?id=" . $data["id"] . "&cmd=1' role='button'><span class='glyphicon glyphicon-repeat' aria-hidden='true'></span> Unlock</a> <a class='btn btn-danger btn-sm' href='javascript:void(0)' role='button' onclick='confirm(\"".$newUrl.$data["id"]."\");'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span> Delete</a></td>";
						echo "</tr>";
					}
					
					?>
				</table>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script>
    		function confirm(confUrl) {
    			swal({
    			  title: "Are you sure?",
    			  text: "Once deleted, you will not be able to recover this data!",
    			  icon: "warning",
    			  buttons: true,
    			  dangerMode: true,
    			})
    			.then((willDelete) => {
    			  if (willDelete) {
    			  	window.location.href = confUrl;
    			  } else {
    			    swal("Your data is safe!");
    			  }
    			});
    		}
        </script>
		<?php

		if (isset($_GET["msg_title"]) && isset($_GET["msg_text"]) && isset($_GET["msg_icon"])) {
			echo "<script>swal('" . $_GET["msg_title"] . "', '" . $_GET["msg_text"] . "', '" . $_GET["msg_icon"] . "');</script>";
		}

		?>
	</body>
</html>