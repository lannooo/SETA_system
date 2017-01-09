<?php
	include "../../teacher/teacher.php";

	if (!hasLogin("teacher")) {
		response(412, "没有登录的教师");
	}
	$uid = getSession("userID");

	// need params
	$params = array("taid", "password");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	$tid = $uid;
	teacherHasTA($mysqli, $tid, $obj->taid);
	
	// test or add to database
	try {
		// $encrypt = md5($obj->password);
		$encrypt = $obj->password;
		$prepared_sql = "UPDATE ta_assist SET password = ? WHERE taid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('si', $encrypt, $obj->taid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(546, "数据库操作错误");
	}

	response();
?>