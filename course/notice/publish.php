<?php
	include "../../teacher/teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// need params
	$params = array("title", "content", "type", "coid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherInCourse($mysqli, $tid, $obj->coid);
	} else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		needPermission("p_nt_delete");
		TAInCourse($mysqli, $taid, $obj->coid);
	}

	// add announcement to database
	try {
		$atime = date('Y-m-d H:i:s',time());
		$prepared_sql = "INSERT INTO anouncement(anid, tid, coid, adate, title, content, type, read_count) VALUES(NULL, ?, ?, ?, ?, ?, ?, 0)";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('iissss', $tid, $obj->coid, $atime, $obj->title, $obj->content, $obj->type);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(541, "数据库操作错误");
	}

	response();
?>