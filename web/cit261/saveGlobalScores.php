<?php
if (isset($_GET['scores'])){
	$jsonFile = fopen("highScores.json", "w") or die("Unable to open file!");
	$scores = $_GET['scores'];
	fwrite($jsonFile, $scores);
	fclose($jsonFile);
} else {
	die;
}
?>