<?php
	session_start();

	function hasLogin($param) {
		return getSession("userType", $param);
	}
	function hasSession($param) {
		if (!isset($_SESSION[$param]))
			return false;
		else
			return true;
	}
	function getSession($param, $value=null) {
		if (!isset($_SESSION[$param]) || ($_SESSION[$param] != $value && $value != null))
			return false;
		if ($value != null)
			return true;
		if ($value == null)
			return $_SESSION[$param];
	}

	// $_SESSION["DEBUG"] = true;
	// if (hasSession("DEBUG")) {
	// 	$_SESSION["userID"] = 1;
	// 	$_SESSION["userName"] = "haha";
	// 	$_SESSION["userType"] = "teacher";
	// }

	$GLOBALS["mysqli"] = new mysqli("localhost", "sem", "sem2016", "software_eng");
	$mysqli = $GLOBALS["mysqli"];
	if (mysqli_connect_errno($mysqli)) {
		die("Connect Error: " . mysqli_connect_error());
	}
	$mysqli->query("SET CHARACTER SET utf8");
	$mysqli->query("SET NAMES UTF8");
?>
