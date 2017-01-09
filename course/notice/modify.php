<?php
	include "../../teacher/teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// need params
	$params = array("title", "content", "anid", "type");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherHasAnnouncement($mysqli, $tid, $obj->anid);
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		needPermission("p_nt_modify");
		TAHasAnnouncement($mysqli, $taid, $obj->anid);
	}

	// update announcement to database
	try {
		$atime = date('Y-m-d H:i:s',time());
		$prepared_sql = "UPDATE anouncement SET adate = ?, title = ?, content = ?, type = ? WHERE anid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('ssssi', $atime, $obj->title, $obj->content, $obj->type, $obj->anid);
			$stmt->execute();
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(537, "数据库操作错误");
	}

	response();
?>