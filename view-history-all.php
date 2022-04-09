<?php

require_once("auth.php");
require_once("config.php");

if ($_SESSION["user_data"]["is_admin"] == 0) {
	header("Location: index.php");
	die();
}

$list_ujian = array();
$total_ujian = 0;

$sql = "SELECT * FROM ujian ORDER BY id DESC";
$query = mysqli_query($db, $sql);

if (!$query) die(mysqli_error($db));

if (mysqli_num_rows($query) > 0) {
	while ($row = mysqli_fetch_assoc($query)) {
		$list_ujian[] = $row;
		++$total_ujian;
	}
}

if (isset($_GET["q"])) {
	$kode_ujian = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);

	$sql = "SELECT * FROM history WHERE kode_ujian='$kode_ujian' ORDER BY related_id";
	$query = mysqli_query($db, $sql);
	
	if (!$query) die(mysqli_error($db));

	if (mysqli_num_rows($query) > 0) {
		$total = 0;
		$user = array();

		while ($row = mysqli_fetch_assoc($query)) {
			$user[$total]["id"] = $row["related_id"];
			$user[$total]["score"] = $row["score"];

			++$total;
		}
	} else {
		header("Location: view-history-all.php");
		die();
	}

	$sql = "SELECT * FROM users ORDER BY id ASC";
	$query = mysqli_query($db, $sql);

	if ($query) {
		$count = 0;

		while ($row = mysqli_fetch_assoc($query)) {
			if ($user[$count]["id"] == $row["id"]) {
				$user[$count]["username"] = $row["username"];
				$user[$count]["nama"] = $row["nama"];

				++$count;
			}
			if ($count >= $total) break;
		}
		
		$keys = array_column($user, 'score');
		array_multisort($keys, SORT_DESC, $user);
		
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
        
        	$newUrl = "https";
        
        } else $newUrl = "http";
        
        $newUrl .= "://";
        $newUrl .= $_SERVER["HTTP_HOST"];
        $newUrl .= "/delete-history.php?id=";
	} else die(mysqli_error($db));
} else {
    $sql = "SELECT COUNT(id) FROM history";
    $query = mysqli_query($db, $sql);
    
    if (!$query) die(mysqli_error($db));
    else {
        $row = mysqli_fetch_array($query);
        $total_records = $row[0];
    }
    
    if (isset($_GET["section"])) $section = $_GET["section"];
    else $section = 1;
    
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
        $offset = ($page*100)-100;
        
    	$sql = "SELECT users.username, users.nama, history.kode_ujian, history.score
            	FROM history
            	INNER JOIN users
            	ON history.related_id=users.id
            	ORDER BY history.id DESC
    	        LIMIT $offset,100";
    	$query = mysqli_query($db, $sql);
    	
    	if (!$query) die(mysqli_error($db));
    
    	if (mysqli_num_rows($query) > 0) {
    		$total = 0;
    		$user = array();
    
    		while ($row = mysqli_fetch_assoc($query)) {
    			$user[$total]["kode_ujian"] = $row["kode_ujian"];
    			$user[$total]["username"] = $row["username"];
    			$user[$total]["nama"] = $row["nama"];
    			$user[$total]["score"] = $row["score"];
    
    			++$total;
    		}
    	} else $total = 0;
    } else {
        $page = (($section-1)*5)+1;
        $offset = ($page*100)-100;
        
        $sql = "SELECT users.username, users.nama, history.kode_ujian, history.score
            	FROM history
            	INNER JOIN users
            	ON history.related_id=users.id
            	ORDER BY history.id DESC
    	        LIMIT $offset,100";
    	$query = mysqli_query($db, $sql);
    	
    	if (!$query) die(mysqli_error($db));
    
    	if (mysqli_num_rows($query) > 0) {
    		$total = 0;
    		$user = array();
    
    		while ($row = mysqli_fetch_assoc($query)) {
    			$user[$total]["kode_ujian"] = $row["kode_ujian"];
    			$user[$total]["username"] = $row["username"];
    			$user[$total]["nama"] = $row["nama"];
    			$user[$total]["score"] = $row["score"];
    
    			++$total;
    		}
    	} else $total = 0;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Riwayat</title>
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
		            <li class="active"><a href="view-history-all.php">Riwayat <span class="sr-only">(current)</span></a></li>
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
			<div class="btn-group">
			  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    Select <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu">
			  	<?php 

			  	$i = 0;

			  	if ($total_ujian > 0) {
			  		while ($i < $total_ujian) {
				  		echo "<li><a href='view-history-all.php?q=" . $list_ujian[$i]["kode_ujian"] . "'>" . $list_ujian[$i]["nama_ujian"] . "</a></li>";

				  		++$i;
				  	}
			  	} else echo "<li><a href='#'>Not found</a></li>";
			  	
			  	?>
			  </ul>
			</div>
		</div>
		<br>
		<div class="container">
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<?php if (isset($_GET["q"])) { ?><th>No</th><?php } ?>
						<?php if (!isset($_GET["q"])) { ?><th>Kode Ujian</th><?php } ?>
						<th>Username</th>
						<th>Nama</th>
						<th>Score</th>
						<?php if (isset($_GET["q"])) { ?><th>Action</th><?php } ?>
					</tr>
					<?php $no = 0;

					while ($no < $total) {
						echo "<tr>".PHP_EOL;
						if (isset($_GET["q"]))
						    echo "<td>" . ($no+1) . "</td>";
						else echo "<td>" . $user[$no]["kode_ujian"] . "</td>";
						echo "<td>" . $user[$no]["username"] . "</td>";
						echo "<td>" . $user[$no]["nama"] . "</td>";
						echo "<td>" . $user[$no]["score"] . "</td>";
						if (isset($_GET["q"]))
						    echo "<td><a class='btn btn-danger btn-sm' href='javascript:void(0)' role='button' onclick='confirm(\"".$newUrl.$user[$no]["id"]."&q=".$kode_ujian."\");'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span> Delete</a></td>";
						echo "</tr>";

						$no = $no + 1;
					}
					
					?>
				</table>
			</div>
			<?php if (!isset($_GET["q"])) { ?>
			<nav class="text-center" aria-label="Page navigation">
              <ul class="pagination">
                <li>
                  <a href="<?=($section <= 1) ? "#" : "?section=".($section-1);?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <?php if ($total_records > ((($section-1)*5)*100)) { ?><li <?=(($page == (($section-1)*5)+1) ? "class=\"active\"" : "")?>><a href="?section=<?=$section;?>&page=<?=(($section-1)*5)+1;?>"><?=(($section-1)*5)+1;?></a></li><?php } ?>
                <?php if ($total_records > (((($section-1)*5)+1)*100)) { ?><li <?=(($page == (($section-1)*5)+2) ? "class=\"active\"" : "")?>><a href="?section=<?=$section;?>&page=<?=(($section-1)*5)+2;?>"><?=(($section-1)*5)+2;?></a></li><?php } ?>
                <?php if ($total_records > (((($section-1)*5)+2)*100)) { ?><li <?=(($page == (($section-1)*5)+3) ? "class=\"active\"" : "")?>><a href="?section=<?=$section;?>&page=<?=(($section-1)*5)+3;?>"><?=(($section-1)*5)+3;?></a></li><?php } ?>
                <?php if ($total_records > (((($section-1)*5)+3)*100)) { ?><li <?=(($page == (($section-1)*5)+4) ? "class=\"active\"" : "")?>><a href="?section=<?=$section;?>&page=<?=(($section-1)*5)+4;?>"><?=(($section-1)*5)+4;?></a></li><?php } ?>
                <?php if ($total_records > (((($section-1)*5)+4)*100)) { ?><li <?=(($page == (($section-1)*5)+5) ? "class=\"active\"" : "")?>><a href="?section=<?=$section;?>&page=<?=(($section-1)*5)+5;?>"><?=(($section-1)*5)+5;?></a></li><?php } ?>
                <li>
                  <a href="<?=($section > floor($total_records/500)) ? "#" : "?section=".($section+1);?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
            <?php } ?>
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