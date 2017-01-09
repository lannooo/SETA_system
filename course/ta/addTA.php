<?php
	include "../../teacher/teacher.php";
	
	if (!hasLogin("teacher")) {
		response(412, "没有登录的教师");
	}
	$uid = getSession("userID");

	// need params
	$params = array("username", "password", "clid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	$tid = $uid;
	teacherInClass($mysqli, $tid, $obj->clid);
	// $encrypt = md5($obj->password);
	$encrypt = $obj->password;

	// add to database
	try {
		$prepared_sql = "SELECT taid FROM ta_assist WHERE username = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('s', $obj->username);
			$stmt->execute();
			$stmt->bind_result($tmp);
			$stmt->store_result();
			$num = $stmt->num_rows;
			$stmt->fetch();
			$stmt->close();
			if ($num != 0)
				response(310, "已有的助教用户名");
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
		$prepared_sql = "INSERT INTO ta_assist(taid, clid, username, password) VALUES(NULL, ?, ?, ?)";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('iss', $obj->clid, $obj->username, $encrypt);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(544, "数据库操作错误");
	}

	response();
?>