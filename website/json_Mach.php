<?php
	header("Content-Type: application/json; charset=UTF-8");
	$mach_id = "%";
	$active = "%";
	if (!empty($_GET["mach_id"])) {$mach_id = htmlspecialchars($_GET["mach_id"]);} //Get mach_id for SQL query
	if (!empty($_GET["active"])) {$active = htmlspecialchars($_GET["active"]);} //Get active for SQL query
	
	// Include config file
	require_once 'config.php';
	$result = $link->query("SELECT * FROM mach WHERE id like '".$mach_id."' AND active like '".$active."'");
	
	$outp = array();
	//echo $result->num_rows;
	echo "Machines([";
	WHILE ($row =  mysqli_fetch_array($result, MYSQLI_ASSOC)){
		echo json_encode($row).",";
	}
	echo "])";
	//echo "mach_id:".$mach_id;
	//echo "active:".$active;
	//echo "Machines(".json_encode($outp).")";
?>