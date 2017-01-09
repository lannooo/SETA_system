<?php
	include "../../teacher/teacher.php";
	if (!hasLogin("teacher")) {
		response(412, "没有登录的教师");
	}
	$uid = getSession("userID");

	// need params
	$params = array("taid");
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	$tid = $uid;
	teacherHasTA($mysqli, $tid, $obj->taid);

	// delete from database
	try {
		$prepared_sql = "DELETE FROM ta_assist WHERE taid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param("i", $obj->taid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(543, "数据库操作错误");
	}
	
	response();
?>
