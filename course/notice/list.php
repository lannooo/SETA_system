<?php
	include "../../teacher/teacher.php";

	if ((!hasLogin("teacher")) && (!hasLogin("TA"))) {
		response(410, "没有登录的教师或助教");
	}
	$uid = getSession("userID");

	// need params
	$params = array("coid");
	if (!isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		response(411, "需要JSON数据");
	}
	$obj = json_decode($GLOBALS['HTTP_RAW_POST_DATA']);
	requiredParams($params, $obj);

	if (hasLogin("teacher")) {
		$tid = $uid;
		teacherInCourse($mysqli, $tid, $obj->coid);
	}
	else {
		$taid = $uid;
		$tid = getTidFromTA($mysqli, $uid);
		TAInCourse($mysqli, $taid, $obj->coid);
	}

	// sql search get root
	$roughData = json_decode("{}");
	$roughData->info = array();
	try {
		$prepared_sql = "SELECT anid, adate, title, content, type FROM anouncement WHERE tid = ? AND coid = ?";
		if ($stmt = $mysqli->prepare($prepared_sql)) {
			$stmt->bind_param('ii', $tid, $obj->coid);
			$stmt->bind_result($o->anid, $o->adate, $o->title, $o->content, $o->type);
			$stmt->execute();
			while ($stmt->fetch()) {
				array_push($roughData->info, $o);
				$roughData = json_decode(json_encode($roughData, JSON_UNESCAPED_UNICODE));
			};
			$stmt->close();
		}
		else {
			die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}
	} catch (Exception $e) {
		response(538, "数据库查询错误");
	}

	response(0, "ok", $roughData);
?>