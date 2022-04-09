<?php 
require_once 'config.php';

$sql = "SELECT related_id, SUM(score) AS score
		FROM history
		GROUP BY related_id
		ORDER BY score DESC";
$query = mysqli_query($db, $sql);

if (!$query) {
	die(mysqli_error($db));
}

while ($row = mysqli_fetch_assoc($query)) {
	$id = $row["related_id"];
	$score = $row["score"];
	
	$code = "UPDATE users
            SET score=$score
            WHERE id=$id";
    $run = mysqli_query($db, $code);
    
    if (!$run) die(mysqli_error($db));
}

echo "Success!";

?>